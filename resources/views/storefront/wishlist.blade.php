<x-storefront-layout>
    <x-slot:title>
        Your Private Wishlist — The Vaughn Atelier
    </x-slot:title>

    <div class="bg-white min-h-[70vh]">
        <!-- Header Banner -->
        <div class="bg-noir-950 text-white py-12 border-b border-stone-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-2">
                <span class="text-xs uppercase tracking-prestige text-gold-400 font-semibold">Client Portfolio</span>
                <h1 class="font-serif text-3xl sm:text-4xl font-light text-white">Your Saved Heirlooms</h1>
                <p class="text-xs text-stone-400 font-light">Privately curated high jewelry pieces reserved for future acquisition.</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if(session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @if($items->isEmpty())
                <div class="text-center py-20 bg-stone-50 border border-stone-200 rounded-sm space-y-4">
                    <svg class="w-12 h-12 mx-auto text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <h2 class="font-serif text-2xl text-stone-900 font-light">Your private wishlist is empty</h2>
                    <p class="text-xs text-stone-500 max-w-sm mx-auto font-light leading-relaxed">
                        Tap the heart icon on any jewelry piece across our catalog to preserve it in your personal vault.
                    </p>
                    <div class="pt-2">
                        <x-luxury.button variant="primary" size="md" :href="route('catalog')">
                            Explore High Jewelry
                        </x-luxury.button>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($items as $wishItem)
                        @php
                            $product = $wishItem->product;
                        @endphp
                        <div class="border border-stone-200 p-4 bg-white flex flex-col justify-between group shadow-2xs">
                            <div>
                                <div class="aspect-square bg-ivory-100 overflow-hidden relative border border-stone-100">
                                    <img src="{{ $product->primaryImage?->image_url ?? $product->primaryImage?->image_path ?? asset('images/products/aurelia-solitaire-diamond-ring.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div class="mt-4 text-center">
                                    <span class="text-[10px] uppercase tracking-ultra text-gold-700 font-semibold block">
                                        {{ $product->category->name }}
                                    </span>
                                    <h3 class="font-serif text-base text-stone-900 mt-1">
                                        <a href="{{ route('product.show', $product->slug) }}" class="hover:text-gold-700">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="font-serif text-sm font-medium text-stone-900 mt-1">
                                        ${{ number_format($product->price, 2) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-stone-100 space-y-2">
                                <form method="POST" action="{{ route('wishlist.move_to_cart', $wishItem->id) }}">
                                    @csrf
                                    <x-luxury.button variant="gold" size="sm" type="submit" class="w-full text-center">
                                        Move To Shopping Bag
                                    </x-luxury.button>
                                </form>
                                <form method="POST" action="{{ route('wishlist.destroy', $wishItem->id) }}" class="text-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] uppercase tracking-wider text-rose-600 hover:text-rose-800 font-medium">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-storefront-layout>

