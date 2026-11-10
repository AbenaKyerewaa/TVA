@php
    $cartItemCount = 0;
    $wishlistItemCount = 0;
    try {
        $cartItemCount = app(\App\Services\CartService::class)->getItemCount();
        if (auth()->check()) {
            $wishlistItemCount = \App\Models\WishlistItem::whereHas('wishlist', function($query) {
                $query->where('user_id', auth()->id());
            })->count();
        } else {
            $wishlistId = session('wishlist_id');
            $sessionId = session()->getId();
            $wishlistItemCount = \App\Models\WishlistItem::whereHas('wishlist', function($query) use ($wishlistId, $sessionId) {
                if ($wishlistId) {
                    $query->where('id', $wishlistId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->count();
        }
    } catch (\Throwable $e) {
        // Fallback gracefully
    }
@endphp

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-stone-200/80 transition-all duration-300">
    <!-- Announcement Bar -->
    <div class="bg-noir-950 text-stone-300 text-[11px] font-sans tracking-ultra uppercase py-2 text-center px-4 border-b border-stone-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <span class="hidden md:inline text-gold-400">The Vaughn Atelier</span>
            <span class="mx-auto md:mx-0">Complimentary Insured Courier on All Orders &bull; Handcrafted in 18K Solid Gold</span>
            <div class="hidden md:flex items-center space-x-4 text-stone-400">
                <a href="#concierge" class="hover:text-gold-300 transition-colors">Bespoke Concierge</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Mobile Menu Trigger -->
            <div class="flex items-center lg:hidden">
                <button type="button" 
                        class="p-2 text-stone-700 hover:text-gold-600 focus:outline-none"
                        onclick="document.getElementById('mobile-drawer').classList.toggle('hidden')"
                        aria-label="Toggle Navigation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Left Navigation Links (Desktop) -->
            <nav class="hidden lg:flex items-center space-x-7 text-xs font-sans uppercase tracking-ultra font-medium text-stone-800">
                <a href="{{ route('home') }}" class="hover:text-gold-600 transition-colors">Home</a>
                <a href="{{ route('founder') }}" class="hover:text-gold-600 transition-colors">The Founder</a>
                <a href="{{ route('catalog') }}" class="hover:text-gold-600 transition-colors">Vault</a>
                <a href="{{ route('catalog', ['category' => 'rings']) }}" class="hover:text-gold-600 transition-colors">Rings</a>
                <a href="{{ route('catalog', ['category' => 'necklaces']) }}" class="hover:text-gold-600 transition-colors">Necklaces</a>
                <a href="{{ route('catalog', ['category' => 'engagement']) }}" class="hover:text-gold-600 transition-colors">Bridal</a>
            </nav>

            <!-- Brand Logo (Center) -->
            <div class="flex-shrink-0">
                <x-luxury.brand-logo size="md" />
            </div>

            <!-- Right Controls: Search, Account, Wishlist, Cart (Desktop & Mobile) -->
            <div class="flex items-center space-x-5 text-stone-800">
                <!-- Search Button -->
                <a href="{{ route('catalog') }}" class="p-1 hover:text-gold-600 transition-colors" aria-label="Search Collection">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </a>

                <!-- Account / Authentication (for Authenticated Users) -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-1 hover:text-gold-600 transition-colors focus:outline-none flex items-center space-x-1" aria-label="Account">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-cloak
                             class="absolute right-0 mt-3 w-48 bg-white border border-stone-200 shadow-lg py-2 z-50 text-xs font-sans uppercase tracking-wider">
                            <div class="px-4 py-2 border-b border-stone-100 text-stone-500 lowercase">
                                {{ auth()->user()->email }}
                            </div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-amber-700 hover:bg-stone-50 font-semibold">
                                    Atelier Admin
                                </a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-stone-700 hover:bg-stone-50">
                                My Vault & Orders
                            </a>
                            <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-stone-700 hover:bg-stone-50">
                                Preferences
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-rose-600 hover:bg-stone-50">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Wishlist -->
                <a href="{{ route('wishlist.index') }}" class="p-1 hover:text-gold-600 transition-colors relative" aria-label="Saved Vault Pieces">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    @if($wishlistItemCount > 0)
                        <span class="absolute -top-1 -right-1 bg-stone-900 text-gold-300 rounded-full text-[9px] w-4 h-4 flex items-center justify-center font-bold">
                            {{ $wishlistItemCount }}
                        </span>
                    @endif
                </a>

                <!-- Shopping Bag -->
                <a href="{{ route('cart.index') }}" class="p-1 hover:text-gold-600 transition-colors relative" aria-label="Shopping Bag">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-gold-600 text-white rounded-full text-[9px] w-4 h-4 flex items-center justify-center font-bold">
                        {{ $cartItemCount }}
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-drawer" class="hidden lg:hidden border-t border-stone-200 bg-white px-6 py-6 space-y-4 text-xs font-sans uppercase tracking-ultra font-medium">
        <a href="{{ route('home') }}" class="block py-2 text-stone-900 border-b border-stone-100">Home</a>
        <a href="{{ route('founder') }}" class="block py-2 text-gold-700 font-semibold border-b border-stone-100">The Founder: Jennifer Vaughn</a>
        <a href="{{ route('catalog') }}" class="block py-2 text-stone-900 border-b border-stone-100">All High Jewelry</a>
        <a href="{{ route('catalog', ['category' => 'rings']) }}" class="block py-2 text-stone-900 border-b border-stone-100">Rings</a>
        <a href="{{ route('catalog', ['category' => 'necklaces']) }}" class="block py-2 text-stone-900 border-b border-stone-100">Necklaces</a>
        <a href="{{ route('catalog', ['category' => 'earrings']) }}" class="block py-2 text-stone-900 border-b border-stone-100">Earrings</a>
        <a href="{{ route('catalog', ['category' => 'engagement']) }}" class="block py-2 text-stone-900 border-b border-stone-100">Bridal & Engagement</a>
        @auth
            <a href="{{ route('dashboard') }}" class="block py-2 text-gold-700 border-b border-stone-100">My Client Account</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left py-2 text-rose-600">
                    Sign Out
                </button>
            </form>
        @endauth
    </div>
</header>

