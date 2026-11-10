<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-xs uppercase tracking-wider text-stone-500 hover:text-amber-700 font-semibold mb-1 inline-block">
                    &larr; Back To Client Portal
                </a>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">My Jewelry Acquisitions</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('catalog') }}" class="px-4 py-2 bg-stone-900 text-white text-xs uppercase font-sans tracking-ultra hover:bg-stone-800 transition-colors">
                    Explore Vault
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-md flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-stone-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-serif text-xl text-stone-900">Historical Vault Commissions</h3>
                        <p class="text-xs text-stone-500">Every piece is individually hallmarked and covered under Lloyd's of London transit insurance.</p>
                    </div>
                </div>

                @if($orders->isEmpty())
                    <div class="p-16 text-center text-stone-400 text-xs space-y-3">
                        <svg class="w-12 h-12 mx-auto text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-sm font-semibold text-stone-800">No Acquisitions Registered</p>
                        <p>Explore our private high jewelry vault to commission solid gold and certified diamond heirlooms.</p>
                        <div class="pt-2">
                            <a href="{{ route('catalog') }}" class="inline-block px-6 py-3 bg-stone-900 text-white text-xs uppercase font-sans tracking-ultra hover:bg-stone-800 transition-colors shadow-sm">
                                View Haute Joaillerie
                            </a>
                        </div>
                    </div>
                @else
                    <div class="divide-y divide-stone-100">
                        @foreach($orders as $order)
                            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-stone-50/50 transition-colors">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-3">
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
                                    <p class="text-[11px] text-stone-400">
                                        Shipped to {{ $order->shipping_city }}, {{ $order->shipping_country }} via {{ $order->shippingMethod->name ?? 'Armored Courier' }}
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

                    @if($orders->hasPages())
                        <div class="p-4 border-t border-stone-100">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

