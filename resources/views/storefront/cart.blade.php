<x-storefront-layout>
    <x-slot:title>
        Your Shopping Bag — The Vaughn Atelier
    </x-slot:title>

    <div class="bg-white min-h-[70vh]">
        <!-- Header Banner -->
        <div class="bg-noir-950 text-white py-12 border-b border-stone-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-2">
                <span class="text-xs uppercase tracking-prestige text-gold-400 font-semibold">The Vaughn Atelier</span>
                <h1 class="font-serif text-3xl sm:text-4xl font-light text-white">Your Shopping Bag</h1>
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

            @if($cart->items->isEmpty())
                <div class="text-center py-20 bg-stone-50 border border-stone-200 rounded-sm space-y-4">
                    <svg class="w-12 h-12 mx-auto text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h2 class="font-serif text-2xl text-stone-900 font-light">Your shopping bag is currently empty</h2>
                    <p class="text-xs text-stone-500 max-w-sm mx-auto font-light leading-relaxed">
                        Explore our handcrafted 18-karat solid gold rings, diamond pendants, and tennis bracelets in the vault.
                    </p>
                    <div class="pt-2">
                        <x-luxury.button variant="primary" size="md" :href="route('catalog')">
                            Explore High Jewelry
                        </x-luxury.button>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    
                    <!-- Left: Items Table / List (8 Cols) -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="border border-stone-200 rounded-sm divide-y divide-stone-200 bg-white shadow-2xs">
                            <div class="hidden sm:grid grid-cols-12 p-4 bg-stone-50 text-[11px] uppercase tracking-ultra font-semibold text-stone-600">
                                <span class="col-span-6">Jewelry Piece</span>
                                <span class="col-span-2 text-center">Unit Price</span>
                                <span class="col-span-2 text-center">Quantity</span>
                                <span class="col-span-2 text-right">Subtotal</span>
                            </div>

                            @foreach($cart->items as $item)
                                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                                    <!-- Product Info & Image -->
                                    <div class="sm:col-span-6 flex items-center space-x-4">
                                        <div class="w-20 h-20 bg-ivory-100 border border-stone-200 rounded-sm overflow-hidden flex-shrink-0">
                                            @php
                                                $img = $item->product->primaryImage?->image_url ?? $item->product->primaryImage?->image_path ?? asset('images/products/aurelia-solitaire-diamond-ring.jpg');
                                            @endphp
                                            <img src="{{ $img }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover object-center">
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase tracking-ultra text-gold-700 font-semibold">
                                                {{ $item->product->category->name }}
                                            </span>
                                            <h3 class="font-serif text-base text-stone-900 leading-snug">
                                                <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-gold-700">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h3>
                                            @if($item->variant)
                                                <p class="text-xs text-stone-500 font-sans mt-0.5">{{ $item->variant->name }}</p>
                                            @endif
                                            <form method="POST" action="{{ route('cart.destroy', $item->id) }}" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[11px] uppercase tracking-wider text-rose-600 hover:text-rose-800 font-medium">
                                                    Remove Piece
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="sm:col-span-2 sm:text-center text-xs font-serif text-stone-800">
                                        <span class="sm:hidden font-sans text-stone-400 mr-1">Price:</span>
                                        ${{ number_format($item->unit_price, 2) }}
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="sm:col-span-2 flex sm:justify-center items-center space-x-1">
                                        <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center border border-stone-200 rounded-sm">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="px-2 py-1 text-stone-600 hover:bg-stone-100 font-mono text-xs">
                                                &minus;
                                            </button>
                                            <span class="px-3 py-1 text-xs font-sans font-medium text-stone-900 border-x border-stone-200">
                                                {{ $item->quantity }}
                                            </span>
                                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-2 py-1 text-stone-600 hover:bg-stone-100 font-mono text-xs">
                                                &#43;
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Item Subtotal -->
                                    <div class="sm:col-span-2 sm:text-right font-serif text-sm font-semibold text-stone-900">
                                        <span class="sm:hidden font-sans text-stone-400 font-normal mr-1">Total:</span>
                                        ${{ number_format($item->total_price, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Complimentary White-Glove Shipping Note -->
                        <div class="p-4 bg-ivory-50 border border-stone-200 text-xs text-stone-600 flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Each creation is accompanied by an individual Certificate of Authenticity and shipped via discrete armored courier with signature confirmation.</span>
                        </div>
                    </div>

                    <!-- Right: Order Summary Card (4 Cols) -->
                    <div class="lg:col-span-4 bg-white border border-stone-200 p-6 rounded-sm space-y-6 shadow-sm">
                        <h2 class="font-serif text-xl text-stone-900 pb-3 border-b border-stone-100">Vault Order Summary</h2>

                        <!-- Summary Figures -->
                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between text-stone-600">
                                <span>Bag Subtotal</span>
                                <span class="font-serif font-medium text-stone-900">${{ number_format($subtotal, 2) }}</span>
                            </div>

                            @if($appliedCoupon)
                                <div class="flex justify-between text-emerald-800 bg-emerald-50 p-2 rounded-xs border border-emerald-200">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold">{{ $appliedCoupon->code }}</span>
                                        <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 text-[10px]">&times; Remove</button>
                                        </form>
                                    </div>
                                    <span class="font-serif font-medium">&minus;${{ number_format($discountAmount, 2) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-stone-600">
                                <span>Estimated Armored Courier</span>
                                <span class="text-emerald-700 font-medium font-sans uppercase tracking-wider">
                                    {{ $shippingFee == 0 ? 'Complimentary' : '$' . number_format($shippingFee, 2) }}
                                </span>
                            </div>

                            <div class="pt-3 border-t border-stone-200 flex justify-between items-baseline">
                                <span class="font-serif text-base text-stone-900">Total Investment</span>
                                <span class="font-serif text-2xl font-semibold text-stone-900">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Coupon Form -->
                        @if(! $appliedCoupon)
                            <form method="POST" action="{{ route('cart.coupon.apply') }}" class="pt-4 border-t border-stone-100 space-y-2">
                                @csrf
                                <label for="code" class="text-[11px] uppercase tracking-ultra text-stone-500 font-medium block">
                                    Promotional / Invitation Code
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" 
                                           id="code" 
                                           name="code" 
                                           placeholder="e.g. WELCOME10"
                                           class="text-xs uppercase font-sans tracking-wider border-stone-200 bg-stone-50 rounded px-3 py-2 flex-1 focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                                    <x-luxury.button variant="outline" size="sm" type="submit">
                                        Apply
                                    </x-luxury.button>
                                </div>
                            </form>
                        @endif

                        <!-- Checkout CTA -->
                        <div class="pt-2">
                            <x-luxury.button variant="gold" size="lg" :href="route('checkout.index')" class="w-full text-center">
                                Proceed To Checkout
                            </x-luxury.button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('catalog') }}" class="text-xs uppercase font-sans tracking-ultra text-stone-500 hover:text-gold-700">
                                &larr; Continue Exploring Vault
                            </a>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
</x-storefront-layout>

