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
        return asset('storage/'.ltrim($this->path, '/'));
    }
}
