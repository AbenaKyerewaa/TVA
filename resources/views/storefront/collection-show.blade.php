<x-storefront-layout>
    <x-slot:title>
        {{ $category->name }} Collection — The Vaughn Atelier
    </x-slot:title>

    <!-- Collection Hero Banner -->
    <div class="relative bg-noir-950 text-white overflow-hidden py-16 sm:py-24 border-b border-stone-800">
        <div class="absolute inset-0 z-0">
            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover opacity-25 scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-950 via-noir-950/80 to-noir-950/40"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
            <div class="inline-flex items-center space-x-2">
                <a href="{{ route('collections') }}" class="text-xs uppercase tracking-ultra text-gold-400 hover:underline">
                    &larr; All Collections
                </a>
                <span class="text-stone-500">&bull;</span>
                <span class="text-xs uppercase tracking-ultra text-stone-300">{{ $category->products_count }} Available Creations</span>
            </div>
            <h1 class="font-serif text-3xl sm:text-5xl font-light text-white">{{ $category->name }}</h1>
            <p class="text-xs sm:text-sm text-stone-300 max-w-2xl mx-auto font-light leading-relaxed">
                {{ $category->description }}
            </p>

            @if($category->slug === 'engagement')
                <div class="pt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('collection.show', 'engagement') }}" 
                       class="px-4 py-2 text-xs uppercase font-sans tracking-ultra font-semibold transition-all rounded-xs {{ !request('segment') ? 'bg-gold-500 text-noir-950 shadow-sm' : 'bg-stone-900/80 text-stone-300 hover:text-white border border-stone-700' }}">
                        All Bridal Creations
                    </a>
                    <a href="{{ route('collection.show', ['slug' => 'engagement', 'segment' => 'gowns']) }}" 
                       class="px-4 py-2 text-xs uppercase font-sans tracking-ultra font-semibold transition-all rounded-xs {{ request('segment') === 'gowns' ? 'bg-gold-500 text-noir-950 shadow-sm' : 'bg-stone-900/80 text-stone-300 hover:text-white border border-stone-700' }}">
                        Ladies' Bridal Gowns (4)
                    </a>
                    <a href="{{ route('collection.show', ['slug' => 'engagement', 'segment' => 'suits']) }}" 
                       class="px-4 py-2 text-xs uppercase font-sans tracking-ultra font-semibold transition-all rounded-xs {{ request('segment') === 'suits' ? 'bg-gold-500 text-noir-950 shadow-sm' : 'bg-stone-900/80 text-stone-300 hover:text-white border border-stone-700' }}">
                        Men's Bridal Suits (5)
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Filter & Sort Bar -->
    <div class="bg-white border-b border-stone-200 sticky top-20 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-xs font-sans uppercase tracking-ultra text-stone-600 font-medium">
                Viewing {{ $products->total() }} pieces in {{ $category->name }}
            </div>

            <!-- Sort Dropdown Form -->
            <form method="GET" action="{{ route('collection.show', $category->slug) }}" class="flex items-center space-x-3 w-full md:w-auto justify-end">
                <label for="sort" class="text-xs uppercase tracking-ultra text-stone-500 font-medium">Sort:</label>
                <select name="sort" 
                        id="sort" 
                        onchange="this.form.submit()"
                        class="text-xs uppercase font-sans tracking-wider border-stone-200 bg-stone-50 text-stone-900 rounded py-1.5 pl-3 pr-8 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>Featured Creations</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Releases</option>
                    <option value="bestsellers" {{ request('sort') === 'bestsellers' ? 'selected' : '' }}>Bestsellers</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($products->isEmpty())
            <div class="text-center py-20 space-y-4">
                <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">Studio Notice</span>
                <h3 class="font-serif text-2xl text-stone-900">No creations currently in this collection</h3>
                <p class="text-xs text-stone-500 max-w-sm mx-auto">
                    New pieces are currently being cast in our atelier. Please consult our concierge for bespoke commissions.
                </p>
                <div class="pt-2">
                    <x-luxury.button variant="primary" size="md" :href="route('catalog')">
                        View Complete Vault
                    </x-luxury.button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <x-luxury.product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-16">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Other Collections Navigation -->
    <div class="bg-stone-50 border-t border-stone-200 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            <div>
                <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">Alternative Portfolios</span>
                <h3 class="font-serif text-2xl sm:text-3xl text-stone-900 font-light mt-1">Explore Other Collections</h3>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($allCategories as $otherCat)
                    @if($otherCat->id !== $category->id)
                        <a href="{{ route('collection.show', $otherCat->slug) }}" 
                           class="px-5 py-2.5 bg-white border border-stone-200 hover:border-gold-500 text-xs uppercase font-sans tracking-ultra text-stone-800 rounded-full transition-colors shadow-2xs">
                            {{ $otherCat->name }} ({{ $otherCat->products_count }})
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-storefront-layout>

