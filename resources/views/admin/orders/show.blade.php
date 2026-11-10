<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs uppercase tracking-wider text-stone-500 hover:text-amber-700 font-semibold mb-1 inline-block">
                    &larr; Back To Orders
                </a>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">
                    Order Dossier: <span class="font-mono font-bold">{{ $order->order_number }}</span>
                </h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('order.invoice', $order->order_number) }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2 bg-stone-900 text-white hover:bg-black rounded text-xs uppercase tracking-wider font-semibold shadow-sm transition-colors">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Download Tax Invoice (PDF)</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-md flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Order Details & Line Items (8 Cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Line Items Table -->
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-lg shadow-sm">
                        <h3 class="font-serif text-lg text-stone-900 pb-3 border-b border-stone-100 mb-4">
                            Commissioned Jewelry Pieces ({{ $order->items->count() }})
                        </h3>

                        <div class="divide-y divide-stone-100">
                            @foreach($order->items as $item)
                                <div class="py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-16 h-16 bg-stone-100 border border-stone-200 rounded overflow-hidden flex-shrink-0">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ $item->product->primaryImage->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center font-serif text-amber-700">TVA</div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-stone-900">
                                                @if($item->product)
                                                    <a href="{{ route('product.show', $item->product->slug) }}" target="_blank" class="hover:text-amber-700">
                                                        {{ $item->product_name }} &rarr;
                                                    </a>
                                                @else
                                                    {{ $item->product_name }}
                                                @endif
                                            </h4>
                                            @if($item->variant_details)
                                                <p class="text-xs text-stone-500 font-sans mt-0.5">{{ $item->variant_details }}</p>
                                            @endif
                                            <p class="text-[11px] text-stone-400 font-mono mt-0.5">SKU: {{ $item->sku }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-stone-500">{{ $item->quantity }} &times; ${{ number_format($item->unit_price, 2) }}</p>
                                        <p class="text-sm font-serif font-bold text-stone-900">${{ number_format($item->total_price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Financial Summary -->
                        <div class="mt-6 pt-4 border-t border-stone-100 max-w-xs ml-auto space-y-2 text-xs">
                            <div class="flex justify-between text-stone-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-emerald-700">
                                    <span>Discount ({{ $order->coupon_code }})</span>
                                    <span>-${{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-stone-600">
                                <span>Insured Armored Courier</span>
                                <span>{{ $order->shipping_fee > 0 ? '$'.number_format($order->shipping_fee, 2) : 'Complimentary' }}</span>
                            </div>
                            <div class="flex justify-between text-stone-600">
                                <span>Tax</span>
                                <span>${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-baseline font-bold text-sm text-stone-950 border-t border-stone-200 pt-2">
                                <span>Total Settled</span>
                                <span class="font-serif text-lg">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Client Special Instructions -->
                    @if($order->customer_notes)
                        <div class="bg-amber-50/60 p-6 border border-amber-200 rounded-lg">
                            <h4 class="text-xs uppercase tracking-wider font-semibold text-amber-900 mb-2">
                                Patron Special Concierge Notes
                            </h4>
                            <p class="text-xs text-amber-800 leading-relaxed font-sans">
                                "{{ $order->customer_notes }}"
                            </p>
                        </div>
                    @endif

                    <!-- Internal Admin Audit & Notes History -->
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-lg shadow-sm space-y-4">
                        <h4 class="text-xs uppercase tracking-ultra font-bold text-stone-900 pb-2 border-b border-stone-100">
                            Internal Atelier Audit Notes
                        </h4>
                        
                        @if($order->admin_notes)
                            <div class="p-4 bg-stone-50 rounded border border-stone-200 font-mono text-xs text-stone-700 whitespace-pre-wrap leading-relaxed">
                                {{ $order->admin_notes }}
                            </div>
                        @else
                            <p class="text-xs text-stone-400">No internal atelier notes recorded yet.</p>
                        @endif

                        <form method="POST" action="{{ route('admin.orders.note', $order->id) }}" class="space-y-3 pt-2">
                            @csrf
                            <label class="block text-xs uppercase tracking-wider font-medium text-stone-600">Append Atelier Administrative Note</label>
                            <textarea name="note" rows="2" class="w-full text-xs border-stone-200 rounded focus:border-amber-500 focus:ring-0 p-2" placeholder="e.g. Ring sized to 6.5 by master artisan, verified hallmark..."></textarea>
                            <button type="submit" class="px-4 py-2 bg-stone-800 text-white text-xs uppercase tracking-wider rounded font-medium hover:bg-stone-700">
                                Append Note
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Right: Status Transition & Courier Information (4 Cols) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Order Lifecycle Management -->
                    <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm space-y-5">
                        <h3 class="font-serif text-lg text-stone-900 pb-2 border-b border-stone-100">
                            Lifecycle Status
                        </h3>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-stone-500">Order Status:</span>
                                <span class="font-bold uppercase tracking-wider text-amber-800">{{ $order->status }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-stone-500">Payment Status:</span>
                                <span class="font-bold uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $order->payment_status }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-stone-500">Shipping Status:</span>
                                <span class="font-bold uppercase tracking-wider text-stone-800">{{ $order->shipping_status }}</span>
                            </div>
                        </div>

                        <!-- Update Status Form -->
                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-4 pt-3 border-t border-stone-100">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider font-medium text-stone-700 mb-1">
                                    Transition Status
                                </label>
                                <select name="status" class="w-full text-xs border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Vault Assembly)</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped (Armored Courier)</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered (Handed Over)</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Restock Vault)</option>
                                    <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded (Restock Vault)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider font-medium text-stone-700 mb-1">
                                    Transition Reason / Log Note
                                </label>
                                <input type="text" name="admin_note" placeholder="e.g. Dispatched with armored security" class="w-full text-xs border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-amber-700 text-white text-xs uppercase tracking-wider font-semibold rounded hover:bg-amber-800 transition-colors shadow-sm">
                                Update Order Lifecycle
                            </button>
                        </form>
                    </div>

                    <!-- Client & Shipping Destination -->
                    <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm space-y-4 text-xs">
                        <h4 class="font-serif text-base text-stone-900 pb-2 border-b border-stone-100">
                            Patron Credentials
                        </h4>
                        <div>
                            <p class="font-semibold text-stone-900">{{ $order->customer_name }}</p>
                            <p class="text-stone-600">{{ $order->customer_email }}</p>
                            <p class="text-stone-600 font-mono">Tel: {{ $order->customer_phone ?? 'N/A' }}</p>
                        </div>

                        <div class="pt-3 border-t border-stone-100">
                            <span class="text-[11px] uppercase tracking-wider font-bold text-stone-500 block mb-1">Shipping Destination</span>
                            <p class="text-stone-700 leading-relaxed">
                                {{ $order->shipping_address_line1 }}<br>
                                @if($order->shipping_address_line2) {{ $order->shipping_address_line2 }}<br> @endif
                                {{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }} {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}
                            </p>
                        </div>
                    </div>

                    <!-- Armored Dispatch Logistics -->
                    <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm space-y-3 text-xs">
                        <h4 class="font-serif text-base text-stone-900 pb-2 border-b border-stone-100">
                            Armored Courier Logistics
                        </h4>
                        <p><span class="text-stone-500">Courier Service:</span> <span class="font-semibold text-stone-900">{{ $order->shippingMethod->name ?? 'The Vaughn Atelier Insured Courier' }}</span></p>
                        @if($order->shipment)
                            <p><span class="text-stone-500">Security Carrier:</span> <span class="font-semibold text-stone-900">{{ $order->shipment->carrier }}</span></p>
                            <p><span class="text-stone-500">Tracking Code:</span> <span class="font-mono font-bold text-stone-900">{{ $order->shipment->tracking_number }}</span></p>
                            <p><span class="text-stone-500">Shipment Status:</span> <span class="font-semibold uppercase tracking-wider text-amber-800">{{ $order->shipment->status }}</span></p>
                            <div class="pt-2">
                                <a href="{{ route('order.tracking', ['tracking' => $order->shipment->tracking_number]) }}" target="_blank" class="text-amber-700 hover:underline font-semibold">
                                    Open Live Client Tracking &rarr;
                                </a>
                            </div>
                        @else
                            <p class="text-stone-400">Shipment dispatch record will be initialized upon status change to Shipped.</p>
                        @endif
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>

