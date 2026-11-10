<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-prestige text-amber-700 font-semibold">The Vaughn Atelier &bull; Private Client Portal</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Welcome, {{ auth()->user()->name }}</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('account.orders.index') }}" class="px-4 py-2 border border-stone-300 text-stone-800 text-xs uppercase font-sans tracking-ultra hover:border-stone-900 transition-colors">
                    My Acquisitions
                </a>
                <a href="{{ route('catalog') }}" class="px-4 py-2 bg-stone-900 text-white text-xs uppercase font-sans tracking-ultra hover:bg-stone-800 transition-colors shadow-sm">
                    Explore The Vault
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-md flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- VIP Patron Dossier Banner -->
            <div class="bg-noir-950 text-white p-6 sm:p-8 rounded-lg shadow-sm border border-stone-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-gold-500/20 text-gold-300 border border-gold-400/40">
                            {{ ucfirst(auth()->user()->customerProfile->vip_tier ?? 'Gold') }} Circle Patron
                        </span>
                        <span class="text-xs text-stone-400">&bull; Patron Since {{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                    <h3 class="font-serif text-2xl font-light text-white">{{ auth()->user()->name }}</h3>
                    <p class="text-xs text-stone-400 mt-1">{{ auth()->user()->email }} &bull; {{ auth()->user()->phone ?? 'Private telephone on file' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customer.profile') }}" class="px-4 py-2 bg-stone-800 hover:bg-stone-700 text-white text-xs uppercase tracking-wider rounded font-medium transition-colors">
                        Preferences & Sizing
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="px-4 py-2 bg-gold-600 hover:bg-gold-500 text-stone-950 text-xs uppercase tracking-wider rounded font-semibold transition-colors">
                        View Saved Pieces ({{ $metrics['saved_vault_pieces'] ?? 0 }})
                    </a>
                </div>
            </div>

            <!-- 4 Quick Key Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Acquisition Count</span>
                    <p class="text-2xl font-serif text-stone-900 mt-1 font-bold">{{ number_format($metrics['total_acquisitions'] ?? 0) }} Orders</p>
                    <span class="text-[11px] text-stone-400">Commissioned heirlooms</span>
                </div>
                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Lifetime Valuation</span>
                    <p class="text-2xl font-serif text-stone-900 mt-1 font-bold">${{ number_format($metrics['lifetime_spend'] ?? 0, 2) }}</p>
                    <span class="text-[11px] text-stone-400">Total certified portfolio</span>
                </div>
                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-amber-700 font-medium">Active Dispatches</span>
                    <p class="text-2xl font-serif text-amber-800 mt-1 font-bold">{{ $metrics['active_dispatches'] ?? 0 }}</p>
                    <span class="text-[11px] text-stone-400">In armored transit / vault prep</span>
                </div>
                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Saved Pieces</span>
                    <p class="text-2xl font-serif text-stone-900 mt-1 font-bold">{{ $metrics['saved_vault_pieces'] ?? 0 }}</p>
                    <span class="text-[11px] text-stone-400">In your private wishlist</span>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-stone-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-serif text-xl text-stone-900">Recent Jewelry Acquisitions</h3>
                        <p class="text-xs text-stone-500">Track order fulfillment, armored courier dispatches, and gemological certificates.</p>
                    </div>
                    @if(auth()->user()->orders->isNotEmpty())
                        <a href="{{ route('account.orders.index') }}" class="text-xs uppercase tracking-wider font-semibold text-amber-700 hover:underline">
                            View All ({{ auth()->user()->orders->count() }}) &rarr;
                        </a>
                    @endif
                </div>

                @if(auth()->user()->orders->isEmpty())
                    <div class="p-12 text-center text-stone-400 text-xs space-y-3">
                        <svg class="w-10 h-10 mx-auto text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p>You have not commissioned any creations with The Vaughn Atelier yet.</p>
                        <a href="{{ route('catalog') }}" class="inline-block mt-2 px-6 py-3 bg-stone-900 text-white text-xs uppercase font-sans tracking-ultra hover:bg-stone-800 transition-colors shadow-sm">
                            Explore The Vault
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-stone-100">
                        @foreach($recentOrders as $order)
                            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-stone-50/50 transition-colors">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('account.orders.show', $order->order_number) }}" class="font-mono text-sm font-bold text-stone-900 hover:text-amber-700">
                                            {{ $order->order_number }}
                                        </a>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider 
                                            @if($order->status === 'delivered') bg-emerald-100 text-emerald-800
                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                            @else bg-amber-100 text-amber-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-stone-500">
                                        Commissioned on {{ $order->created_at->format('F d, Y') }} &bull; {{ $order->items->count() }} Piece(s) &bull; 
                                        <span class="font-serif font-bold text-stone-900">${{ number_format($order->total_amount, 2) }}</span>
                                    </p>
                                </div>
                                <div class="flex items-center space-x-3 text-xs">
                                    @if($order->shipment)
                                        <a href="{{ route('order.tracking', ['tracking' => $order->shipment->tracking_number]) }}" class="px-3 py-1.5 border border-stone-200 text-stone-700 hover:border-stone-900 rounded font-medium">
                                            Track Dispatch
                                        </a>
                                    @endif
                                    <a href="{{ route('order.invoice', $order->order_number) }}" class="px-3 py-1.5 border border-stone-200 text-stone-700 hover:border-stone-900 rounded font-medium">
                                        PDF Invoice
                                    </a>
                                    <a href="{{ route('account.orders.show', $order->order_number) }}" class="px-4 py-1.5 bg-stone-900 text-white hover:bg-black rounded font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Address Book & Concierge Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Address Book Card -->
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-2xs">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-3">
                        <span class="text-xs uppercase tracking-ultra text-stone-400 font-medium">Registered Private Addresses</span>
                        <a href="{{ route('customer.addresses.index') }}" class="text-xs text-amber-700 hover:underline">Manage &rarr;</a>
                    </div>
                    @if(auth()->user()->addresses->isNotEmpty())
                        <div class="text-xs text-stone-600 space-y-1">
                            <p class="font-semibold text-stone-900">{{ auth()->user()->addresses->first()->full_name }}</p>
                            <p>{{ auth()->user()->addresses->first()->formatted_address }}</p>
                        </div>
                    @else
                        <p class="text-xs text-stone-400">No saved addresses on file. You can register addresses during checkout.</p>
                    @endif
                </div>

                <!-- Private Concierge Hotline -->
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-ultra text-gold-700 font-semibold block mb-2">Dedicated Concierge Service</span>
                    <h4 class="font-serif text-lg text-stone-900">Personal Atelier Liaison</h4>
                    <p class="text-xs text-stone-500 mt-1 leading-relaxed">
                        For bespoke engraving, anniversary surprises, or private salon appointments in Paris or New York, contact your liaison at <a href="mailto:concierge@thevaughnatelier.com" class="text-amber-700 underline">concierge@thevaughnatelier.com</a>.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
