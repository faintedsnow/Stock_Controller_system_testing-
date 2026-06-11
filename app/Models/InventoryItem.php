<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'current_stock',
        'minimum_stock',
        'price',
        'supplier_id'
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'minimum_stock' => 'integer',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the supplier for this inventory item
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * FIXED: Get restock orders for this item
     */
    public function restockOrders()
    {
        return $this->hasMany(RestockOrder::class);
    }

    /**
     * Check if item needs restocking
     */
    public function needsRestock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    /**
     * Static method to get items needing restock
     */
    public static function needingRestock()
    {
        return static::whereColumn('current_stock', '<=', 'minimum_stock');
    }

    /**
     * Scope for items needing restock
     */
    public function scopeNeedingRestock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    /**
     * Get total stock value
     */
    public function getStockValueAttribute()
    {
        return $this->current_stock * $this->price;
    }

    /**
     * Check if item is out of stock
     */
    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    /**
     * Get stock status
     */
    public function getStockStatus()
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }
        
        if ($this->needsRestock()) {
            return 'low_stock';
        }
        
        return 'in_stock';
    }

    /**
     * Get stock status label
     */
    public function getStockStatusLabel()
    {
        switch ($this->getStockStatus()) {
            case 'out_of_stock':
                return 'អស់ស្តុក';
            case 'low_stock':
                return 'ស្តុកតិច';
            default:
                return 'មានស្តុក';
        }
    }
}