<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address'
    ];

    /**
     * Get the inventory items for this supplier
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get restock orders for this supplier
     */
    public function restockOrders()
    {
        return $this->hasMany(RestockOrder::class);
    }
}