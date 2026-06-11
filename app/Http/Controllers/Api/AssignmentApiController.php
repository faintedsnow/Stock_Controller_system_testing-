<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\RestockOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Support\SimpleApiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AssignmentApiController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($validated);

        return response()->json([
            'message' => 'Registration successful.',
            'token' => SimpleApiToken::issue($user),
            'user' => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 422);
        }

        return response()->json([
            'message' => 'Login successful.',
            'token' => SimpleApiToken::issue($user),
            'user' => $user,
        ]);
    }

    public function suppliers(): JsonResponse
    {
        return response()->json([
            'data' => Supplier::withCount('inventoryItems')->latest()->get(),
        ]);
    }

    public function storeSupplier(Request $request): JsonResponse
    {
        $supplier = Supplier::create($this->validateSupplier($request));

        return response()->json([
            'message' => 'Supplier created successfully.',
            'data' => $supplier,
        ], 201);
    }

    public function showSupplier(Supplier $supplier): JsonResponse
    {
        return response()->json([
            'data' => $supplier->loadCount('inventoryItems'),
        ]);
    }

    public function updateSupplier(Request $request, Supplier $supplier): JsonResponse
    {
        $supplier->update($this->validateSupplier($request));

        return response()->json([
            'message' => 'Supplier updated successfully.',
            'data' => $supplier->fresh(),
        ]);
    }

    public function deleteSupplier(Supplier $supplier): JsonResponse
    {
        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted successfully.',
        ]);
    }

    public function inventory(): JsonResponse
    {
        return response()->json([
            'data' => InventoryItem::with('supplier')->latest()->get(),
        ]);
    }

    public function storeInventory(Request $request): JsonResponse
    {
        $item = InventoryItem::create($this->validateInventory($request));

        return response()->json([
            'message' => 'Inventory item created successfully.',
            'data' => $item->load('supplier'),
        ], 201);
    }

    public function showInventory(InventoryItem $inventory): JsonResponse
    {
        return response()->json([
            'data' => $inventory->load('supplier'),
        ]);
    }

    public function updateInventory(Request $request, InventoryItem $inventory): JsonResponse
    {
        $inventory->update($this->validateInventory($request, $inventory));

        return response()->json([
            'message' => 'Inventory item updated successfully.',
            'data' => $inventory->fresh()->load('supplier'),
        ]);
    }

    public function deleteInventory(InventoryItem $inventory): JsonResponse
    {
        $inventory->delete();

        return response()->json([
            'message' => 'Inventory item deleted successfully.',
        ]);
    }

    public function updateStock(Request $request, InventoryItem $inventory): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'action' => ['required', Rule::in(['add', 'remove'])],
        ]);

        if ($validated['action'] === 'add') {
            $inventory->current_stock += $validated['quantity'];
        } else {
            $inventory->current_stock = max(0, $inventory->current_stock - $validated['quantity']);
        }

        $inventory->save();

        return response()->json([
            'message' => 'Stock updated successfully.',
            'data' => $inventory->fresh(),
        ]);
    }

    public function restockOrders(): JsonResponse
    {
        return response()->json([
            'data' => RestockOrder::with(['inventoryItem', 'supplier'])->latest()->get(),
        ]);
    }

    public function storeRestockOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'inventory_item_id' => ['required', 'exists:inventory_items,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'quantity_ordered' => ['required', 'integer', 'min:1'],
            'total_cost' => ['required', 'numeric', 'min:0'],
            'expected_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $order = RestockOrder::create([
            ...$validated,
            'quantity_received' => 0,
            'status' => 'pending',
            'order_date' => now()->toDateString(),
        ]);

        return response()->json([
            'message' => 'Restock order created successfully.',
            'data' => $order->load(['inventoryItem', 'supplier']),
        ], 201);
    }

    public function receiveRestockOrder(Request $request, RestockOrder $order): JsonResponse
    {
        $validated = $request->validate([
            'quantity_received' => ['required', 'integer', 'min:1', 'max:'.$order->quantity_ordered],
            'received_date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        DB::transaction(function () use ($order, $validated): void {
            $status = $validated['quantity_received'] < $order->quantity_ordered ? 'partial' : 'completed';

            $order->update([
                'quantity_received' => $validated['quantity_received'],
                'received_date' => $validated['received_date'],
                'status' => $status,
            ]);

            $item = $order->inventoryItem;
            $item->current_stock += $validated['quantity_received'];
            $item->save();
        });

        return response()->json([
            'message' => 'Restock order received successfully.',
            'data' => $order->fresh()->load(['inventoryItem', 'supplier']),
        ]);
    }

    private function validateSupplier(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);
    }

    private function validateInventory(Request $request, ?InventoryItem $inventory = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', Rule::unique('inventory_items', 'sku')->ignore($inventory)],
            'current_stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
