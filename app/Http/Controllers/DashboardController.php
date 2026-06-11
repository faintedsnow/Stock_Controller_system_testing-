<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get all inventory items with supplier relationship
            $items = InventoryItem::with('supplier')->get();
            
            // Calculate basic statistics
            $totalItems = $items->count();
            
            // Get items with low stock (returns a collection, not a query)
            $lowStockItems = $items->filter(function($item) {
                return $item->needsRestock();
            });
            
            // Count of items in good stock
            $inStockItems = $totalItems - $lowStockItems->count();
            
            // Count suppliers
            $totalSuppliers = Supplier::count();
            
            // Initialize restock-related variables with defaults
            $pendingOrders = 0;
            $recentOrders = collect(); // Empty collection as default
            
            // Safely check for RestockOrder functionality
            if (class_exists('\App\Models\RestockOrder') && Schema::hasTable('restock_orders')) {
                try {
                    $restockOrderClass = \App\Models\RestockOrder::class;
                    $pendingOrders = $restockOrderClass::where('status', 'pending')->count();
                    $recentOrders = $restockOrderClass::with(['inventoryItem', 'supplier'])
                        ->latest()
                        ->limit(10)
                        ->get();
                } catch (\Exception $e) {
                    // If there's any error with RestockOrder, use defaults
                    $pendingOrders = 0;
                    $recentOrders = collect();
                }
            }
            
            // Calculate total stock value
            $totalStockValue = $items->sum(function($item) {
                return $item->current_stock * $item->price;
            });
            
            // Get recent items (last 5 added)
            $recentItems = InventoryItem::with('supplier')
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard', compact(
                'totalItems',
                'lowStockItems',      // This is now properly defined as a collection
                'inStockItems',
                'totalSuppliers', 
                'pendingOrders',
                'recentOrders',
                'totalStockValue',
                'recentItems'
            ));
            
        } catch (\Exception $e) {
            // Fallback in case of any database errors
            return view('dashboard', [
                'totalItems' => 0,
                'lowStockItems' => collect(),
                'inStockItems' => 0,
                'totalSuppliers' => 0,
                'pendingOrders' => 0,
                'recentOrders' => collect(),
                'totalStockValue' => 0,
                'recentItems' => collect(),
                'error' => 'មានបញ្ហាក្នុងការទាញយកទិន្នន័យ។'
            ]);
        }
    }
}