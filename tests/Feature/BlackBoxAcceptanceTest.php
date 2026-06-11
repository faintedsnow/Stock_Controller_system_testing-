<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\RestockOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlackBoxAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tc_auth_001_register_with_valid_data(): void
    {
        $this->post('/register', [
            'name' => 'Black Box Tester',
            'email' => 'blackbox@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/login');

        $this->assertDatabaseHas('users', ['email' => 'blackbox@example.com']);
    }

    public function test_tc_auth_002_register_rejects_duplicate_email(): void
    {
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => 'password123',
        ]);

        $this->from('/register')->post('/register', [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/register')
            ->assertSessionHasErrors('email');
    }

    public function test_tc_auth_003_login_with_valid_credentials(): void
    {
        User::create([
            'name' => 'Login User',
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ])->assertRedirect('/dashboard');
    }

    public function test_tc_auth_004_login_rejects_wrong_password(): void
    {
        User::create([
            'name' => 'Login User',
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->from('/login')->post('/login', [
            'email' => 'login@example.com',
            'password' => 'wrong-password',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }

    public function test_tc_inv_001_create_inventory_item_with_valid_fields(): void
    {
        [$user, $supplier] = $this->userAndSupplier();

        $this->actingAs($user)->post('/inventory', [
            'name' => 'USB Cable',
            'sku' => 'USB-001',
            'current_stock' => 20,
            'minimum_stock' => 5,
            'price' => 3.50,
            'supplier_id' => $supplier->id,
            'description' => 'Type-C cable',
        ])->assertRedirect('/inventory');

        $this->assertDatabaseHas('inventory_items', ['sku' => 'USB-001']);
    }

    public function test_tc_inv_002_reject_duplicate_sku(): void
    {
        [$user, $supplier] = $this->userAndSupplier();
        InventoryItem::create([
            'name' => 'USB Cable',
            'sku' => 'USB-001',
            'current_stock' => 20,
            'minimum_stock' => 5,
            'price' => 3.50,
            'supplier_id' => $supplier->id,
        ]);

        $this->actingAs($user)->from('/inventory/create')->post('/inventory', [
            'name' => 'Duplicate USB Cable',
            'sku' => 'USB-001',
            'current_stock' => 10,
            'minimum_stock' => 5,
            'price' => 4,
            'supplier_id' => $supplier->id,
        ])->assertRedirect('/inventory/create')
            ->assertSessionHasErrors('sku');
    }

    public function test_tc_inv_003_reject_negative_current_stock(): void
    {
        [$user, $supplier] = $this->userAndSupplier();

        $this->actingAs($user)->from('/inventory/create')->post('/inventory', [
            'name' => 'Negative Stock Item',
            'sku' => 'NEG-001',
            'current_stock' => -1,
            'minimum_stock' => 5,
            'price' => 4,
            'supplier_id' => $supplier->id,
        ])->assertRedirect('/inventory/create')
            ->assertSessionHasErrors('current_stock');
    }

    public function test_tc_inv_004_removing_more_stock_than_available_clamps_to_zero(): void
    {
        [$user, $supplier] = $this->userAndSupplier();
        $item = InventoryItem::create([
            'name' => 'Mouse',
            'sku' => 'MOU-001',
            'current_stock' => 4,
            'minimum_stock' => 2,
            'price' => 9.99,
            'supplier_id' => $supplier->id,
        ]);

        $this->actingAs($user)->post("/inventory/{$item->id}/update-stock", [
            'action' => 'remove',
            'quantity' => 99,
        ])->assertRedirect('/inventory');

        $this->assertSame(0, $item->fresh()->current_stock);
    }

    public function test_tc_sup_001_create_supplier_with_valid_data(): void
    {
        $user = User::create([
            'name' => 'Supplier Tester',
            'email' => 'supplier.tester@example.com',
            'password' => 'password123',
        ]);

        $this->actingAs($user)->post('/suppliers', [
            'name' => 'Acme Supply',
            'contact_person' => 'Dara QA',
            'email' => 'sales@acme.test',
            'phone' => '012345678',
            'address' => 'Phnom Penh',
        ])->assertRedirect('/suppliers');

        $this->assertDatabaseHas('suppliers', ['name' => 'Acme Supply']);
    }

    public function test_tc_sup_002_reject_invalid_supplier_email(): void
    {
        $user = User::create([
            'name' => 'Supplier Tester',
            'email' => 'supplier.tester@example.com',
            'password' => 'password123',
        ]);

        $this->actingAs($user)->from('/suppliers/create')->post('/suppliers', [
            'name' => 'Invalid Email Supplier',
            'email' => 'abc',
        ])->assertRedirect('/suppliers/create')
            ->assertSessionHasErrors('email');
    }

    public function test_tc_restock_001_create_valid_restock_order(): void
    {
        [$user, $supplier, $item] = $this->userSupplierAndItem();

        $this->actingAs($user)->post('/restock', [
            'inventory_item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'quantity_ordered' => 10,
            'total_cost' => 45,
            'expected_date' => now()->addWeek()->toDateString(),
        ])->assertRedirect('/restock');

        $this->assertDatabaseHas('restock_orders', ['status' => 'pending']);
    }

    public function test_tc_restock_002_reject_receive_quantity_above_ordered_quantity(): void
    {
        [$user, $supplier, $item] = $this->userSupplierAndItem();
        $order = RestockOrder::create([
            'inventory_item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'quantity_ordered' => 10,
            'quantity_received' => 0,
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'total_cost' => 45,
        ]);

        $this->actingAs($user)->from('/restock')->post("/restock/{$order->id}/receive", [
            'quantity_received' => 11,
            'received_date' => now()->toDateString(),
        ])->assertRedirect('/restock')
            ->assertSessionHasErrors('quantity_received');

        $this->assertSame(0, $order->fresh()->quantity_received);
        $this->assertSame('pending', $order->fresh()->status);
    }

    private function userAndSupplier(): array
    {
        $user = User::create([
            'name' => 'Black Box Tester',
            'email' => 'blackbox@example.com',
            'password' => 'password123',
        ]);

        $supplier = Supplier::create([
            'name' => 'Acme Supply',
            'email' => 'sales@acme.test',
        ]);

        return [$user, $supplier];
    }

    private function userSupplierAndItem(): array
    {
        [$user, $supplier] = $this->userAndSupplier();
        $item = InventoryItem::create([
            'name' => 'Cable',
            'sku' => 'CAB-001',
            'current_stock' => 5,
            'minimum_stock' => 10,
            'price' => 5,
            'supplier_id' => $supplier->id,
        ]);

        return [$user, $supplier, $item];
    }
}
