<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class Inventory extends Model
{
    protected $fillable = ['product_id', 'stock_quantity', 'reserved_quantity', 'low_stock_threshold'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getAvailableQuantityAttribute(): int
    {
        return max(0, $this->stock_quantity - $this->reserved_quantity);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->available_quantity > 0 && $this->available_quantity <= $this->low_stock_threshold;
    }

    /**
     * Reserve stock for a pending order (Section 14). Uses a row lock +
     * transaction so two concurrent checkouts can never both succeed
     * past available stock.
     */
    public static function reserve(int $productId, int $quantity): void
    {
        DB::transaction(function () use ($productId, $quantity) {
            $inventory = self::where('product_id', $productId)->lockForUpdate()->firstOrFail();

            if ($inventory->available_quantity < $quantity) {
                throw new RuntimeException("Insufficient stock for product #{$productId}.");
            }

            $inventory->increment('reserved_quantity', $quantity);
        });
    }

    /** Finalize a reservation once payment/order is confirmed: deduct stock, release the reservation. */
    public static function finalize(int $productId, int $quantity): void
    {
        DB::transaction(function () use ($productId, $quantity) {
            $inventory = self::where('product_id', $productId)->lockForUpdate()->firstOrFail();
            $inventory->decrement('stock_quantity', $quantity);
            $inventory->decrement('reserved_quantity', min($quantity, $inventory->reserved_quantity));
        });
    }

    /** Release a reservation (cart expired, order cancelled) without touching physical stock. */
    public static function release(int $productId, int $quantity): void
    {
        DB::transaction(function () use ($productId, $quantity) {
            $inventory = self::where('product_id', $productId)->lockForUpdate()->firstOrFail();
            $inventory->decrement('reserved_quantity', min($quantity, $inventory->reserved_quantity));
        });
    }
}
