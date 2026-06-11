<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'supplier_id',
        'quantity_ordered',
        'quantity_received',
        'status',
        'order_date',
        'expected_date',
        'received_date',
        'total_cost'
    ];

    protected $casts = [
        'quantity_ordered' => 'integer',
        'quantity_received' => 'integer',
        'total_cost' => 'decimal:2',
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the inventory item for this restock order
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the supplier for this restock order
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Check if order is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->expected_date && $this->expected_date < now();
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'completed':
                return 'bg-success';
            case 'pending':
                return $this->isOverdue() ? 'bg-danger' : 'bg-warning';
            case 'partial':
                return 'bg-info';
            default:
                return 'bg-primary';
        }
    }

    /**
     * Get status label in Khmer
     */
    public function getStatusLabel()
    {
        switch ($this->status) {
            case 'completed':
                return 'បានបញ្ចប់';
            case 'pending':
                return 'កំពុងរង់ចាំ';
            case 'partial':
                return 'មិនពេញលេញ';
            default:
                return 'មិនស្គាល់';
        }
    }

    /**
     * Calculate unit cost based on total cost and quantity
     */
    public function getUnitCostAttribute()
    {
        return $this->quantity_ordered > 0 ? $this->total_cost / $this->quantity_ordered : 0;
    }
}