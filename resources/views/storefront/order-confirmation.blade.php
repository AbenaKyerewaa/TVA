<x-storefront-layout title="Order Confirmed &mdash; #{{ $order->order_number }} &mdash; The Vaughn Atelier">
    <div class="bg-stone-50 min-h-screen py-12 md:py-20 font-sans text-stone-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success Header -->
            <div class="text-center mb-12">
                <div class="w-16 h-16 bg-stone-900 text-gold-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md border border-gold-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-ultra text-gold-700 mb-2">Acquisition Confirmed</p>
                <h1 class="font-serif text-3xl md:text-5xl text-stone-900 tracking-tight">Thank You, {{ $order->customer_name }}</h1>
                <p class="text-stone-600 text-sm max-w-lg mx-auto mt-3">
                    Your bespoke commission has entered our master workshop. We have transmitted full verification credentials to <span class="font-semibold text-stone-900">{{ $order->customer_email }}</span>.
                </p>
                
                <div class="mt-6 inline-flex items-center space-x-3 bg-white border border-stone-200 px-5 py-2.5 shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-stone-400">Order Reference:</span>
                    <span class="font-mono text-sm font-bold text-stone-900">{{ $order->order_number }}</span>
                    <span class="text-stone-300">|</span>
                    <span class="text-xs uppercase tracking-wider text-stone-400">Status:</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <!-- Main Order Receipt Card -->
            <div class="bg-white border border-stone-200 shadow-sm overflow-hidden mb-8">
                
                <!-- Receipt Actions Toolbar -->
                <div class="p-6 bg-stone-900 text-stone-200 flex flex-wrap items-center justify-between gap-4 border-b border-stone-800">
                    <div class="flex items-center space-x-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-gold-400 animate-pulse"></span>
                        <span class="text-xs uppercase tracking-widest font-mono text-gold-300">Vault Fulfillment Underway</span>
                    </div>
                    <div>
                        <a href="{{ route('order.invoice', $order->order_number) }}" 
                           class="inline-flex items-center space-x-2 px-4 py-2 bg-stone-800 hover:bg-stone-700 text-white text-xs uppercase tracking-wider font-semibold border border-stone-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download Official PDF Invoice</span>
                        </a>
                    </div>
                </div>

                <!-- Order Details Grid -->
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-stone-100 text-xs">
                    <div>
                        <h4 class="font-sans uppercase tracking-ultra font-bold text-stone-900 mb-3 text-[11px]">
                            White-Glove Dispatch Destination
                        </h4>
                        <div class="text-stone-600 leading-relaxed">
                            <p class="font-semibold text-stone-900">{{ $order->customer_name }}</p>
                            <p>{{ $order->shipping_address_line1 }}</p>
                            @if($order->shipping_address_line2)
                                <p>{{ $order->shipping_address_line2 }}</p>
                            @endif
                            <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }} {{ $order->shipping_postal_code }}</p>
                            <p>{{ $order->shipping_country }}</p>
                            <p class="mt-2 text-stone-500 font-mono">Phone: {{ $order->customer_phone ?? 'On file' }}</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-sans uppercase tracking-ultra font-bold text-stone-900 mb-3 text-[11px]">
                            Dispatch & Settlement Information
                        </h4>
                        <div class="text-stone-600 space-y-1.5 leading-relaxed">
                            <p><span class="text-stone-400">Shipping Method:</span> <span class="font-medium text-stone-900">{{ $order->shippingMethod->name ?? 'The Vaughn Atelier Insured White-Glove Courier' }}</span></p>
                            <p><span class="text-stone-400">Estimated Delivery:</span> <span class="font-medium text-stone-900">{{ $order->shippingMethod->estimated_delivery_days ?? '2-4' }} Business Days</span></p>
                            <p><span class="text-stone-400">Payment Method:</span> <span class="font-medium text-stone-900 uppercase">{{ $order->payment_method ?? 'Vault Authorized' }}</span></p>
                            <p><span class="text-stone-400">Payment Status:</span> <span class="font-semibold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $order->payment_status }}</span></p>
                            @if($order->payments->isNotEmpty())
                                <p><span class="text-stone-400">Transaction ID:</span> <span class="font-mono text-stone-800">{{ $order->payments->first()->transaction_reference }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Items Purchased Table -->
                <div class="p-6 md:p-8">
                    <h4 class="font-sans uppercase tracking-ultra font-bold text-stone-900 mb-4 text-[11px]">
                        Commissioned Pieces ({{ $order->items->count() }})
                    </h4>
                    <div class="divide-y divide-stone-100">
                        @foreach($order->items as $item)
                            <div class="py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-stone-100 flex-shrink-0 border border-stone-200 overflow-hidden">
                                        @if($item->product && $item->product->primaryImage)
                                            <img src="{{ $item->product->primaryImage->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gold-600 font-serif text-[10px] text-center px-1">The Vaughn Atelier</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-semibold text-stone-900">{{ $item->product_name }}</h5>
                                        @if($item->variant_name)
                                            <p class="text-xs text-stone-500">{{ $item->variant_name }}</p>
                                        @endif
                                        <p class="text-xs text-stone-500 mt-1">Quantity: {{ $item->quantity }} &times; ${{ number_format($item->unit_price, 2) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-stone-900">${{ number_format($item->total_price, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="mt-6 border-t border-stone-200 pt-4 max-w-xs ml-auto space-y-2 text-xs">
                        <div class="flex justify-between text-stone-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-700">
                                <span>Vault Privilege Discount</span>
                                <span>-${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-stone-600">
                            <span>Insured White-Glove Dispatch</span>
                            <span>{{ $order->shipping_fee > 0 ? '$'.number_format($order->shipping_fee, 2) : 'Complimentary' }}</span>
                        </div>
                        <div class="flex justify-between text-stone-600">
                            <span>Estimated Tax</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-baseline text-sm font-semibold text-stone-950 border-t border-stone-200 pt-3">
                            <span class="uppercase tracking-wider">Total Settled</span>
                            <span class="font-serif text-xl">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Navigation Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('collections') }}" 
                   class="w-full sm:w-auto px-8 py-3.5 bg-stone-900 text-white hover:bg-stone-800 text-xs uppercase tracking-ultra font-semibold text-center transition-all">
                    Explore Haute Joaillerie
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="w-full sm:w-auto px-8 py-3.5 bg-white border border-stone-300 text-stone-800 hover:border-stone-900 text-xs uppercase tracking-ultra font-semibold text-center transition-all">
                        View Order in Client Vault
                    </a>
                @endauth
            </div>

        </div>
    </div>
</x-storefront-layout>

