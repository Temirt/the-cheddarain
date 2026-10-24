<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $products = Product::with('category', 'options.values')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();
        $featuredProducts = $products->where('is_featured', true)->take(8);

        $stats = [
            'total_burgers' => Product::count(),
            'happy_customers' => '12,500+',
            'rating' => '4.9',
            'prep_time' => '12-15 min',
        ];

        return view('pages.home', compact('categories', 'products', 'featuredProducts', 'stats'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        // In a real deployment this can email the manager; for now we flash success message
        return back()->with('success', 'Thank you! Your message has been sent to our culinary team.');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
