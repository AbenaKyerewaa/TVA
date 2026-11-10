<x-storefront-layout title="Armored Courier Tracking &mdash; The Vaughn Atelier">
    <div class="bg-stone-50 min-h-screen py-12 md:py-20 font-sans text-stone-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="text-center mb-10">
                <span class="text-xs uppercase tracking-ultra text-gold-700 font-semibold block mb-2">Discrete Courier Logistics</span>
                <h1 class="font-serif text-3xl md:text-4xl text-stone-900 tracking-wide">Armored Vault Dispatch Tracking</h1>
                <p class="text-stone-500 text-xs max-w-md mx-auto mt-2">
                    Enter your security tracking code or order reference number to monitor the real-time movement of your high jewelry creation.
                </p>
            </div>

            <!-- Search Form Card -->
            <div class="bg-white p-6 sm:p-8 border border-stone-200 shadow-sm mb-10">
                <form method="GET" action="{{ route('order.tracking') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                    <div class="sm:col-span-8">
                        <label for="tracking" class="block text-[11px] uppercase tracking-wider font-semibold text-stone-700 mb-1.5">
                            Security Tracking Code (e.g. TVA-SEC-XXXXXXXX)
                        </label>
                        <input type="text" 
                               id="tracking" 
                               name="tracking" 
                               value="{{ $trackingNumber ?? old('tracking') }}" 
                               placeholder="TVA-SEC-..." 
                               class="w-full text-sm font-mono border-stone-200 focus:border-stone-900 focus:ring-0 p-3">
                    </div>
                    <div class="sm:col-span-4">
                        <button type="submit" 
                                class="w-full py-3.5 bg-stone-950 text-gold-300 hover:bg-black hover:text-white transition-all text-xs uppercase tracking-ultra font-semibold">
                            Locate Consignment
                        </button>
                    </div>
                </form>

                <div class="mt-4 pt-4 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                    <span>Or locate by Order Reference + Email</span>
                    <button type="button" onclick="document.getElementById('alt-tracking-box').classList.toggle('hidden')" class="text-gold-700 hover:underline">
                        Order Number Lookup &rarr;
                    </button>
                </div>

                <div id="alt-tracking-box" class="hidden mt-4 pt-4 border-t border-stone-100">
                    <form method="GET" action="{{ route('order.tracking') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-wider font-medium text-stone-600 mb-1">Order Reference (e.g. TVA-20261101-ABCDE)</label>
                            <input type="text" name="order" value="{{ $orderNumber }}" placeholder="TVA-..." class="w-full text-xs font-mono border-stone-200 p-2.5">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-wider font-medium text-stone-600 mb-1">Customer Email</label>
                            <input type="email" name="email" value="{{ $email }}" placeholder="patron@..." class="w-full text-xs border-stone-200 p-2.5">
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="px-5 py-2.5 bg-stone-900 text-white text-xs uppercase tracking-wider font-semibold">
                                Track by Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($order)
                <!-- Tracking Details Dossier -->
                <div class="bg-white border border-stone-200 shadow-sm overflow-hidden mb-8">
                    
                    <!-- Dossier Header -->
                    <div class="p-6 bg-stone-900 text-white flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] uppercase tracking-widest text-gold-400 font-mono block">Order Reference</span>
                            <span class="font-mono text-base font-bold text-white">{{ $order->order_number }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] uppercase tracking-widest text-stone-400 block">Current Dispatch Stage</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wider 
                                @if($order->status === 'delivered') bg-emerald-100 text-emerald-900
                                @elseif($order->status === 'shipped') bg-gold-400 text-stone-950
                                @elseif($order->status === 'processing') bg-amber-100 text-amber-900
                                @else bg-stone-700 text-stone-200 @endif">
                                {{ $shipment ? str_replace('_', ' ', $shipment->status) : $order->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Visual Progress Timeline -->
                    <div class="p-6 md:p-8 border-b border-stone-100">
                        <div class="relative flex items-center justify-between">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-stone-200 -z-0"></div>

                            <!-- Step 1: Commissioned -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-9 h-9 rounded-full bg-stone-900 text-gold-400 flex items-center justify-center font-bold text-xs shadow-sm">
                                    &#10003;
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-semibold text-stone-900 mt-2">Commissioned</span>
                                <span class="text-[9px] text-stone-400 font-mono">{{ $order->created_at->format('M d') }}</span>
                            </div>

                            <!-- Step 2: Payment Verified -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-9 h-9 rounded-full {{ $order->payment_status === 'paid' ? 'bg-stone-900 text-gold-400' : 'bg-stone-200 text-stone-400' }} flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ $order->payment_status === 'paid' ? '✓' : '2' }}
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-semibold {{ $order->payment_status === 'paid' ? 'text-stone-900' : 'text-stone-400' }} mt-2">Payment Cleared</span>
                                <span class="text-[9px] text-stone-400 font-mono">{{ $order->paid_at ? $order->paid_at->format('M d') : 'Pending' }}</span>
                            </div>

                            <!-- Step 3: Vault Preparation -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                @php $isPrep = in_array($order->status, ['processing', 'shipped', 'delivered']); @endphp
                                <div class="w-9 h-9 rounded-full {{ $isPrep ? 'bg-stone-900 text-gold-400' : 'bg-stone-200 text-stone-400' }} flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ $isPrep ? '✓' : '3' }}
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-semibold {{ $isPrep ? 'text-stone-900' : 'text-stone-400' }} mt-2">Vault Preparation</span>
                                <span class="text-[9px] text-stone-400 font-mono">Assay & Cert</span>
                            </div>

                            <!-- Step 4: Armored Transit -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                @php $isShipped = in_array($order->status, ['shipped', 'delivered']) || ($shipment && in_array($shipment->status, ['in_transit', 'out_for_delivery', 'delivered'])); @endphp
                                <div class="w-9 h-9 rounded-full {{ $isShipped ? 'bg-stone-900 text-gold-400' : 'bg-stone-200 text-stone-400' }} flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ $isShipped ? '✓' : '4' }}
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-semibold {{ $isShipped ? 'text-stone-900' : 'text-stone-400' }} mt-2">Armored Transit</span>
                                <span class="text-[9px] text-stone-400 font-mono">{{ $shipment?->dispatched_at ? $shipment->dispatched_at->format('M d') : 'En Route' }}</span>
                            </div>

                            <!-- Step 5: Delivered -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                @php $isDelivered = $order->status === 'delivered' || ($shipment && $shipment->status === 'delivered'); @endphp
                                <div class="w-9 h-9 rounded-full {{ $isDelivered ? 'bg-emerald-700 text-white' : 'bg-stone-200 text-stone-400' }} flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ $isDelivered ? '✓' : '5' }}
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-semibold {{ $isDelivered ? 'text-stone-900' : 'text-stone-400' }} mt-2">Handed Over</span>
                                <span class="text-[9px] text-stone-400 font-mono">{{ $order->delivered_at ? $order->delivered_at->format('M d') : 'Pending' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Courier & Consignment Details -->
                    <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 text-xs">
                        <div class="space-y-2 leading-relaxed">
                            <h4 class="font-sans uppercase tracking-ultra font-bold text-stone-900 mb-2 text-[11px]">
                                Armored Dispatch Credentials
                            </h4>
                            <p><span class="text-stone-400">Security Carrier:</span> <span class="font-semibold text-stone-900">{{ $shipment->carrier ?? 'The Vaughn Atelier Armored Courier' }}</span></p>
                            @if($shipment)
                                <p><span class="text-stone-400">Tracking Code:</span> <span class="font-mono font-bold text-stone-900">{{ $shipment->tracking_number }}</span></p>
                            @endif
                            <p><span class="text-stone-400">Service Level:</span> <span class="font-medium text-stone-900">{{ $order->shippingMethod->name ?? 'The Vaughn Atelier Insured White-Glove Courier' }}</span></p>
                            @if($shipment && $shipment->notes)
                                <p class="mt-2 text-stone-600 bg-stone-50 p-2.5 rounded border border-stone-200">
                                    <span class="font-semibold text-stone-800">Courier Memo:</span> {{ $shipment->notes }}
                                </p>
                            @endif
                        </div>

                        <div class="space-y-2 leading-relaxed">
                            <h4 class="font-sans uppercase tracking-ultra font-bold text-stone-900 mb-2 text-[11px]">
                                Insured Destination
                            </h4>
                            <p class="font-semibold text-stone-900">{{ $order->customer_name }}</p>
                            <p class="text-stone-600">{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }}, {{ $order->shipping_country }}</p>
                            <p class="text-stone-500 font-mono text-[11px]">Signature verification required upon receipt.</p>
                            
                            <div class="pt-2">
                                <a href="{{ route('order.invoice', $order->order_number) }}" class="inline-flex items-center text-xs font-semibold text-gold-700 hover:underline">
                                    <svg class="w-4 h-4 mr-1 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Tax Invoice (PDF)
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Items Included -->
                    <div class="p-6 md:p-8 bg-stone-50/50 border-t border-stone-100">
                        <h4 class="font-sans uppercase tracking-ultra font-bold text-stone-900 mb-4 text-[11px]">
                            Protected Pieces in Consignment ({{ $order->items->count() }})
                        </h4>
                        <div class="divide-y divide-stone-200">
                            @foreach($order->items as $item)
                                <div class="py-3 flex items-center justify-between text-xs">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-white border border-stone-200 rounded overflow-hidden flex-shrink-0">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ $item->product->primaryImage->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center font-serif text-amber-700 text-[10px]">The Vaughn Atelier</div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-semibold text-stone-900 block">{{ $item->product_name }}</span>
                                            <span class="text-stone-500 text-[11px]">Qty: {{ $item->quantity }} &bull; {{ $item->variant_details ?? 'Master Edition' }}</span>
                                        </div>
                                    </div>
                                    <span class="font-serif font-semibold text-stone-900">${{ number_format($item->total_price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @elseif(request('tracking') || (request('order') && request('email')))
                <div class="p-8 text-center bg-white border border-stone-200 shadow-sm text-stone-500 text-xs">
                    <p class="font-semibold text-stone-900 text-sm mb-1">No Consignment Record Found</p>
                    <p>We could not find an armored courier shipment matching the provided details. Please verify your tracking number or contact The Vaughn Atelier Private Concierge hotline.</p>
                </div>
            @endif

        </div>
    </div>
</x-storefront-layout>

