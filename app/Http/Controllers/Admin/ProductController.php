<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of jewelry pieces in the vault.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage', 'variants']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('materials', 'like', "%{$search}%")
                    ->orWhere('gemstone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->boolean('low_stock')) {
            $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new jewelry creation.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created jewelry piece in the vault.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'materials' => ['nullable', 'string', 'max:150'],
            'metal_color' => ['nullable', 'string', 'max:50'],
            'gemstone' => ['nullable', 'string', 'max:150'],
            'weight_grams' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'image_url' => ['nullable', 'string', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_new_arrival' => ['nullable', 'boolean'],
            'is_bestseller' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.strtolower(Str::random(4));
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new_arrival'] = $request->boolean('is_new_arrival');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['low_stock_threshold'] = $validated['low_stock_threshold'] ?? 3;

        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        $product = Product::create($validated);

        if ($imageUrl) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imageUrl,
                'is_primary' => true,
                'sort_order' => 0,
                'alt_text' => $product->name,
            ]);
        }

        return redirect()->route('admin.products.index')->with('status', 'Fine jewelry piece added to the vault successfully.');
    }

    /**
     * Show the form for editing the jewelry creation.
     */
    public function edit(Product $product): View
    {
        $product->load(['images', 'variants', 'attributes']);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified jewelry piece.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'materials' => ['nullable', 'string', 'max:150'],
            'metal_color' => ['nullable', 'string', 'max:50'],
            'gemstone' => ['nullable', 'string', 'max:150'],
            'weight_grams' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'image_url' => ['nullable', 'string', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_new_arrival' => ['nullable', 'boolean'],
            'is_bestseller' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new_arrival'] = $request->boolean('is_new_arrival');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['low_stock_threshold'] = $validated['low_stock_threshold'] ?? 3;

        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        $product->update($validated);

        if ($imageUrl) {
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_primary' => true],
                ['image_path' => $imageUrl, 'sort_order' => 0, 'alt_text' => $product->name]
            );
        }

        return redirect()->route('admin.products.index')->with('status', 'Jewelry creation updated successfully.');
    }

    /**
     * Remove the specified jewelry piece from the vault.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Piece removed from vault.');
    }
}
