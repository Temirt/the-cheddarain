<?php

namespace Database\Seeders;

use App\Models\RestaurantSetting;
use Illuminate\Database\Seeder;

class RestaurantSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'restaurant_name', 'value' => 'The Cheddarain', 'group' => 'general', 'description' => 'Restaurant brand name'],
            ['key' => 'restaurant_tagline', 'value' => 'Dripping in Cheddar, Crafted to Perfection', 'group' => 'general', 'description' => 'Brand tagline'],
            ['key' => 'whatsapp_number', 'value' => '15552433327', 'group' => 'contact', 'description' => 'Direct WhatsApp customer support number'],
            ['key' => 'restaurant_phone', 'value' => '+1 (555) 243-3327', 'group' => 'contact', 'description' => 'Public phone number'],
            ['key' => 'restaurant_email', 'value' => 'orders@thecheddarain.com', 'group' => 'contact', 'description' => 'Customer service email'],
            ['key' => 'restaurant_address', 'value' => '108 Artisan Burger Way, Melt District', 'group' => 'contact', 'description' => 'Physical store address'],
            ['key' => 'opening_hours', 'value' => 'Monday - Sunday: 11:00 AM - 11:00 PM', 'group' => 'timing', 'description' => 'Store hours'],
            ['key' => 'delivery_fee', 'value' => '3.99', 'group' => 'ordering', 'description' => 'Standard delivery fee ($)'],
            ['key' => 'tax_rate_percent', 'value' => '8.5', 'group' => 'ordering', 'description' => 'Estimated tax rate (%)'],
            ['key' => 'currency_symbol', 'value' => '$', 'group' => 'ordering', 'description' => 'Currency symbol'],
            ['key' => 'currency_code', 'value' => 'USD', 'group' => 'ordering', 'description' => 'ISO Currency Code'],
            ['key' => 'enable_pickup', 'value' => '1', 'group' => 'ordering', 'description' => 'Enable customer pickup option'],
            ['key' => 'enable_delivery', 'value' => '1', 'group' => 'ordering', 'description' => 'Enable home delivery option'],
            ['key' => 'enable_cash_on_delivery', 'value' => '1', 'group' => 'ordering', 'description' => 'Accept Cash on Delivery'],
            ['key' => 'enable_payment_on_pickup', 'value' => '1', 'group' => 'ordering', 'description' => 'Accept Payment at Pickup Counter'],
        ];

        foreach ($settings as $setting) {
            RestaurantSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
