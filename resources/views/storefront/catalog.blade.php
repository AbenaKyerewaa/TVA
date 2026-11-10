<x-storefront-layout>
    <x-slot:title>
        High Jewelry Vault — The Vaughn Atelier
    </x-slot:title>

    <!-- Header Banner -->
    <div class="bg-noir-950 text-white py-14 border-b border-stone-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-2">
            @if(request('category') === 'engagement')
                <span class="text-xs uppercase tracking-prestige text-gold-400 font-semibold">The Vaughn Atelier &bull; Haute Couture Bridal & Suiting</span>
                <h1 class="font-serif text-3xl sm:text-5xl font-light text-white">Bridal & Bespoke Heirlooms</h1>
                <p class="text-xs sm:text-sm text-stone-300 max-w-2xl mx-auto font-light leading-relaxed">
                    Haute couture ladies' bridal gowns and bespoke men's wedding suiting, each master-crafted and accessorized with certified sovereign high jewelry.
                </p>

                <!-- Bridal Segment Tabs -->
                <div class="pt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('catalog', ['category' => 'engagement']) }}" 
                       class="px-4 py-2 text-xs uppercase font-sans tracking-ultra font-semibold transition-all rounded-xs {{ !request('segment') ? 'bg-gold-500 text-noir-950 shadow-sm' : 'bg-stone-900 text-stone-300 hover:text-white border border-stone-700' }}">
                        All Bridal Creations
                    </a>
                    <a href="{{ route('catalog', ['category' => 'engagement', 'segment' => 'gowns']) }}" 
                       class="px-4 py-2 text-xs uppercase font-sans tracking-ultra font-semibold transition-all rounded-xs {{ request('segment') === 'gowns' ? 'bg-gold-500 text-noir-950 shadow-sm' : 'bg-stone-900 text-stone-300 hover:text-white border border-stone-700' }}">
                        Ladies' Bridal Gowns (4)
                    </a>
                    <a href="{{ route('catalog', ['category' => 'engagement', 'segment' => 'suits']) }}" 
                       class="px-4 py-2 text-xs uppercase font-sans tracking-ultra font-semibold transition-all rounded-xs {{ request('segment') === 'suits' ? 'bg-gold-500 text-noir-950 shadow-sm' : 'bg-stone-900 text-stone-300 hover:text-white border border-stone-700' }}">
                        Men's Bridal Suits (5)
                    </a>
                </div>
            @else
                <span class="text-xs uppercase tracking-prestige text-gold-400 font-semibold">The Vaughn Atelier</span>
                <h1 class="font-serif text-3xl sm:text-5xl font-light text-white">The High Jewelry Vault</h1>
                <p class="text-xs sm:text-sm text-stone-400 max-w-lg mx-auto font-light">
                    Handcrafted solid gold, certified solitaire diamonds, and bespoke jewelry masterworks.
                </p>
            @endif
        </div>
    </div>

    <!-- Active Filter Chips Bar (if filters are applied) -->
    @if(request('q') || request('category') || request('segment') || request('metal') || request('min_price') || request('max_price') || request('in_stock'))
        <div class="bg-ivory-100 border-b border-stone-200 py-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center gap-2 text-xs">
                <span class="text-stone-500 uppercase tracking-wider font-sans font-medium mr-2">Active Filters:</span>
                
                @if(request('q'))
                    <span class="inline-flex items-center px-3 py-1 bg-white border border-stone-300 rounded-full text-stone-800">
                        Search: "{{ request('q') }}"
                    </span>
                @endif

                @if(request('category'))
                    <span class="inline-flex items-center px-3 py-1 bg-white border border-stone-300 rounded-full text-stone-800">
                        Collection: {{ ucfirst(request('category')) }}
                    </span>
                @endif

                @if(request('segment'))
                    <span class="inline-flex items-center px-3 py-1 bg-white border border-stone-300 rounded-full text-stone-800">
                        Segment: {{ request('segment') === 'gowns' ? "Ladies' Bridal Gowns" : "Men's Bridal Suits" }}
                    </span>
                @endif

                @if(request('metal'))
                    <span class="inline-flex items-center px-3 py-1 bg-white border border-stone-300 rounded-full text-stone-800">
                        Metal: {{ request('metal') }}
                    </span>
                @endif

                @if(request('min_price') || request('max_price'))
                    <span class="inline-flex items-center px-3 py-1 bg-white border border-stone-300 rounded-full text-stone-800">
                        Price: ${{ request('min_price', '0') }} &ndash; ${{ request('max_price', '&infin;') }}
                    </span>
                @endif

                @if(request('in_stock'))
                    <span class="inline-flex items-center px-3 py-1 bg-white border border-stone-300 rounded-full text-stone-800">
                        Vault In-Stock Only
                    </span>
                @endif

                <a href="{{ route('catalog') }}" class="text-rose-600 hover:text-rose-800 uppercase tracking-wider font-semibold ml-2 text-[11px]">
                    Clear All Filters
                </a>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 items-start">
            
            <!-- Left Filter Sidebar (1 Col) -->
            <div class="lg:col-span-1 bg-white border border-stone-200 p-6 rounded-sm space-y-6 shadow-2xs">
                <div class="flex items-center justify-between pb-4 border-b border-stone-200">
                    <h3 class="font-serif text-lg text-stone-900 font-medium">Refine Vault</h3>
                    @if(request()->anyFilled(['q', 'category', 'metal', 'min_price', 'max_price', 'in_stock']))
                        <a href="{{ route('catalog') }}" class="text-xs text-stone-500 hover:text-gold-700 underline">Reset</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('catalog') }}" class="space-y-6">
                    <!-- Preserve Sorting -->
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <!-- Search Input -->
                    <div>
                        <label for="filter-q" class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 block mb-2">
                            Search Keywords / SKU
                        </label>
                        <input type="text" 
                               id="filter-q" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="e.g. Solitaire, 18K, Vault..."
                               class="w-full text-xs font-sans border-stone-200 bg-stone-50 rounded px-3 py-2 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                    </div>

                    <!-- Collections Filter -->
                    <div class="pt-4 border-t border-stone-100">
                        <label for="filter-category" class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 block mb-2">
                            Jewelry Collection
                        </label>
                        <select id="filter-category" 
                                name="category" 
                                class="w-full text-xs font-sans border-stone-200 bg-stone-50 rounded px-3 py-2 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                            <option value="">All Collections</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Metal Color Filter -->
                    @if(isset($availableMetals) && $availableMetals->isNotEmpty())
                        <div class="pt-4 border-t border-stone-100">
                            <label for="filter-metal" class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 block mb-2">
                                Precious Metal
                            </label>
                            <select id="filter-metal" 
                                    name="metal" 
                                    class="w-full text-xs font-sans border-stone-200 bg-stone-50 rounded px-3 py-2 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                                <option value="">All Precious Metals</option>
                                @foreach($availableMetals as $metal)
                                    <option value="{{ $metal }}" {{ request('metal') === $metal ? 'selected' : '' }}>
                                        {{ $metal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Price Range -->
                    <div class="pt-4 border-t border-stone-100">
                        <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 block mb-2">
                            Price Range ($ USD)
                        </span>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" 
                                   name="min_price" 
                                   value="{{ request('min_price') }}" 
                                   placeholder="Min $"
                                   class="text-xs font-sans border-stone-200 bg-stone-50 rounded px-3 py-2 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                            <input type="number" 
                                   name="max_price" 
                                   value="{{ request('max_price') }}" 
                                   placeholder="Max $"
                                   class="text-xs font-sans border-stone-200 bg-stone-50 rounded px-3 py-2 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                        </div>
                    </div>

                    <!-- In-Stock Only Toggle -->
                    <div class="pt-4 border-t border-stone-100 flex items-center space-x-2">
                        <input type="checkbox" 
                               id="in_stock" 
                               name="in_stock" 
                               value="1" 
                               {{ request('in_stock') ? 'checked' : '' }}
                               class="rounded border-stone-300 text-gold-600 focus:ring-gold-500">
                        <label for="in_stock" class="text-xs text-stone-800 font-medium">Vault In-Stock Only</label>
                    </div>

                    <!-- Filter Action Button -->
                    <div class="pt-2">
                        <x-luxury.button variant="primary" size="sm" type="submit" class="w-full">
                            Apply Filter
                        </x-luxury.button>
                    </div>
                </form>
            </div>

            <!-- Right Content Area: Results Header & Grid (3 Cols) -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Sorting & Count Header -->
                <div class="flex flex-col sm:flex-row items-center justify-between pb-4 border-b border-stone-200 gap-4">
                    <span class="text-xs uppercase font-sans tracking-ultra text-stone-600">
                        Displaying <strong class="text-stone-900">{{ $products->total() }}</strong> Fine Jewelry Creations
                    </span>

                    <form method="GET" action="{{ route('catalog') }}" class="flex items-center space-x-2">
                        @foreach(request()->except(['sort', 'page']) as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $subV)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $subV }}">
                                @endforeach
                            @elseif($v !== null && $v !== '')
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach
                        <label for="catalog-sort" class="text-xs uppercase tracking-ultra text-stone-500 font-medium">Sort By:</label>
                        <select name="sort" 
                                id="catalog-sort" 
                                onchange="this.form.submit()"
                                class="text-xs uppercase font-sans tracking-wider border-stone-200 bg-white text-stone-900 rounded py-1.5 pl-3 pr-8 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                            <option value="" {{ !request('sort') ? 'selected' : '' }}>Featured</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Releases</option>
                            <option value="bestsellers" {{ request('sort') === 'bestsellers' ? 'selected' : '' }}>Bestsellers</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </form>
                </div>

                <!-- Product Grid -->
                @if($products->isEmpty())
                    <div class="bg-white p-16 text-center border border-stone-200 rounded-sm space-y-4">
                        <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">Vault Inquiry</span>
                        <h3 class="font-serif text-2xl text-stone-900">No creations match your search parameters</h3>
                        <p class="text-xs text-stone-500 max-w-sm mx-auto font-light">
                            Try broadening your price or collection criteria, or contact our private atelier concierge for custom commissions.
                        </p>
                        <div class="pt-2">
                            <x-luxury.button variant="outline" size="sm" :href="route('catalog')">
                                Reset Search Filters
                            </x-luxury.button>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($products as $product)
                            <x-luxury.product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-12 pt-6 border-t border-stone-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-storefront-layout>
