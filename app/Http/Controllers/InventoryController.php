<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items
     */
    public function index()
    {
        $items = InventoryItem::with('supplier')->get();
        return view('inventory.index', compact('items'));
    }

    /**
     * Show the form for creating a new inventory item
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('inventory.create', compact('suppliers'));
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string'
        ]);

        InventoryItem::create($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'ទំនិញត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * Show the form for editing an inventory item
     */
    public function edit(InventoryItem $inventory)
    {
        $suppliers = Supplier::all();
        // Pass as 'item' to maintain compatibility with the view
        return view('inventory.edit', ['item' => $inventory, 'suppliers' => $suppliers]);
    }

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items,sku,' . $inventory->id,
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string'
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'ទំនិញត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។');
    }

    /**
     * Remove the specified inventory item
     */
    public function destroy(InventoryItem $inventory)
    {
        try {
            $itemName = $inventory->name;
            $inventory->delete();
            
            return redirect()->route('inventory.index')
                ->with('success', "ទំនិញ \"{$itemName}\" ត្រូវបានលុបដោយជោគជ័យ។");
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', 'មានបញ្ហាក្នុងការលុបទំនិញ។ សូមព្យាយាមម្តងទៀត។');
        }
    }

    /**
     * Update stock quantity (add or remove stock)
     */
    public function updateStock(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:add,remove'
        ]);

        $oldStock = $inventory->current_stock;

        try {
            if ($request->action === 'add') {
                $inventory->current_stock += $request->quantity;
                $message = "បានបន្ថែមស្តុក " . number_format($request->quantity) . " ឯកតា។ ស្តុកបច្ចុប្បន្ន: " . number_format($inventory->current_stock);
            } else {
                $inventory->current_stock = max(0, $inventory->current_stock - $request->quantity);
                $actualRemoved = $oldStock - $inventory->current_stock;
                $message = "បានដកស្តុក " . number_format($actualRemoved) . " ឯកតា។ ស្តុកបច្ចុប្បន្ន: " . number_format($inventory->current_stock);
            }

            $inventory->save();

            // Check if request came from edit page or index page
            $referer = $request->header('referer');
            if ($referer && strpos($referer, 'edit') !== false) {
                return redirect()->route('inventory.edit', $inventory)
                    ->with('success', $message);
            } else {
                return redirect()->route('inventory.index')
                    ->with('success', $message);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'មានបញ្ហាក្នុងការធ្វើបច្ចុប្បន្នភាពស្តុក។ សូមព្យាយាមម្តងទៀត។');
        }
    }
}