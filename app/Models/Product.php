<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'brand', 'origin',
        'price', 'sale_price', 'weight', 'material',
        'short_description', 'description', 'care_instructions',
        'specifications', 'badges', 'rating', 'review_count',
        'is_featured', 'is_bestseller', 'status',
        'seo_title', 'seo_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'specifications' => 'array',
        'badges' => 'array',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
    ];

    /* ---------- Relationships ---------- */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    /* ---------- Scopes ---------- */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    public function scopeInCategory($query, string $categorySlug)
    {
        return $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
    }

    public function scopeSearch($query, string $term)
    {
        return $query->whereFullText(['name', 'short_description'], $term)
            ->orWhere('sku', 'like', "%{$term}%");
    }

    /* ---------- Accessors ---------- */

    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->sale_price) && (float) $this->sale_price < (float) $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->has_discount) {
            return 0;
        }
        return (int) round((1 - ((float) $this->sale_price / (float) $this->price)) * 100);
    }

    /**
     * Convert this model (with its loaded relations) into the exact array
     * shape App\Data\DummyCatalog produced. Blade views and the
     * <x-product-card> component were built against that shape, so this
     * is the only place that has to know both worlds — no template edits
     * were needed to move the catalog from static PHP to MySQL.
     */
    public function toDisplayArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'category' => $this->category?->name,
            'category_slug' => $this->category?->slug,
            'origin' => $this->origin,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price !== null ? (float) $this->sale_price : null,
            'rating' => (float) $this->rating,
            'review_count' => $this->review_count,
            'stock_quantity' => $this->inventory?->available_quantity ?? 0,
            'weight' => $this->weight,
            'material' => $this->material,
            'badges' => $this->badges ?? [],
            'image' => $this->images->first()?->url ?? '/images/placeholder.jpg',
            'gallery' => $this->images->pluck('url')->values()->all() ?: ['/images/placeholder.jpg'],
            'short_description' => $this->short_description,
            'description' => $this->description,
            'specifications' => $this->specifications ?? [],
            'care_instructions' => $this->care_instructions,
            'variants' => $this->variants->map(fn ($v) => [
                'id' => $v->id,
                'label' => $v->label,
                'price' => (float) $v->price,
                'sale_price' => $v->sale_price !== null ? (float) $v->sale_price : null,
            ])->all(),
        ];
    }
}
