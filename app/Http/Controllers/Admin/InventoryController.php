<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryLog;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Display vault inventory tracking dashboard.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage', 'variants'])->orderBy('stock_quantity', 'asc');

        if ($request->filled('category')) {
            $query->where('category_id', $request->query('category'));
        }

        if ($request->query('filter') === 'low_stock') {
            $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 3);
        } elseif ($request->query('filter') === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        $metrics = [
            'total_pieces' => Product::sum('stock_quantity'),
            'low_stock_count' => Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 3)->count(),
            'out_of_stock_count' => Product::where('stock_quantity', '<=', 0)->count(),
            'total_skus' => Product::count(),
        ];

        return view('admin.inventory.index', compact('products', 'categories', 'metrics'));
    }

    /**
     * Manually adjust vault stock with audit logging.
     */
    public function adjust(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'adjustment_type' => ['required', 'in:add,subtract,set'],
            'quantity' => ['required', 'integer', 'min:0'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($product, $validated) {
            $previousStock = $product->stock_quantity;
            $qty = $validated['quantity'];

            if ($validated['adjustment_type'] === 'add') {
                $currentStock = $previousStock + $qty;
                $changeQty = $qty;
            } elseif ($validated['adjustment_type'] === 'subtract') {
                $currentStock = max(0, $previousStock - $qty);
                $changeQty = -$qty;
            } else {
                $currentStock = $qty;
                $changeQty = $qty - $previousStock;
            }

            $product->update(['stock_quantity' => $currentStock]);

            InventoryLog::create([
                'product_id' => $product->id,
                'change_type' => 'adjustment',
                'quantity' => $changeQty,
                'previous_stock' => $previousStock,
                'current_stock' => $currentStock,
                'reference_type' => 'Administrator',
                'reference_id' => auth()->id(),
                'notes' => $validated['notes'],
            ]);
        });

        return back()->with('status', "Vault inventory updated for {$product->name}.");
    }

    /**
     * Display historical vault stock audit logs.
     */
    public function logs(Request $request): View
    {
        $query = InventoryLog::with(['product.primaryImage', 'variant'])
            ->latest();

        if ($request->filled('change_type')) {
            $query->where('change_type', $request->query('change_type'));
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.inventory.logs', compact('logs'));
    }
}
