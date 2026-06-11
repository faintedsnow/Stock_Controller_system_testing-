<?php

namespace Tests\Unit;

use App\Models\InventoryItem;
use App\Models\RestockOrder;
use PHPUnit\Framework\TestCase;

class InventoryLogicTest extends TestCase
{
    public function test_item_is_out_of_stock_when_stock_is_zero(): void
    {
        $item = new InventoryItem(['current_stock' => 0, 'minimum_stock' => 5]);

        $this->assertTrue($item->isOutOfStock());
    }

    public function test_item_is_not_out_of_stock_when_stock_is_positive(): void
    {
        $item = new InventoryItem(['current_stock' => 1, 'minimum_stock' => 5]);

        $this->assertFalse($item->isOutOfStock());
    }

    public function test_item_needs_restock_when_stock_equals_minimum(): void
    {
        $item = new InventoryItem(['current_stock' => 10, 'minimum_stock' => 10]);

        $this->assertTrue($item->needsRestock());
    }

    public function test_item_does_not_need_restock_when_stock_is_above_minimum(): void
    {
        $item = new InventoryItem(['current_stock' => 11, 'minimum_stock' => 10]);

        $this->assertFalse($item->needsRestock());
    }

    public function test_stock_status_is_out_of_stock_first(): void
    {
        $item = new InventoryItem(['current_stock' => 0, 'minimum_stock' => 10]);

        $this->assertSame('out_of_stock', $item->getStockStatus());
    }

    public function test_stock_status_is_low_stock_when_below_minimum(): void
    {
        $item = new InventoryItem(['current_stock' => 4, 'minimum_stock' => 10]);

        $this->assertSame('low_stock', $item->getStockStatus());
    }

    public function test_stock_status_is_in_stock_when_above_minimum(): void
    {
        $item = new InventoryItem(['current_stock' => 20, 'minimum_stock' => 10]);

        $this->assertSame('in_stock', $item->getStockStatus());
    }

    public function test_stock_value_multiplies_quantity_by_price(): void
    {
        $item = new InventoryItem(['current_stock' => 8, 'price' => 12.50]);

        $this->assertSame(100.0, $item->stock_value);
    }

    public function test_restock_unit_cost_divides_total_by_ordered_quantity(): void
    {
        $order = new RestockOrder(['quantity_ordered' => 4, 'total_cost' => 50]);

        $this->assertSame(12.5, $order->unit_cost);
    }

    public function test_restock_unit_cost_is_zero_when_quantity_is_zero(): void
    {
        $order = new RestockOrder(['quantity_ordered' => 0, 'total_cost' => 50]);

        $this->assertSame(0, $order->unit_cost);
    }
}
