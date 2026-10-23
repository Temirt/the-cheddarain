<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $deliveryFee = (float)RestaurantSetting::get('delivery_fee', 3.99);
        $taxRate = (float)RestaurantSetting::get('tax_rate_percent', 8.5);
        $currency = RestaurantSetting::get('currency_symbol', '$');

        return view('pages.cart', compact('deliveryFee', 'taxRate', 'currency'));
    }
}
