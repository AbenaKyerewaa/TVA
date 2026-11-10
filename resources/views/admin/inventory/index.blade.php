<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-700 font-semibold">The Vaughn Atelier &bull; Atelier Admin</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Private Vault Inventory Control</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.inventory.logs') }}" 
                   class="px-4 py-2 bg-stone-900 text-white hover:bg-black rounded text-xs uppercase tracking-wider font-semibold shadow-sm transition-colors">
                    View Stock Audit Logs &rarr;
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-md flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Inventory Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Total Vault Pieces</span>
                    <p class="text-2xl font-serif text-stone-900 mt-1 font-bold">{{ number_format($metrics['total_pieces']) }}</p>
                    <span class="text-[11px] text-stone-400">Total units across all collections</span>
                </div>
                <div class="bg-white p-5 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-amber-700 font-medium">Low Vault Stock (&le; 3)</span>
                    <p class="text-2xl font-serif text-amber-800 mt-1 font-bold">{{ number_format($metrics['low_stock_count']) }}</p>
                    <span class="text-[11px] text-stone-400">Pieces nearing depletion</span>
                </div>
                <div class="bg-white p-5 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-rose-700 font-medium">Depleted / Out of Stock</span>
                    <p class="text-2xl font-serif text-rose-800 mt-1 font-bold">{{ number_format($metrics['out_of_stock_count']) }}</p>
                    <span class="text-[11px] text-stone-400">Requires atelier commission</span>
                </div>
                <div class="bg-white p-5 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Active Jewelry SKUs</span>
                    <p class="text-2xl font-serif text-stone-900 mt-1 font-bold">{{ number_format($metrics['total_skus']) }}</p>
                    <span class="text-[11px] text-stone-400">Unique catalog models</span>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white p-4 border border-stone-200 rounded-lg shadow-2xs flex flex-wrap gap-3 items-center justify-between">
                <div class="flex flex-wrap gap-2 text-xs">
                    <a href="{{ route('admin.inventory.index') }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ !request('filter') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        All Pieces
                    </a>
                    <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('filter') === 'low_stock' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Low Stock (&le; 3)
                    </a>
                    <a href="{{ route('admin.inventory.index', ['filter' => 'out_of_stock']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('filter') === 'out_of_stock' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Out of Stock
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex items-center space-x-2">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Piece name, SKU..." class="text-xs border-stone-200 rounded px-3 py-1.5 focus:border-amber-500 focus:ring-0">
                    <button type="submit" class="px-3 py-1.5 bg-stone-800 text-white text-xs uppercase tracking-wider rounded hover:bg-stone-700">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.inventory.index') }}" class="text-xs text-rose-600 hover:underline">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Inventory Pieces Table -->
            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs divide-y divide-stone-200">
                        <thead class="bg-stone-50 font-semibold text-stone-700 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-6 py-3.5">Creation</th>
                                <th class="px-6 py-3.5">SKU</th>
                                <th class="px-6 py-3.5">Collection</th>
                                <th class="px-6 py-3.5">Unit Price</th>
                                <th class="px-6 py-3.5 text-center">Remaining Stock</th>
                                <th class="px-6 py-3.5">Stock Status</th>
                                <th class="px-6 py-3.5 text-right">Quick Stock Adjustment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-stone-50/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded bg-stone-100 border border-stone-200 overflow-hidden flex-shrink-0">
                                                @if($product->primaryImage)
                                                    <img src="{{ $product->primaryImage->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center font-serif text-amber-700 text-xs">TVA</div>
                                                @endif
                                            </div>
                                            <span class="font-medium text-stone-900 block truncate max-w-xs">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-stone-500 text-[11px]">
                                        {{ $product->sku }}
                                    </td>
                                    <td class="px-6 py-4 text-stone-600">
                                        {{ $product->category->name ?? 'Vault' }}
                                    </td>
                                    <td class="px-6 py-4 font-serif font-semibold text-stone-900">
                                        ${{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-mono text-sm font-bold {{ $product->stock_quantity <= 0 ? 'text-rose-600' : ($product->stock_quantity <= 3 ? 'text-amber-600' : 'text-stone-900') }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->stock_quantity <= 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-rose-100 text-rose-800">
                                                Out of Stock
                                            </span>
                                        @elseif($product->stock_quantity <= 3)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-amber-100 text-amber-800">
                                                Low Reserve
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                                Sufficient
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <!-- Inline Quick Adjust Form -->
                                        <form method="POST" action="{{ route('admin.inventory.adjust', $product->id) }}" class="inline-flex items-center space-x-1 justify-end">
                                            @csrf
                                            <select name="adjustment_type" class="text-[11px] border-stone-200 rounded py-1 px-2 focus:ring-0">
                                                <option value="add">+ Add</option>
                                                <option value="subtract">&minus; Deduct</option>
                                                <option value="set">= Set</option>
                                            </select>
                                            <input type="number" name="quantity" min="0" placeholder="Qty" required class="w-16 text-center text-xs border-stone-200 rounded py-1 px-1 focus:ring-0">
                                            <input type="hidden" name="notes" value="Atelier inventory reconciliation">
                                            <button type="submit" class="px-2.5 py-1 bg-amber-700 hover:bg-amber-800 text-white rounded text-[10px] uppercase tracking-wider font-semibold">
                                                Apply
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                        No jewelry pieces found matching criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="p-4 border-t border-stone-200">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

