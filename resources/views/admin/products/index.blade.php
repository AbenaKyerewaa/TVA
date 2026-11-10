<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs tracking-widest uppercase font-semibold text-amber-600">The Vaughn Atelier Administration</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Vault Inventory Management</h2>
            </div>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-stone-900 text-white text-xs uppercase font-sans tracking-ultra hover:bg-stone-800 transition-colors">
                + Create Jewelry Piece
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Search and Filter Bar -->
            <div class="bg-white p-4 border border-stone-200 rounded-sm shadow-2xs">
                <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex flex-1 items-center space-x-3 w-full sm:w-auto">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by title, SKU, gemstone..." 
                               class="text-xs font-sans border-stone-200 bg-stone-50 rounded px-3 py-2 flex-1 focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                        
                        <select name="category_id" class="text-xs font-sans border-stone-200 bg-stone-50 rounded px-3 py-2">
                            <option value="">All Collections</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2 text-xs text-stone-700">
                            <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span>Low Vault Stock Only</span>
                        </label>
                        <x-primary-button class="py-2 text-xs">
                            Filter
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Products Table -->
            <div class="bg-white border border-stone-200 rounded-sm shadow-xs overflow-hidden">
                <table class="min-w-full divide-y divide-stone-200 text-left text-xs font-sans">
                    <thead class="bg-stone-100 uppercase tracking-ultra font-semibold text-stone-700">
                        <tr>
                            <th class="px-6 py-3">Creation</th>
                            <th class="px-6 py-3">SKU</th>
                            <th class="px-6 py-3">Collection</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Vault Stock</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-stone-50">
                                <td class="px-6 py-4 flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded bg-stone-100 overflow-hidden flex-shrink-0">
                                        @if($product->primaryImage)
                                            <img src="{{ $product->primaryImage->image_path }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[10px] text-stone-400">TVA</div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="font-medium text-stone-900 hover:text-amber-700">
                                            {{ $product->name }}
                                        </a>
                                        <p class="text-[11px] text-stone-500">{{ $product->materials ?? 'Precious Alloy' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-stone-600">{{ $product->sku }}</td>
                                <td class="px-6 py-4 text-stone-800">{{ $product->category->name }}</td>
                                <td class="px-6 py-4 font-serif font-medium text-stone-900">${{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($product->stock_quantity <= 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-100 text-rose-800 font-semibold">Out of Stock</span>
                                    @elseif($product->isLowStock())
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-800 font-semibold">{{ $product->stock_quantity }} low stock</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-semibold">{{ $product->stock_quantity }} in vault</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($product->is_active)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-semibold">Live</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-stone-100 text-stone-500 font-semibold">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-amber-700 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-block" onsubmit="return confirm('Remove this piece from the vault?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-stone-400">No jewelry creations found matching criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

