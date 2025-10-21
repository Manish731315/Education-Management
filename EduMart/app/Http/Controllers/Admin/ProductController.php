<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Access denied. Admin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with('category')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'thumbnail' => 'required|string|max:2048',
            'images' => 'nullable|array',
            'type' => 'required|in:course,ebook,material',
            'category_id' => 'required|exists:categories,id',
            'content' => 'nullable|string',
            'file_path' => 'nullable|string|max:2048',
            'duration' => 'nullable|integer|min:0',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        // Normalize images to array
        if (isset($validated['images']) && !is_array($validated['images'])) {
            $validated['images'] = [$validated['images']];
        }

        $product = Product::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'thumbnail' => $validated['thumbnail'],
            'images' => $validated['images'] ?? null,
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'content' => $validated['content'] ?? null,
            'file_path' => $validated['file_path'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'level' => $validated['level'] ?? null,
            'is_featured' => (bool)($validated['is_featured'] ?? false),
            'is_active' => (bool)($validated['is_active'] ?? true),
            'stock_quantity' => $validated['stock_quantity'],
        ]);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'thumbnail' => 'required|string|max:2048',
            'images' => 'nullable|array',
            'type' => 'required|in:course,ebook,material',
            'category_id' => 'required|exists:categories,id',
            'content' => 'nullable|string',
            'file_path' => 'nullable|string|max:2048',
            'duration' => 'nullable|integer|min:0',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        if (isset($validated['images']) && !is_array($validated['images'])) {
            $validated['images'] = [$validated['images']];
        }

        $product->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'thumbnail' => $validated['thumbnail'],
            'images' => $validated['images'] ?? null,
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'content' => $validated['content'] ?? null,
            'file_path' => $validated['file_path'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'level' => $validated['level'] ?? null,
            'is_featured' => (bool)($validated['is_featured'] ?? false),
            'is_active' => (bool)($validated['is_active'] ?? true),
            'stock_quantity' => $validated['stock_quantity'],
        ]);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return redirect()->back()->with('success', 'Product status updated.');
    }
}
