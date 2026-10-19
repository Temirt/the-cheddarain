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
        $burger = Product::where('slug', 'the-ultimate-cheddar-lava')->first();
        $fries = Product::where('slug', 'loaded-cheddar-bacon-fries')->first();
        $sauce = Product::where('slug', 'liquid-gold-cheddar-dip-pot')->first();

        // 1. ACTIVE PREPARING ORDER (CHED-782901)
        $order1 = Order::updateOrCreate(
            ['order_reference' => 'CHED-782901'],
            [
                'customer_name' => 'Michael Sterling',
                'customer_email' => 'michael.sterling@example.com',
                'customer_phone' => '5551234567',
                'fulfillment_type' => 'delivery',
                'delivery_address' => '742 Evergreen Terrace, Apt 4B',
                'delivery_city' => 'Springfield',
                'delivery_notes' => 'Ring doorbell twice, leave at apartment door.',
                'special_instructions' => 'Extra hot cheddar drizzle please!',
                'subtotal' => 25.47,
                'delivery_fee' => 3.99,
                'tax' => 2.16,
                'total' => 31.62,
                'status' => Order::STATUS_PREPARING,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'estimated_prep_minutes' => 12,
                'estimated_delivery_at' => Carbon::now()->addMinutes(25),
                'created_at' => Carbon::now()->subMinutes(8),
            ]
        );

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $burger?->id,
            'product_name' => 'The Ultimate Cheddar Lava',
            'unit_price' => 14.99,
            'quantity' => 1,
            'subtotal' => 14.99,
            'selected_options' => [
                ['option' => 'Bun Style', 'choice' => 'Artisanal Brioche Bun', 'price' => 0.00],
                ['option' => 'Cheese Drizzle', 'choice' => 'Extra Liquid Cheddar Drizzle', 'price' => 1.99],
            ],
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $fries?->id,
            'product_name' => 'Loaded Cheddar Bacon Fries',
            'unit_price' => 7.99,
            'quantity' => 1,
            'subtotal' => 7.99,
            'selected_options' => [],
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $sauce?->id,
            'product_name' => 'Liquid Gold Cheddar Dip Pot',
            'unit_price' => 2.49,
            'quantity' => 1,
            'subtotal' => 2.49,
            'selected_options' => [],
        ]);

        OrderStatusHistory::create([
            'order_id' => $order1->id,
            'from_status' => null,
            'to_status' => Order::STATUS_PENDING,
            'note' => 'Order received from website checkout.',
            'created_at' => Carbon::now()->subMinutes(8),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order1->id,
            'from_status' => Order::STATUS_PENDING,
            'to_status' => Order::STATUS_CONFIRMED,
            'note' => 'Order verified and accepted by kitchen dispatch.',
            'user_id' => $admin?->id,
            'created_at' => Carbon::now()->subMinutes(6),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order1->id,
            'from_status' => Order::STATUS_CONFIRMED,
            'to_status' => Order::STATUS_PREPARING,
            'note' => 'Chef started grilling Angus patties and melting cheddar. Est. 12 mins.',
            'user_id' => $admin?->id,
            'created_at' => Carbon::now()->subMinutes(3),
        ]);

        // 2. READY FOR PICKUP ORDER (CHED-782902)
        $order2 = Order::updateOrCreate(
            ['order_reference' => 'CHED-782902'],
            [
                'customer_name' => 'Sarah Connor',
                'customer_email' => 'sarah.c@example.com',
                'customer_phone' => '5551234567',
                'fulfillment_type' => 'pickup',
                'special_instructions' => 'Will arrive in silver sedan.',
                'subtotal' => 22.99,
                'delivery_fee' => 0.00,
                'tax' => 1.95,
                'total' => 24.94,
                'status' => Order::STATUS_READY_PICKUP,
                'payment_method' => 'payment_on_pickup',
                'payment_status' => 'pending',
                'estimated_prep_minutes' => 0,
                'created_at' => Carbon::now()->subMinutes(20),
            ]
        );

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $burger?->id,
            'product_name' => 'The King Cheddar Feast Combo',
            'unit_price' => 22.99,
            'quantity' => 1,
            'subtotal' => 22.99,
            'selected_options' => [
                ['option' => 'Bun Style', 'choice' => 'Toasted Sesame Seed Bun', 'price' => 0.00],
            ],
        ]);

        OrderStatusHistory::create([
            'order_id' => $order2->id,
            'from_status' => null,
            'to_status' => Order::STATUS_PENDING,
            'note' => 'Order received for store pickup.',
            'created_at' => Carbon::now()->subMinutes(20),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order2->id,
            'from_status' => Order::STATUS_PENDING,
            'to_status' => Order::STATUS_CONFIRMED,
            'note' => 'Confirmed by front counter staff.',
            'user_id' => $admin?->id,
            'created_at' => Carbon::now()->subMinutes(18),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order2->id,
            'from_status' => Order::STATUS_CONFIRMED,
            'to_status' => Order::STATUS_PREPARING,
            'note' => 'Packaging and frying loaded fries.',
            'user_id' => $admin?->id,
            'created_at' => Carbon::now()->subMinutes(12),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order2->id,
            'from_status' => Order::STATUS_PREPARING,
            'to_status' => Order::STATUS_READY_PICKUP,
            'note' => 'Bagged in heat-insulated carrier at counter 1. Ready for customer pickup!',
            'user_id' => $admin?->id,
            'created_at' => Carbon::now()->subMinutes(2),
        ]);

        // 3. COMPLETED / DELIVERED ORDER (CHED-782904)
        $order3 = Order::updateOrCreate(
            ['order_reference' => 'CHED-782904'],
            [
                'customer_name' => 'David Miller',
                'customer_email' => 'david.m@example.com',
                'customer_phone' => '5551234567',
                'fulfillment_type' => 'delivery',
                'delivery_address' => '1204 Pine Valley Road',
                'delivery_city' => 'Springfield',
                'subtotal' => 16.49,
                'delivery_fee' => 3.99,
                'tax' => 1.40,
                'total' => 21.88,
                'status' => Order::STATUS_DELIVERED,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subMinutes(60),
            ]
        );

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $burger?->id,
            'product_name' => 'The Smokey Truffle Melt',
            'unit_price' => 16.49,
            'quantity' => 1,
            'subtotal' => 16.49,
            'selected_options' => [],
        ]);

        OrderStatusHistory::create([
            'order_id' => $order3->id,
            'from_status' => Order::STATUS_READY_PICKUP,
            'to_status' => Order::STATUS_DELIVERED,
            'note' => 'Handed over directly to customer. Payment collected.',
            'user_id' => $admin?->id,
            'created_at' => Carbon::now()->subMinutes(15),
        ]);
    }
}
