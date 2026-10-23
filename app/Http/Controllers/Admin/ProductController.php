<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by keyword
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('badge', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Availability filter
        if ($request->has('status') && $request->input('status') !== 'all') {
            if ($request->input('status') === 'available') {
                $query->where('is_available', true);
            } elseif ($request->input('status') === 'out_of_stock') {
                $query->where('is_available', false);
            }
        }

        $products = $query->orderBy('category_id')->orderBy('sort_order')->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $counts = [
            'total' => Product::count(),
            'available' => Product::where('is_available', true)->count(),
            'out_of_stock' => Product::where('is_available', false)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'counts'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'badge' => 'nullable|string|max:50',
            'calories' => 'nullable|integer|min:0',
            'prep_time_minutes' => 'required|integer|min:1',
            'sort_order' => 'nullable|integer',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        // Handle uploaded file if present
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $validated['image_url'] = asset('images/products/' . $filename);
        }

        if (empty($validated['image_url'])) {
            $validated['image_url'] = 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80';
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', "Menu item '{$validated['name']}' was successfully created.");
    }

    public function edit($id)
    {
        $product = Product::with('options.values')->findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'badge' => 'nullable|string|max:50',
            'calories' => 'nullable|integer|min:0',
            'prep_time_minutes' => 'required|integer|min:1',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $validated['image_url'] = asset('images/products/' . $filename);
        }

        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? $product->sort_order;

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', "Menu item '{$product->name}' was successfully updated.");
    }

    public function toggleAvailability(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->is_available = !$product->is_available;
        $product->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_available' => $product->is_available,
                'status_label' => $product->is_available ? 'Available' : 'Out of Stock',
                'message' => "Availability updated for {$product->name}."
            ]);
        }

        return back()->with('success', "Updated availability for {$product->name} to " . ($product->is_available ? 'Available' : 'Out of Stock') . '.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Menu item '{$name}' was successfully deleted.");
    }
}
