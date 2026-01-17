<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        // Items with more variety
        $items = [
            // Stationery
            ['name' => 'A4 Paper Ream', 'category' => 'Stationery', 'quantity' => 50, 'unit' => 'ream', 'unit_price' => 500, 'low_stock_threshold' => 10],
            ['name' => 'Whiteboard Marker (Black)', 'category' => 'Stationery', 'quantity' => 100, 'unit' => 'pc', 'unit_price' => 50, 'low_stock_threshold' => 20],
            ['name' => 'Whiteboard Marker (Blue)', 'category' => 'Stationery', 'quantity' => 80, 'unit' => 'pc', 'unit_price' => 50, 'low_stock_threshold' => 20],
            ['name' => 'Whiteboard Marker (Red)', 'category' => 'Stationery', 'quantity' => 60, 'unit' => 'pc', 'unit_price' => 50, 'low_stock_threshold' => 20],
            ['name' => 'Whiteboard Eraser', 'category' => 'Stationery', 'quantity' => 25, 'unit' => 'pc', 'unit_price' => 150, 'low_stock_threshold' => 5],
            ['name' => 'Pen (Blue)', 'category' => 'Stationery', 'quantity' => 200, 'unit' => 'pc', 'unit_price' => 10, 'low_stock_threshold' => 50],
            ['name' => 'Pen (Black)', 'category' => 'Stationery', 'quantity' => 200, 'unit' => 'pc', 'unit_price' => 10, 'low_stock_threshold' => 50],
            ['name' => 'Notebook (100 pages)', 'category' => 'Stationery', 'quantity' => 150, 'unit' => 'pc', 'unit_price' => 80, 'low_stock_threshold' => 30],
            ['name' => 'Stapler', 'category' => 'Stationery', 'quantity' => 15, 'unit' => 'pc', 'unit_price' => 250, 'low_stock_threshold' => 5],
            ['name' => 'Staple Pins (Box)', 'category' => 'Stationery', 'quantity' => 30, 'unit' => 'box', 'unit_price' => 50, 'low_stock_threshold' => 10],
            
            // Electronics
            ['name' => 'Projector', 'category' => 'Electronics', 'quantity' => 5, 'unit' => 'pc', 'unit_price' => 45000, 'low_stock_threshold' => 1],
            ['name' => 'Laptop', 'category' => 'Electronics', 'quantity' => 10, 'unit' => 'pc', 'unit_price' => 55000, 'low_stock_threshold' => 2],
            ['name' => 'Desktop Computer', 'category' => 'Electronics', 'quantity' => 8, 'unit' => 'pc', 'unit_price' => 40000, 'low_stock_threshold' => 2],
            ['name' => 'Printer', 'category' => 'Electronics', 'quantity' => 3, 'unit' => 'pc', 'unit_price' => 15000, 'low_stock_threshold' => 1],
            ['name' => 'Scanner', 'category' => 'Electronics', 'quantity' => 2, 'unit' => 'pc', 'unit_price' => 8000, 'low_stock_threshold' => 1],
            ['name' => 'Microphone', 'category' => 'Electronics', 'quantity' => 6, 'unit' => 'pc', 'unit_price' => 2500, 'low_stock_threshold' => 2],
            ['name' => 'Speaker System', 'category' => 'Electronics', 'quantity' => 4, 'unit' => 'set', 'unit_price' => 8000, 'low_stock_threshold' => 1],
            
            // Furniture
            ['name' => 'Student Chair', 'category' => 'Furniture', 'quantity' => 150, 'unit' => 'pc', 'unit_price' => 2500, 'low_stock_threshold' => 10],
            ['name' => 'Student Desk', 'category' => 'Furniture', 'quantity' => 150, 'unit' => 'pc', 'unit_price' => 3500, 'low_stock_threshold' => 10],
            ['name' => 'Teacher Table', 'category' => 'Furniture', 'quantity' => 20, 'unit' => 'pc', 'unit_price' => 8000, 'low_stock_threshold' => 2],
            ['name' => 'Teacher Chair', 'category' => 'Furniture', 'quantity' => 20, 'unit' => 'pc', 'unit_price' => 4500, 'low_stock_threshold' => 2],
            ['name' => 'Bookshelf', 'category' => 'Furniture', 'quantity' => 12, 'unit' => 'pc', 'unit_price' => 6000, 'low_stock_threshold' => 2],
            ['name' => 'Filing Cabinet', 'category' => 'Furniture', 'quantity' => 8, 'unit' => 'pc', 'unit_price' => 7500, 'low_stock_threshold' => 2],
            
            // Cleaning Supplies
            ['name' => 'Broom', 'category' => 'Cleaning', 'quantity' => 20, 'unit' => 'pc', 'unit_price' => 150, 'low_stock_threshold' => 5],
            ['name' => 'Mop', 'category' => 'Cleaning', 'quantity' => 15, 'unit' => 'pc', 'unit_price' => 200, 'low_stock_threshold' => 5],
            ['name' => 'Cleaning Liquid (5L)', 'category' => 'Cleaning', 'quantity' => 25, 'unit' => 'bottle', 'unit_price' => 350, 'low_stock_threshold' => 5],
            ['name' => 'Dustbin (Large)', 'category' => 'Cleaning', 'quantity' => 30, 'unit' => 'pc', 'unit_price' => 500, 'low_stock_threshold' => 5],
            
            // Sports Equipment
            ['name' => 'Football', 'category' => 'Sports', 'quantity' => 10, 'unit' => 'pc', 'unit_price' => 1200, 'low_stock_threshold' => 3],
            ['name' => 'Cricket Bat', 'category' => 'Sports', 'quantity' => 8, 'unit' => 'pc', 'unit_price' => 2500, 'low_stock_threshold' => 2],
            ['name' => 'Cricket Ball', 'category' => 'Sports', 'quantity' => 15, 'unit' => 'pc', 'unit_price' => 400, 'low_stock_threshold' => 5],
            ['name' => 'Badminton Racket', 'category' => 'Sports', 'quantity' => 12, 'unit' => 'pc', 'unit_price' => 800, 'low_stock_threshold' => 4],
        ];

        foreach ($items as $itemData) {
            $item = InventoryItem::create($itemData);
            
            // Initial Stock Transaction (Purchase)
            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'type' => 'purchase',
                'quantity' => $itemData['quantity'],
                'transaction_date' => now()->subDays(rand(60, 90)),
                'notes' => 'Initial stock opening',
                'created_by' => 1,
            ]);
            
            // Add some random usage transactions for variety
            $usageCount = rand(1, 5);
            for($i = 0; $i < $usageCount; $i++) {
                $usageQty = rand(1, min(10, $itemData['quantity'] / 5));
                
                InventoryTransaction::create([
                    'inventory_item_id' => $item->id,
                    'type' => 'usage',
                    'quantity' => $usageQty,
                    'transaction_date' => now()->subDays(rand(1, 60)),
                    'notes' => 'Used for ' . $faker->randomElement(['classroom', 'office', 'lab', 'sports day', 'event']),
                    'created_by' => 1,
                ]);
                
                // Update item quantity
                $item->decrement('quantity', $usageQty);
            }
            
            // Occasionally add a purchase transaction
            if($faker->boolean(30)) {
                $purchaseQty = rand(5, 20);
                
                InventoryTransaction::create([
                    'inventory_item_id' => $item->id,
                    'type' => 'purchase',
                    'quantity' => $purchaseQty,
                    'transaction_date' => now()->subDays(rand(1, 30)),
                    'notes' => 'Restocking',
                    'created_by' => 1,
                ]);
                
                // Update item quantity
                $item->increment('quantity', $purchaseQty);
            }
        }
        
        echo "Created " . count($items) . " inventory items with transactions!\n";
    }
}
