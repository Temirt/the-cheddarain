<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = RestaurantSetting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|string|max:100',
            'restaurant_tagline' => 'nullable|string|max:200',
            'whatsapp_number' => 'required|string|max:30',
            'restaurant_phone' => 'required|string|max:30',
            'restaurant_email' => 'required|email|max:100',
            'restaurant_address' => 'required|string|max:255',
            'opening_hours' => 'required|string|max:150',
            'delivery_fee' => 'required|numeric|min:0',
            'tax_rate_percent' => 'required|numeric|min:0|max:100',
            'currency_symbol' => 'required|string|max:5',
        ]);

        foreach ($validated as $key => $value) {
            RestaurantSetting::set($key, $value);
        }

        // Toggles
        RestaurantSetting::set('enable_pickup', $request->has('enable_pickup') ? '1' : '0');
        RestaurantSetting::set('enable_delivery', $request->has('enable_delivery') ? '1' : '0');
        RestaurantSetting::set('enable_cash_on_delivery', $request->has('enable_cash_on_delivery') ? '1' : '0');
        RestaurantSetting::set('enable_payment_on_pickup', $request->has('enable_payment_on_pickup') ? '1' : '0');

        return back()->with('success', 'Restaurant settings saved successfully.');
    }
}
