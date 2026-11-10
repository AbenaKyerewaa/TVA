<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    /**
     * Display the TVA Luxury Jewelry homepage.
     */
    public function index(): View
    {
        $featuredCategories = Category::where('is_featured', true)
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredProducts = Product::with(['category', 'primaryImage', 'images'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $newArrivals = Product::with(['category', 'primaryImage'])
            ->where('is_new_arrival', true)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        $bestSellers = Product::with(['category', 'primaryImage'])
            ->where('is_bestseller', true)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('storefront.home', compact('featuredCategories', 'featuredProducts', 'newArrivals', 'bestSellers'));
    }

    /**
     * Display all curated collections overview.
     */
    public function collections(): View
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('storefront.collections', compact('categories'));
    }

    /**
     * Display a specific collection landing page.
     */
    public function collection(Request $request, string $slug): View
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->withCount('products')
            ->firstOrFail();

        $query = Product::with(['category', 'primaryImage', 'variants'])
            ->where('category_id', $category->id)
            ->where('is_active', true);

        // Bridal Segment filter
        if ($request->filled('segment')) {
            if ($request->segment === 'gowns') {
                $query->where('name', 'like', '%Gown%');
            } elseif ($request->segment === 'suits') {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%Suit%')
                        ->orWhere('name', 'like', '%Tuxedo%')
                        ->orWhere('name', 'like', '%Jacket%');
                });
            }
        }

        // Apply shared filters
        $this->applyCatalogFilters($request, $query);

        $products = $query->paginate(12)->withQueryString();
        $allCategories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('storefront.collection-show', compact('category', 'products', 'allCategories'));
    }

    /**
     * Display the comprehensive product catalog with search, filtering, and sorting.
     */
    public function catalog(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage', 'variants'])->where('is_active', true);

        // Search by keyword / SKU
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('materials', 'like', "%{$keyword}%")
                    ->orWhere('gemstone', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Bridal Segment filter (Gowns vs Suits)
        if ($request->filled('segment')) {
            if ($request->segment === 'gowns') {
                $query->where('name', 'like', '%Gown%');
            } elseif ($request->segment === 'suits') {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%Suit%')
                        ->orWhere('name', 'like', '%Tuxedo%')
                        ->orWhere('name', 'like', '%Jacket%');
                });
            }
        }

        // Bridal Segment filter
        if ($request->filled('segment')) {
            if ($request->segment === 'gowns') {
                $query->where('name', 'like', '%Gown%');
            } elseif ($request->segment === 'suits') {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%Suit%')
                        ->orWhere('name', 'like', '%Tuxedo%')
                        ->orWhere('name', 'like', '%Jacket%');
                });
            }
        }

        // Apply shared filters
        $this->applyCatalogFilters($request, $query);

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->withCount('products')->orderBy('sort_order')->get();

        // Available metals and gemstones for filter facets
        $availableMetals = Product::where('is_active', true)->whereNotNull('metal_color')->distinct()->pluck('metal_color');
        $availableGemstones = Product::where('is_active', true)->whereNotNull('gemstone')->distinct()->pluck('gemstone');

        return view('storefront.catalog', compact('products', 'categories', 'availableMetals', 'availableGemstones'));
    }

    /**
     * Display single product detail page with variants, specs, and reviews.
     */
    public function product(string $slug): View
    {
        $product = Product::with([
            'category',
            'images',
            'variants' => fn ($q) => $q->where('is_active', true),
            'attributes',
            'reviews' => fn ($q) => $q->where('is_approved', true)->with('user'),
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('storefront.product', compact('product', 'relatedProducts'));
    }

    /**
     * Instant JSON search endpoint for quick header discovery.
     */
    public function searchApi(Request $request): JsonResponse
    {
        $keyword = trim($request->query('q', ''));

        if (strlen($keyword) < 2) {
            return response()->json(['results' => []]);
        }

        $results = Product::with('primaryImage')
            ->where('is_active', true)
            ->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('materials', 'like', "%{$keyword}%");
            })
            ->take(6)
            ->get(['id', 'name', 'slug', 'sku', 'price', 'materials'])
            ->map(fn ($p) => [
                'name' => $p->name,
                'slug' => $p->slug,
                'sku' => $p->sku,
                'price' => '$'.number_format($p->price, 2),
                'materials' => $p->materials,
                'image' => $p->primaryImage->image_path ?? null,
                'url' => route('product.show', $p->slug),
            ]);

        return response()->json(['results' => $results]);
    }

    /**
     * Display the profile of Jennifer Vaughn, Founder & CEO of The Vaughn Atelier.
     */
    public function founder(): View
    {
        $signaturePieces = Product::with(['category', 'primaryImage'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(3)
            ->get();

        return view('storefront.founder', compact('signaturePieces'));
    }

    /**
     * Apply filtering and sorting constraints to product queries.
     */
    private function applyCatalogFilters(Request $request, $query): void
    {
        // Metal color filter
        if ($request->filled('metal')) {
            $query->where('metal_color', $request->metal);
        }

        // Gemstone filter
        if ($request->filled('gemstone')) {
            $query->where('gemstone', 'like', "%{$request->gemstone}%");
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Availability filter
        if ($request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sorting
        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'newest' => $query->latest(),
                'bestsellers' => $query->orderBy('is_bestseller', 'desc')->orderBy('price', 'desc'),
                default => $query->orderBy('is_featured', 'desc')->orderBy('id', 'asc'),
            };
        } else {
            $query->orderBy('is_featured', 'desc')->orderBy('id', 'asc');
        }
    }
}
