<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $selectedCategory = $request->query('category', 'all');
        $search = $request->query('search');

        $query = Product::with('category', 'options.values')->where('is_available', true);

        if ($selectedCategory !== 'all') {
            $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('sort_order')->get();

        return view('pages.menu', compact('categories', 'products', 'selectedCategory', 'search'));
    }

    public function show($slug)
    {
        $product = Product::with('category', 'options.values')
            ->where('slug', $slug)
            ->where('is_available', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    /**
     * API endpoint to return product options JSON for modal customization
     */
    public function getOptionsJson($id)
    {
        $product = Product::with('options.values')->findOrFail($id);
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float)$product->price,
            'description' => $product->description,
            'image_url' => $product->image_url,
            'options' => $product->options,
        ]);
    }
}
