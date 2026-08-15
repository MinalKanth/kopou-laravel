<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'alt_text', 'sort_order'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Full public URL for the stored image path. Centralised here so
     * Blade views never construct storage paths themselves.
     */
    public function getUrlAttribute(): string
    {
        // Full external URLs (e.g. hotlinked stock photos while real product
        // photography isn't ready yet) are returned as-is. Anything else is
        // treated as a local path inside storage/app/public and needs the
        // public/storage symlink (php artisan storage:link) to resolve.
        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }

        return asset('storage/'.ltrim($this->path, '/'));
    }
}
