@props(['product'])

<div class="group relative flex flex-col bg-white">
    <!-- Image Container with Hover Zoom -->
    <div class="relative aspect-square w-full overflow-hidden bg-ivory-100 border border-stone-100">
        @php
            $primary = $product->primaryImage?->image_url ?? $product->primaryImage?->image_path ?? asset('images/products/aurelia-solitaire-diamond-ring.jpg');
            $secondary = $product->images->skip(1)->first()?->image_url ?? $product->images->skip(1)->first()?->image_path ?? null;
        @endphp

        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $primary }}" 
                 alt="{{ $product->name }}" 
                 class="h-full w-full object-cover object-center transition-all duration-700 ease-out group-hover:scale-105 {{ $secondary ? 'group-hover:opacity-0' : '' }}">
            
            @if($secondary)
                <img src="{{ $secondary }}" 
                     alt="{{ $product->name }}" 
                     class="absolute inset-0 h-full w-full object-cover object-center opacity-0 transition-opacity duration-700 ease-out group-hover:opacity-100 group-hover:scale-105">
            @endif
        </a>

        <!-- Badges Overlay -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 pointer-events-none">
            @if($product->is_new_arrival)
                <x-luxury.badge variant="dark">New Arrival</x-luxury.badge>
            @elseif($product->is_bestseller)
                <x-luxury.badge variant="gold">Bestseller</x-luxury.badge>
            @endif

            @if($product->discount_percentage)
                <x-luxury.badge variant="sale">-{{ $product->discount_percentage }}%</x-luxury.badge>
            @endif
        </div>

        <!-- Wishlist Button Overlay -->
        <form method="POST" action="{{ route('wishlist.toggle') }}" class="absolute top-3 right-3 z-10">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" 
                    class="p-2 rounded-full bg-white/80 backdrop-blur-sm text-stone-700 hover:text-rose-600 transition-colors duration-200 shadow-sm focus:outline-none"
                    aria-label="Save to Wishlist">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </form>

        <!-- Quick View / Details Floating Bar -->
        <div class="absolute inset-x-3 bottom-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
            <a href="{{ route('product.show', $product->slug) }}" 
               class="block w-full py-2.5 text-center text-xs uppercase font-sans font-medium tracking-ultra bg-white/95 backdrop-blur-md text-noir-900 border border-stone-200 hover:bg-noir-950 hover:text-white transition-colors duration-300 shadow-sm">
                View Creation
            </a>
        </div>
    </div>

    <!-- Product Info -->
    <div class="pt-4 pb-2 flex flex-col flex-grow text-center">
        <span class="text-[11px] uppercase tracking-ultra font-sans text-gold-700 font-medium">
            {{ $product->materials ?? $product->category->name }}
        </span>

        <h3 class="mt-1 font-serif text-base text-stone-900 group-hover:text-gold-700 transition-colors duration-200 line-clamp-1">
            <a href="{{ route('product.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        <div class="mt-2 flex items-center justify-center space-x-2">
            <span class="font-serif text-sm font-medium text-stone-900">
                ${{ number_format($product->price, 2) }}
            </span>
            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                <span class="font-serif text-xs text-stone-400 line-through">
                    ${{ number_format($product->compare_at_price, 2) }}
                </span>
            @endif
        </div>
    </div>
</div>

