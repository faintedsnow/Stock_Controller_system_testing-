<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\RestockOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Support\SimpleApiToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Stock Tester',
            'email' => 'tester@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Registration successful.')
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
    }

    public function test_user_can_login_and_receive_token(): void
    {
        User::create([
            'name' => 'Stock Tester',
            'email' => 'tester@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'tester@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Login successful.')
            ->assertJsonStructure(['token']);
    }

    public function test_protected_routes_require_bearer_token(): void
    {
        $this->getJson('/api/v1/inventory')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_authenticated_user_can_create_supplier(): void
    {
        $response = $this->withToken($this->token())->postJson('/api/v1/suppliers', [
            'name' => 'Acme Supply',
            'contact_person' => 'Dara',
            'email' => 'sales@acme.test',
            'phone' => '012345678',
            'address' => 'Phnom Penh',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Supplier created successfully.')
            ->assertJsonPath('data.name', 'Acme Supply');
    }

    public function test_authenticated_user_can_create_inventory_item(): void
    {
        $supplier = Supplier::create(['name' => 'Acme Supply']);

        $response = $this->withToken($this->token())->postJson('/api/v1/inventory', [
            'name' => 'USB Cable',
            'sku' => 'USB-001',
            'current_stock' => 20,
            'minimum_stock' => 5,
            'price' => 3.5,
            'supplier_id' => $supplier->id,
            'description' => 'Type-C cable',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Inventory item created successfully.')
            ->assertJsonPath('data.sku', 'USB-001');
    }

    public function test_inventory_sku_must_be_unique(): void
    {
        InventoryItem::create([
            'name' => 'USB Cable',
            'sku' => 'USB-001',
            'current_stock' => 20,
            'minimum_stock' => 5,
            'price' => 3.5,
        ]);

        $response = $this->withToken($this->token())->postJson('/api/v1/inventory', [
            'name' => 'Duplicate Cable',
            'sku' => 'USB-001',
            'current_stock' => 10,
            'minimum_stock' => 5,
            'price' => 4,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    }

    public function test_authenticated_user_can_update_stock_quantity(): void
    {
        $item = InventoryItem::create([
            'name' => 'Mouse',
            'sku' => 'MOU-001',
            'current_stock' => 10,
            'minimum_stock' => 3,
            'price' => 9.99,
        ]);

        $response = $this->withToken($this->token())->postJson("/api/v1/inventory/{$item->id}/stock", [
            'action' => 'remove',
            'quantity' => 7,
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Stock updated successfully.')
            ->assertJsonPath('data.current_stock', 3);
    }

    public function test_removing_more_stock_than_available_clamps_to_zero(): void
    {
        $item = InventoryItem::create([
            'name' => 'Keyboard',
            'sku' => 'KEY-001',
            'current_stock' => 4,
            'minimum_stock' => 3,
            'price' => 15,
        ]);

        $response = $this->withToken($this->token())->postJson("/api/v1/inventory/{$item->id}/stock", [
            'action' => 'remove',
            'quantity' => 99,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.current_stock', 0);
    }

    public function test_authenticated_user_can_create_restock_order(): void
    {
        [$supplier, $item] = $this->supplierAndItem();

        $response = $this->withToken($this->token())->postJson('/api/v1/restock-orders', [
            'inventory_item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'quantity_ordered' => 25,
            'total_cost' => 125,
            'expected_date' => now()->addWeek()->toDateString(),
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Restock order created successfully.')
            ->assertJsonPath('data.status', 'pending');
    }

    public function test_receiving_restock_order_updates_inventory_stock(): void
    {
        [$supplier, $item] = $this->supplierAndItem();
        $order = RestockOrder::create([
            'inventory_item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'quantity_ordered' => 10,
            'quantity_received' => 0,
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'total_cost' => 50,
        ]);

        $response = $this->withToken($this->token())->postJson("/api/v1/restock-orders/{$order->id}/receive", [
            'quantity_received' => 10,
            'received_date' => now()->toDateString(),
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Restock order received successfully.')
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.inventory_item.current_stock', 15);
    }

    private function token(): string
    {
        $user = User::first() ?? User::create([
            'name' => 'API Tester',
            'email' => 'api@example.com',
            'password' => 'password123',
        ]);

        return SimpleApiToken::issue($user);
    }

    private function supplierAndItem(): array
    {
        $supplier = Supplier::create(['name' => 'Acme Supply']);
        $item = InventoryItem::create([
            'name' => 'Cable',
            'sku' => 'CAB-001',
            'current_stock' => 5,
            'minimum_stock' => 10,
            'price' => 5,
            'supplier_id' => $supplier->id,
        ]);

        return [$supplier, $item];
    }
}
