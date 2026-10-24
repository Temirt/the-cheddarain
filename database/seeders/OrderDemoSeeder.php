<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $burger = Product::where('slug', 'the-cheddarloin-signature-beef')->first() ?? Product::first();
        $fries = Product::where('slug', 'loaded-cheddar-fries')->first() ?? Product::skip(1)->first();
        $drink = Product::where('slug', 'coca-cola')->first() ?? Product::skip(2)->first();

        // Clean demo items first
        OrderItem::whereIn('product_name', ['The Ultimate Cheddar Lava', 'Loaded Cheddar Bacon Fries', 'Liquid Gold Cheddar Dip Pot'])->delete();

        // 1. ACTIVE PREPARING ORDER (CHED-782901)
        $order1 = Order::updateOrCreate(
            ['order_number' => 'CHED-782901'],
            [
                'order_reference' => 'CHED-782901',
                'customer_name' => 'Michael Sterling',
                'customer_email' => 'michael.sterling@example.com',
                'customer_phone' => '5551234567',
                'fulfillment_type' => 'delivery',
                'delivery_address' => 'Bole Sub-City, Road 3, House 45B',
                'delivery_city' => 'Addis Ababa',
                'delivery_instructions' => 'Ring gate bell, call upon arrival.',
                'subtotal' => 1300.00,
                'delivery_fee' => 100.00,
                'tax' => 0.00,
                'total' => 1400.00,
                'status' => Order::STATUS_PREPARING,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'estimated_prep_minutes' => 12,
                'estimated_delivery_at' => Carbon::now()->addMinutes(25),
                'created_at' => Carbon::now()->subMinutes(8),
            ]
        );

        $order1->items()->delete();
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $burger?->id,
            'product_name' => $burger?->name ?? 'The Cheddarloin Signature Beef',
            'unit_price' => 900.00,
            'quantity' => 1,
            'subtotal' => 900.00,
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $fries?->id,
            'product_name' => $fries?->name ?? 'Loaded Cheddar Fries',
            'unit_price' => 320.00,
            'quantity' => 1,
            'subtotal' => 320.00,
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $drink?->id,
            'product_name' => $drink?->name ?? 'Coca-Cola',
            'unit_price' => 80.00,
            'quantity' => 1,
            'subtotal' => 80.00,
        ]);

        // 2. READY FOR DISPATCH ORDER (CHED-782902)
        $order2 = Order::updateOrCreate(
            ['order_number' => 'CHED-782902'],
            [
                'order_reference' => 'CHED-782902',
                'customer_name' => 'Sara Jenkins',
                'customer_email' => 'sara.jenkins@example.com',
                'customer_phone' => '5551234567',
                'fulfillment_type' => 'pickup',
                'delivery_address' => 'Store Pickup Counter',
                'delivery_city' => 'Addis Ababa',
                'delivery_instructions' => 'Customer will pick up at counter.',
                'subtotal' => 950.00,
                'delivery_fee' => 0.00,
                'tax' => 0.00,
                'total' => 950.00,
                'status' => Order::STATUS_READY_PICKUP,
                'payment_method' => 'manual_payment',
                'payment_status' => 'pending',
                'created_at' => Carbon::now()->subMinutes(25),
            ]
        );

        $order2->items()->delete();
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $burger?->id,
            'product_name' => 'The Cheddarloin Signature Chicken',
            'unit_price' => 950.00,
            'quantity' => 1,
            'subtotal' => 950.00,
        ]);

        // 3. COMPLETED DELIVERED ORDER (CHED-782904)
        $order3 = Order::updateOrCreate(
            ['order_number' => 'CHED-782904'],
            [
                'order_reference' => 'CHED-782904',
                'customer_name' => 'David Miller',
                'customer_email' => 'david.miller@example.com',
                'customer_phone' => '5551234567',
                'fulfillment_type' => 'delivery',
                'delivery_address' => 'Olympia District, Bldg 12',
                'delivery_city' => 'Addis Ababa',
                'subtotal' => 1500.00,
                'delivery_fee' => 100.00,
                'tax' => 0.00,
                'total' => 1600.00,
                'status' => Order::STATUS_DELIVERED,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subHours(2),
            ]
        );

        $order3->items()->delete();
        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $burger?->id,
            'product_name' => 'The Grand Cheddarloin',
            'unit_price' => 1500.00,
            'quantity' => 1,
            'subtotal' => 1500.00,
        ]);
    }
}
