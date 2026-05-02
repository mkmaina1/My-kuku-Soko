<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketplace extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'product_type',
        'title',
        'description',
        'price',
        'quantity',
        'unit',
        'category',
        'image',
        'location',
        'is_verified',
        'is_available',
        'min_order',
        'max_order',
        'tags',
        'rating',
        'total_ratings',
        'views',
        'orders_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'min_order' => 'integer',
        'max_order' => 'integer',
        'is_verified' => 'boolean',
        'is_available' => 'boolean',
        'rating' => 'decimal:2',
        'total_ratings' => 'integer',
        'views' => 'integer',
        'orders_count' => 'integer',
        'tags' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Get the supplier who owns the product.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }


    /**
     * Get the cart items for this product.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    /**
     * Get the order items for this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    /**
     * Scope a query to only include available products.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include verified products.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Scope a query to only include products of a specific category.
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include products of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('product_type', $type);
    }

    /**
     * Scope a query to only include products in a location.
     */
    public function scopeInLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    /**
     * Scope a query to only include products within price range.
     */
    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Check if product is in stock.
     */
    public function inStock(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Check if product is low in stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= 10 && $this->quantity > 0;
    }

    /**
     * Check if product is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Get the stock status.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        }

        if ($this->isLowStock()) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    /**
     * Get the stock status badge color.
     */
    public function getStockStatusBadgeAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'danger';
        }

        if ($this->isLowStock()) {
            return 'warning';
        }

        return 'success';
    }

    /**
     * Get the availability status.
     */
    public function getAvailabilityStatusAttribute(): string
    {
        if (!$this->is_available) {
            return 'Unavailable';
        }

        return $this->stock_status;
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'KES ' . number_format($this->price, 2);
    }

    /**
     * Get the formatted total value.
     */
    public function getTotalValueAttribute(): string
    {
        return 'KES ' . number_format($this->price * $this->quantity, 2);
    }

    /**
     * Get the product image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // Default product images based on category
        $defaultImages = [
            'poultry' => 'https://images.unsplash.com/photo-1599733873573-8fb7ff8a5d50?w=300&h=200&fit=crop',
            'livestock' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=300&h=200&fit=crop',
            'equipment' => 'https://images.unsplash.com/photo-1589923186741-b7d59d6b2c4c?w=300&h=200&fit=crop',
            'feed' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&h=200&fit=crop',
        ];

        return $defaultImages[$this->category] ?? 'https://via.placeholder.com/300x200/1cc88a/ffffff?text=' . urlencode($this->category);
    }

    /**
     * Get the product thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return 'https://via.placeholder.com/150/1cc88a/ffffff?text=' . urlencode(substr($this->title, 0, 2));
    }

    /**
     * Get the category icon.
     */
    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'poultry' => 'fas fa-egg',
            'livestock' => 'fas fa-paw',
            'equipment' => 'fas fa-tools',
            'feed' => 'fas fa-seedling',
            'medicine' => 'fas fa-pills',
            default => 'fas fa-box',
        };
    }

    /**
     * Get the product type icon.
     */
    public function getProductTypeIconAttribute(): string
    {
        return match($this->product_type) {
            'chicks' => 'fas fa-kiwi-bird',
            'feed' => 'fas fa-wheat-awn',
            'medicine' => 'fas fa-syringe',
            'equipment' => 'fas fa-toolbox',
            'eggs' => 'fas fa-egg',
            default => 'fas fa-box',
        };
    }

    /**
     * Get the rating stars HTML.
     */
    public function getRatingStarsAttribute(): string
    {
        $fullStars = floor($this->rating);
        $halfStar = ($this->rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        $html = '';

        // Full stars
        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '<i class="fas fa-star text-warning"></i>';
        }

        // Half star
        if ($halfStar) {
            $html .= '<i class="fas fa-star-half-alt text-warning"></i>';
        }

        // Empty stars
        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '<i class="far fa-star text-warning"></i>';
        }

        return $html;
    }

    /**
     * Get short description (truncated).
     */
    public function getShortDescriptionAttribute(): string
    {
        return strlen($this->description) > 100
            ? substr($this->description, 0, 100) . '...'
            : $this->description;
    }

    /**
     * Get the verification badge.
     */
    public function getVerificationBadgeAttribute(): string
    {
        if ($this->is_verified) {
            return '<span class="badge badge-primary"><i class="fas fa-check-circle mr-1"></i>Verified</span>';
        }

        return '<span class="badge badge-secondary"><i class="fas fa-clock mr-1"></i>Pending</span>';
    }

    /**
     * Check if product can be ordered in given quantity.
     */
    public function canOrderQuantity($quantity): bool
    {
        if ($quantity < $this->min_order) {
            return false;
        }

        if ($this->max_order && $quantity > $this->max_order) {
            return false;
        }

        if ($quantity > $this->quantity) {
            return false;
        }

        return true;
    }

    /**
     * Get available quantity for ordering.
     */
    public function getAvailableForOrderAttribute(): int
    {
        $max = $this->max_order ? min($this->max_order, $this->quantity) : $this->quantity;
        return $max;
    }

    /**
     * Update product rating.
     */
    public function updateRating($newRating)
    {
        $totalRating = ($this->rating * $this->total_ratings) + $newRating;
        $this->total_ratings++;
        $this->rating = $totalRating / $this->total_ratings;
        $this->save();
    }

    /**
     * Decrement product quantity.
     */
    public function decrementQuantity($amount = 1)
    {
        $this->decrement('quantity', $amount);

        // If quantity reaches zero, mark as unavailable
        if ($this->quantity <= 0) {
            $this->update(['is_available' => false]);
        }
    }

    /**
     * Increment product quantity.
     */
    public function incrementQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);

        // If quantity was zero and now has stock, mark as available
        if ($this->quantity > 0 && !$this->is_available) {
            $this->update(['is_available' => true]);
        }
    }

    /**
     * Get similar products.
     */
    public function similarProducts($limit = 4)
    {
        return self::where('category', $this->category)
            ->where('id', '!=', $this->id)
            ->where('is_available', true)
            ->inStock()
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular products from same supplier.
     */
    public function supplierOtherProducts($limit = 3)
    {
        return self::where('supplier_id', $this->supplier_id)
            ->where('id', '!=', $this->id)
            ->where('is_available', true)
            ->orderBy('orders_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get product statistics.
     */
    public function getStatisticsAttribute(): array
    {
        return [
            'total_orders' => $this->orders_count,
            'total_views' => $this->views,
            'conversion_rate' => $this->views > 0
                ? round(($this->orders_count / $this->views) * 100, 2)
                : 0,
            'total_value' => $this->price * $this->quantity,
            'avg_rating' => $this->rating,
            'total_ratings' => $this->total_ratings,
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($product) {
            // Set default rating if not set
            if (!$product->rating) {
                $product->rating = 0;
            }

            // Set default total_ratings if not set
            if (!$product->total_ratings) {
                $product->total_ratings = 0;
            }

            // Set default views if not set
            if (!$product->views) {
                $product->views = 0;
            }

            // Set default orders_count if not set
            if (!$product->orders_count) {
                $product->orders_count = 0;
            }

            // Set default is_available based on quantity
            if ($product->quantity > 0 && !isset($product->is_available)) {
                $product->is_available = true;
            }
        });
    }

    /**
 * Check if product belongs to agent.
 */
public function isAgentProduct(): bool
{
    return $this->supplier->role === 'agent';
}

/**
 * Get agent products.
 */
public function scopeAgentProducts($query)
{
    return $query->whereHas('supplier', function($q) {
        $q->where('role', 'agent');
    });
}

/**
 * Get non-agent products (supplier products).
 */
public function scopeSupplierProducts($query)
{
    return $query->whereHas('supplier', function($q) {
        $q->where('role', 'supplier');
    });
}
}
