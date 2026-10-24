<?php

namespace Database\Seeders;

use App\Models\RestaurantSetting;
use Illuminate\Database\Seeder;

class RestaurantSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'restaurant_name', 'value' => 'The Cheddarloin', 'group' => 'general', 'description' => 'Restaurant brand name'],
            ['key' => 'restaurant_tagline', 'value' => 'Burgers, elevated. Made your way and prepared for delivery.', 'group' => 'general', 'description' => 'Brand tagline'],
            ['key' => 'whatsapp_number', 'value' => '251911234567', 'group' => 'contact', 'description' => 'Direct WhatsApp customer support number'],
            ['key' => 'restaurant_phone', 'value' => '+251 (911) 234-567', 'group' => 'contact', 'description' => 'Public phone number'],
            ['key' => 'restaurant_email', 'value' => 'orders@thecheddarloin.com', 'group' => 'contact', 'description' => 'Customer service email'],
            ['key' => 'restaurant_address', 'value' => 'Bole Sub-City, Melt District, Addis Ababa, Ethiopia', 'group' => 'contact', 'description' => 'Physical store address'],
            ['key' => 'telegram_link', 'value' => 'https://t.me/thecheddarloin', 'group' => 'social', 'description' => 'Telegram channel link'],
            ['key' => 'instagram_link', 'value' => 'https://instagram.com/thecheddarloin', 'group' => 'social', 'description' => 'Instagram profile link'],
            ['key' => 'opening_hours', 'value' => 'Monday - Sunday: 11:00 AM - 11:00 PM', 'group' => 'timing', 'description' => 'Store hours'],
            ['key' => 'delivery_fee', 'value' => '100.00', 'group' => 'ordering', 'description' => 'Standard delivery fee (Birr)'],
            ['key' => 'minimum_order_amount', 'value' => '200.00', 'group' => 'ordering', 'description' => 'Minimum order amount (Birr)'],
            ['key' => 'currency_symbol', 'value' => 'Birr', 'group' => 'ordering', 'description' => 'Currency symbol / label'],
            ['key' => 'currency_code', 'value' => 'ETB', 'group' => 'ordering', 'description' => 'ISO Currency Code'],
            ['key' => 'enable_pickup', 'value' => '1', 'group' => 'ordering', 'description' => 'Enable customer pickup option'],
            ['key' => 'enable_delivery', 'value' => '1', 'group' => 'ordering', 'description' => 'Enable home delivery option'],
            ['key' => 'enable_cash_on_delivery', 'value' => '1', 'group' => 'ordering', 'description' => 'Accept Cash on Delivery'],
            ['key' => 'enable_manual_payment', 'value' => '1', 'group' => 'ordering', 'description' => 'Accept Manual / In-Person Payment'],
        ];

        foreach ($settings as $setting) {
            RestaurantSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
