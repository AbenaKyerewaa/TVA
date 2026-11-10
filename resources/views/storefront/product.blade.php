<x-layouts.storefront>
    <x-slot:title>
        {{ $product->name }} — The Vaughn Atelier
    </x-slot:title>
    <x-slot:metaDescription>
        {{ strip_tags($product->short_description ?? Str::limit($product->description, 160)) }}
    </x-slot:metaDescription>
    <x-slot:ogImage>
        {{ $product->primaryImage?->image_url ?? asset('images/products/aurelia-solitaire-diamond-ring.jpg') }}
    </x-slot:ogImage>
    <x-slot:ogType>product</x-slot:ogType>

    @push('schema')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ addslashes($product->name) }}",
        "image": [
            "{{ $product->primaryImage?->image_url ?? asset('images/products/aurelia-solitaire-diamond-ring.jpg') }}"
        ],
        "description": "{{ addslashes(strip_tags($product->short_description ?? Str::limit($product->description, 160))) }}",
        "sku": "{{ $product->sku }}",
        "brand": {
            "@type": "Brand",
            "name": "The Vaughn Atelier"
        },
        "offers": {
            "@type": "Offer",
            "url": "{{ url()->current() }}",
            "priceCurrency": "USD",
            "price": "{{ $product->price }}",
            "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "itemCondition": "https://schema.org/NewCondition"
        }
    }
    </script>
    @endpush

    <div class="bg-white">
        <!-- Breadcrumbs -->
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 border-b border-stone-100 text-xs font-sans uppercase tracking-ultra text-stone-500">
            <a href="{{ route('home') }}" class="hover:text-gold-600 transition-colors">Home</a>
            <span class="mx-2 text-stone-300">/</span>
            <a href="{{ route('catalog') }}" class="hover:text-gold-600 transition-colors">Vault</a>
            <span class="mx-2 text-stone-300">/</span>
            <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-gold-600 transition-colors">{{ $product->category->name }}</a>
            <span class="mx-2 text-stone-300">/</span>
            <span class="text-stone-900 font-medium">{{ $product->name }}</span>
        </nav>

        <!-- Product Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            @if(session('status'))
                <div class="mb-8 p-4 bg-stone-900 text-gold-300 text-xs font-sans uppercase tracking-wider flex items-center justify-between border-l-4 border-gold-500">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('cart.index') }}" class="underline hover:text-white">View Shopping Bag &rarr;</a>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-sans">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                
                <!-- Left: Image Gallery (7 Cols) -->
                <div class="lg:col-span-7 space-y-4" x-data="{ activeImage: '{{ $product->primaryImage?->image_url ?? $product->primaryImage?->image_path ?? '' }}' }">
                    <!-- Main Large Image -->
                    <div class="relative aspect-square w-full overflow-hidden bg-ivory-100 border border-stone-200">
                        <img :src="activeImage" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover object-center transition-all duration-500">
                        
                        @if($product->is_new_arrival)
                            <div class="absolute top-4 left-4">
                                <x-luxury.badge variant="dark">New Studio Release</x-luxury.badge>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails Carousel/Grid -->
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                            @foreach($product->images as $img)
                                <button type="button" 
                                        @click="activeImage = '{{ $img->image_url ?? $img->image_path }}'" 
                                        class="aspect-square bg-ivory-50 border overflow-hidden transition-all duration-200 focus:outline-none"
                                        :class="activeImage === '{{ $img->image_url ?? $img->image_path }}' ? 'border-gold-600 ring-1 ring-gold-600' : 'border-stone-200 hover:border-stone-400'">
                                    <img src="{{ $img->image_url ?? $img->image_path }}" alt="Thumbnail" class="w-full h-full object-cover object-center">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right: Product Information & Purchase (5 Cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold block">
                            {{ $product->category->name }} &bull; {{ $product->materials }}
                        </span>
                        <h1 class="mt-2 font-serif text-3xl sm:text-4xl text-stone-900 font-light leading-tight">
                            {{ $product->name }}
                        </h1>
                        <p class="mt-1 text-xs text-stone-400 font-mono tracking-wider">
                            SKU: {{ $product->sku }}
                        </p>
                    </div>

                    <!-- Price -->
                    <div class="flex items-baseline space-x-3 pb-4 border-b border-stone-100">
                        <span class="font-serif text-3xl text-stone-900 font-medium">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        @if($product->compare_at_price && $product->compare_at_price > $product->price)
                            <span class="font-serif text-lg text-stone-400 line-through">
                                ${{ number_format($product->compare_at_price, 2) }}
                            </span>
                            <x-luxury.badge variant="sale">Save {{ $product->discount_percentage }}%</x-luxury.badge>
                        @endif
                    </div>

                    <!-- Short Description -->
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-light">
                        {{ $product->short_description ?? $product->description }}
                    </p>

                    <!-- Interactive Purchase / Variants Area -->
                    <div x-data="{ 
                        selectedVariant: '{{ $product->variants->first()?->id ?? '' }}', 
                        quantity: 1 
                    }" class="space-y-6">

                        <!-- Variants (Sizes or Options) -->
                        @if($product->variants->isNotEmpty())
                            <div class="space-y-3 pt-2">
                                <div class="flex justify-between text-xs uppercase font-sans tracking-ultra">
                                    <span class="font-medium text-stone-900">Select Edition / Size:</span>
                                    <a href="#sizing" class="text-gold-700 hover:underline">Sizing Guide</a>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->variants as $variant)
                                        <button type="button" 
                                                @click="selectedVariant = '{{ $variant->id }}'" 
                                                class="px-4 py-2 text-xs uppercase tracking-wider font-sans border transition-all focus:outline-none"
                                                :class="selectedVariant === '{{ $variant->id }}' ? 'bg-stone-950 text-white border-stone-950 shadow-sm' : 'bg-white text-stone-700 border-stone-200 hover:border-gold-500'">
                                            {{ $variant->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Stock Status -->
                        <div class="flex items-center space-x-2 text-xs">
                            @if($product->stock_quantity > 0)
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-emerald-800 font-medium font-sans">
                                    In Vault ({{ $product->stock_quantity }} pieces remaining) &mdash; Ready for dispatch
                                </span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span class="text-rose-700 font-medium font-sans">
                                    Currently in Studio Production &mdash; Bespoke Reserve Available
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-2 flex flex-col sm:flex-row gap-3">
                            <form method="POST" action="{{ route('cart.store') }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="product_variant_id" :value="selectedVariant">
                                <input type="hidden" name="quantity" :value="quantity">
                                
                                <button type="submit" 
                                        {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}
                                        class="w-full py-4 px-6 bg-stone-950 text-gold-300 hover:bg-black hover:text-white transition-all text-xs uppercase tracking-ultra font-semibold flex items-center justify-center space-x-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span>Add To Shopping Bag</span>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('wishlist.toggle') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" 
                                        class="w-full sm:w-auto py-4 px-6 border border-stone-300 text-stone-800 hover:border-stone-900 transition-all text-xs uppercase tracking-ultra font-semibold flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span>Wishlist</span>
                                </button>
                            </form>
                        </div>

                        <!-- Social Sharing & Heirloom Curation -->
                        <div class="pt-4 flex items-center justify-between border-t border-stone-100 text-[11px] font-sans uppercase tracking-wider text-stone-500">
                            <span class="font-medium text-stone-700">Share Atelier Piece:</span>
                            <div class="flex items-center space-x-3">
                                <a href="https://wa.me/?text={{ urlencode('Admire this exquisite high-jewelry creation from The Vaughn Atelier: ' . $product->name . ' — ' . url()->current()) }}" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="hover:text-gold-600 transition-colors" title="Share via WhatsApp">
                                    WhatsApp
                                </a>
                                <span class="text-stone-300">&bull;</span>
                                <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ urlencode($product->primaryImage?->image_url ?? asset('images/products/aurelia-solitaire-diamond-ring.jpg')) }}&description={{ urlencode($product->name . ' — The Vaughn Atelier') }}" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="hover:text-gold-600 transition-colors" title="Pin on Pinterest">
                                    Pinterest
                                </a>
                                <span class="text-stone-300">&bull;</span>
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($product->name . ' — The Vaughn Atelier') }}&url={{ urlencode(url()->current()) }}" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="hover:text-gold-600 transition-colors" title="Share on X">
                                    X (Twitter)
                                </a>
                                <span class="text-stone-300">&bull;</span>
                                <button type="button" 
                                        onclick="navigator.clipboard.writeText(window.location.href); alert('Atelier link copied to clipboard.');"
                                        class="hover:text-gold-600 transition-colors" title="Copy Link">
                                    Copy Link
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Accordion / Guarantees -->
                    <div class="pt-6 border-t border-stone-200 divide-y divide-stone-100 text-xs text-stone-600">
                        <div class="py-3 flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gold-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div>
                                <span class="font-medium text-stone-900 uppercase tracking-ultra block">Certificate of Authenticity</span>
                                <span>Includes official gemological certification confirming carat weight, clarity, cut, and 18K assay hallmark.</span>
                            </div>
                        </div>

                        <div class="py-3 flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gold-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <div>
                                <span class="font-medium text-stone-900 uppercase tracking-ultra block">Complimentary Armored Courier</span>
                                <span>Dispatched in unmarked security packaging with signature verification upon delivery.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Specifications Table -->
            @if($product->attributes->isNotEmpty())
                <div class="mt-20 pt-12 border-t border-stone-200">
                    <div class="max-w-3xl mx-auto">
                        <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold block text-center">Artisanal Specifications</span>
                        <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 text-center font-light mt-1 mb-8">Creation Details</h2>

                        <div class="border border-stone-200 divide-y divide-stone-200 bg-white">
                            <div class="grid grid-cols-2 p-3 text-xs">
                                <span class="text-stone-500 uppercase tracking-wider font-sans">Precious Metal</span>
                                <span class="text-stone-900 font-medium">{{ $product->materials }}</span>
                            </div>
                            @if($product->gemstone)
                                <div class="grid grid-cols-2 p-3 text-xs bg-stone-50/50">
                                    <span class="text-stone-500 uppercase tracking-wider font-sans">Primary Gemstone</span>
                                    <span class="text-stone-900 font-medium">{{ $product->gemstone }}</span>
                                </div>
                            @endif
                            @if($product->weight_grams)
                                <div class="grid grid-cols-2 p-3 text-xs">
                                    <span class="text-stone-500 uppercase tracking-wider font-sans">Approximate Weight</span>
                                    <span class="text-stone-900 font-medium">{{ $product->weight_grams }} grams</span>
                                </div>
                            @endif
                            @foreach($product->attributes as $attr)
                                <div class="grid grid-cols-2 p-3 text-xs {{ $loop->even ? 'bg-stone-50/50' : '' }}">
                                    <span class="text-stone-500 uppercase tracking-wider font-sans">{{ $attr->attribute_name }}</span>
                                    <span class="text-stone-900 font-medium">{{ $attr->attribute_value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Verified Client Reviews -->
            @if($product->reviews->isNotEmpty())
                <div class="mt-20 pt-12 border-t border-stone-200">
                    <div class="max-w-3xl mx-auto">
                        <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold block text-center">Client Experiences</span>
                        <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 text-center font-light mt-1 mb-8">Verified Patron Reviews</h2>

                        <div class="space-y-6">
                            @foreach($product->reviews as $review)
                                <div class="p-6 bg-ivory-50 border border-stone-200 rounded-sm space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex text-amber-500">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-stone-400 font-sans">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($review->title)
                                        <h4 class="font-serif text-base text-stone-900 font-medium">{{ $review->title }}</h4>
                                    @endif
                                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-light">{{ $review->comment }}</p>
                                    <span class="text-xs uppercase tracking-wider text-stone-500 block font-medium">&mdash; {{ $review->user->name }} (Verified Collector)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Related Products -->
            @if($relatedProducts->isNotEmpty())
                <div class="mt-20 pt-12 border-t border-stone-200">
                    <div class="text-center mb-10">
                        <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold block">Complementary Heirlooms</span>
                        <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 font-light mt-1">You May Also Admire</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($relatedProducts as $related)
                            <x-luxury.product-card :product="$related" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.storefront>

