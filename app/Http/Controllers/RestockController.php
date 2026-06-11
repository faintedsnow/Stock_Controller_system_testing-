<?php

namespace App\Http\Controllers;

use App\Models\RestockOrder;
use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    /**
     * Display a listing of restock orders
     */
    public function index()
    {
        try {
            // Check if RestockOrder model and table exist
            if (!class_exists('\App\Models\RestockOrder') || !Schema::hasTable('restock_orders')) {
                return view('restock.index', [
                    'orders' => collect([]),
                    'message' => 'ប្រព័ន្ធការបញ្ជាទិញមិនត្រូវបានដំឡើងនៅឡើយទេ។ សូមរត់ php artisan migrate ជាមុនសិន។'
                ]);
            }

            $orders = RestockOrder::with(['inventoryItem', 'supplier'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();
            
            return view('restock.index', compact('orders'));
            
        } catch (\Exception $e) {
            Log::error('Error loading restock orders: ' . $e->getMessage());
            return view('restock.index', [
                'orders' => collect([]),
                'error' => 'មានបញ្ហាក្នុងការទាញយកទិន្នន័យការបញ្ជាទិញ។'
            ]);
        }
    }

    /**
     * Show the form for creating a new restock order
     */
    public function create()
    {
        try {
            // Get all inventory items and suppliers
            $items = InventoryItem::with('supplier')->get();
            $suppliers = Supplier::all();
            
            // Get pre-selected item if coming from inventory page
            $selectedItemId = request('item_id');
            $selectedItem = null;
            
            if ($selectedItemId) {
                $selectedItem = InventoryItem::with('supplier')->find($selectedItemId);
            }

            // Check if RestockOrder functionality is available
            $hasRestockSystem = class_exists('\App\Models\RestockOrder') && Schema::hasTable('restock_orders');
            
            return view('restock.create', compact('items', 'suppliers', 'selectedItem', 'hasRestockSystem'));
            
        } catch (\Exception $e) {
            Log::error('Error loading restock create form: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'មានបញ្ហាក្នុងការបង្កើតការបញ្ជាទិញ។');
        }
    }

    /**
     * Store a newly created restock order (FIXED for your table structure)
     */
    public function store(Request $request)
    {
        try {
            // Check if the table exists first
            if (!Schema::hasTable('restock_orders')) {
                return redirect()->route('restock.create')
                    ->with('error', 'ប្រព័ន្ធការបញ្ជាទិញមិនត្រូវបានដំឡើងនៅឡើយទេ។ សូមរត់ php artisan migrate ជាមុនសិន។')
                    ->withInput();
            }

            // Validate the request (FIXED: removed unit_cost and notes, added order_date)
            $validated = $request->validate([
                'inventory_item_id' => 'required|exists:inventory_items,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'quantity_ordered' => 'required|integer|min:1',
                'total_cost' => 'required|numeric|min:0',
                'expected_date' => 'nullable|date|after_or_equal:today',
            ], [
                'inventory_item_id.required' => 'សូមជ្រើសរើសទំនិញ',
                'inventory_item_id.exists' => 'ទំនិញដែលជ្រើសរើសមិនត្រឹមត្រូវ',
                'supplier_id.required' => 'សូមជ្រើសរើសអ្នកផ្គត់ផ្គង់',
                'supplier_id.exists' => 'អ្នកផ្គត់ផ្គង់ដែលជ្រើសរើសមិនត្រឹមត្រូវ',
                'quantity_ordered.required' => 'សូមបញ្ចូលបរិមាណបញ្ជាទិញ',
                'quantity_ordered.integer' => 'បរិមាណត្រូវតែជាលេខគត់',
                'quantity_ordered.min' => 'បរិមាណត្រូវតែលើសពី 0',
                'total_cost.required' => 'សូមបញ្ចូលតម្លៃសរុប',
                'total_cost.numeric' => 'តម្លៃត្រូវតែជាលេខ',
                'total_cost.min' => 'តម្លៃត្រូវតែលើសពី 0',
                'expected_date.date' => 'កាលបរិច្ចេទមិនត្រឹមត្រូវ',
                'expected_date.after_or_equal' => 'កាលបរិច្ចេទរំពឹងត្រូវតែជាថ្ងៃនេះ ឬខាងមុខ'
            ]);

            // Get the item to verify it exists
            $item = InventoryItem::findOrFail($validated['inventory_item_id']);
            
            // Use database transaction for safety
            DB::beginTransaction();
            
            // Create the restock order (FIXED: using your table structure)
            $order = RestockOrder::create([
                'inventory_item_id' => $validated['inventory_item_id'],
                'supplier_id' => $validated['supplier_id'],
                'quantity_ordered' => $validated['quantity_ordered'],
                'quantity_received' => 0, // Default value
                'status' => 'pending',
                'order_date' => now()->toDateString(), // Current date
                'expected_date' => $validated['expected_date'],
                'total_cost' => $validated['total_cost'],
            ]);

            DB::commit();
            
            return redirect()->route('restock.index')
                ->with('success', "ការបញ្ជាទិញបន្ថែមសម្រាប់ \"{$item->name}\" ត្រូវបានបង្កើតដោយជោគជ័យ។ លេខបញ្ជាទិញ: #{$order->id}");
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollback();
            }

            Log::error('Error creating restock order: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'មានបញ្ហាក្នុងការបង្កើតការបញ្ជាទិញ។ សូមព្យាយាមម្តងទៀត។ (Error: ' . $e->getMessage() . ')')
                ->withInput();
        }
    }

    /**
     * Mark a restock order as received and update inventory
     */
    public function receive(Request $request, $orderId)
    {
        try {
            if (!Schema::hasTable('restock_orders')) {
                return redirect()->route('dashboard')
                    ->with('error', 'ប្រព័ន្ធការបញ្ជាទិញមិនត្រូវបានដំឡើងនៅឡើយទេ។');
            }

            $order = RestockOrder::with(['inventoryItem', 'supplier'])->findOrFail($orderId);

            $validated = $request->validate([
                'quantity_received' => 'required|integer|min:1|max:' . $order->quantity_ordered,
                'received_date' => 'required|date|before_or_equal:today'
            ], [
                'quantity_received.required' => 'សូមបញ្ចូលបរិមាណទទួល',
                'quantity_received.integer' => 'បរិមាណត្រូវតែជាលេខគត់',
                'quantity_received.min' => 'បរិមាណទទួលត្រូវតែលើសពី 0',
                'quantity_received.max' => 'បរិមាណទទួលមិនអាចលើសពីបរិមាណបញ្ជាទិញ',
                'received_date.required' => 'សូមជ្រើសរើសកាលបរិច្ចេទទទួល',
                'received_date.date' => 'កាលបរិច្ចេទមិនត្រឹមត្រូវ',
                'received_date.before_or_equal' => 'កាលបរិច្ចេទទទួលមិនអាចជាថ្ងៃខាងមុខ'
            ]);

            DB::beginTransaction();

            // Determine new status
            $newStatus = 'completed';
            if ($validated['quantity_received'] < $order->quantity_ordered) {
                $newStatus = 'partial';
            }

            // Update the restock order
            $order->update([
                'quantity_received' => $validated['quantity_received'],
                'received_date' => $validated['received_date'],
                'status' => $newStatus
            ]);

            // Update inventory stock
            $item = $order->inventoryItem;
            $item->current_stock += $validated['quantity_received'];
            $item->save();

            DB::commit();

            return redirect()->route('restock.index')
                ->with('success', "បានទទួលស្តុក {$validated['quantity_received']} ឯកតា សម្រាប់ \"{$item->name}\" ដោយជោគជ័យ។ ស្តុកបច្ចុប្បន្ន: " . number_format($item->current_stock));
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollback();
            }

            Log::error('Error receiving restock order: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'មានបញ្ហាក្នុងការទទួលស្តុក។ សូមព្យាយាមម្តងទៀត។');
        }
    }
}
