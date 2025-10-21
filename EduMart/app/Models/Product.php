<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'discount_price',
        'thumbnail',
        'images',
        'type',
        'category_id',
        'content',
        'file_path',
        'duration',
        'level',
        'is_featured',
        'is_active',
        'stock_quantity',
        'sold_count',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get cart items for this product
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the current price (discount if available)
     */
    public function getCurrentPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check if product is on discount
     */
    public function isOnDiscount()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->isOnDiscount()) {
            return 0;
        }
        
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }
}
