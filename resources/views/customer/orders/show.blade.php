<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('account.orders.index') }}" class="text-xs uppercase tracking-wider text-stone-500 hover:text-amber-700 font-semibold mb-1 inline-block">
                    &larr; Back To All Acquisitions
                </a>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">
                    Acquisition Dossier: <span class="font-mono font-bold">{{ $order->order_number }}</span>
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

    <div class="py-12 bg-stone-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-md flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Order Status & Courier Ribbon -->
            <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <span class="w-3 h-3 rounded-full {{ $order->status === 'delivered' ? 'bg-emerald-500' : 'bg-gold-500 animate-pulse' }}"></span>
                    <div>
                        <span class="text-[10px] uppercase tracking-wider text-stone-400 font-medium block">Current Fulfillment Status</span>
                        <span class="text-sm font-bold uppercase tracking-wider text-stone-900">{{ $order->status }}</span>
                    </div>
                </div>

                @if($order->shipment)
                    <div class="flex items-center space-x-4">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-stone-400 font-mono block">Armored Courier Tracking</span>
                            <span class="text-xs font-mono font-bold text-stone-900">{{ $order->shipment->tracking_number }}</span>
                        </div>
                        <a href="{{ route('order.tracking', ['tracking' => $order->shipment->tracking_number]) }}" class="px-4 py-2 bg-stone-900 text-white rounded text-xs uppercase tracking-wider font-semibold hover:bg-black transition-colors">
                            Track Courier Live &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Commissioned Pieces (8 Cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-lg shadow-sm">
                        <h3 class="font-serif text-lg text-stone-900 pb-3 border-b border-stone-100 mb-4">
                            Purchased Heirlooms ({{ $order->items->count() }})
                        </h3>

                        <div class="divide-y divide-stone-100">
                            @foreach($order->items as $item)
                                <div class="py-5" x-data="{ showReviewModal: false }">
                                    <div class="flex items-center justify-between">
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
                                                        <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-amber-700">
                                                            {{ $item->product_name }}
                                                        </a>
                                                    @else
                                                        {{ $item->product_name }}
                                                    @endif
                                                </h4>
                                                @if($item->variant_details)
                                                    <p class="text-xs text-stone-500 font-sans mt-0.5">{{ $item->variant_details }}</p>
                                                @endif
                                                <p class="text-xs text-stone-400 mt-0.5">Quantity: {{ $item->quantity }} &times; ${{ number_format($item->unit_price, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-serif font-bold text-sm text-stone-900 block">${{ number_format($item->total_price, 2) }}</span>
                                            
                                            <!-- Review Button if order is paid -->
                                            @if($order->payment_status === 'paid' && $item->product)
                                                <button type="button" 
                                                        @click="showReviewModal = !showReviewModal" 
                                                        class="mt-2 text-[11px] uppercase tracking-wider text-amber-700 hover:underline font-semibold">
                                                    &star; Write Appraisal
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Review Form Drawer / Modal -->
                                    <div x-show="showReviewModal" x-cloak class="mt-4 p-4 bg-stone-50 border border-stone-200 rounded text-xs space-y-3">
                                        <h5 class="font-semibold text-stone-900 uppercase tracking-wider text-[11px]">
                                            Patron Appraisal for {{ $item->product_name }}
                                        </h5>
                                        <form method="POST" action="{{ route('reviews.store') }}" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-medium text-stone-700 mb-1">Rating</label>
                                                    <select name="rating" class="w-full text-xs border-stone-200 rounded p-1.5 focus:ring-0">
                                                        <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733; (5/5) Exceptional Masterpiece</option>
                                                        <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734; (4/5) Very Pleased</option>
                                                        <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734; (3/5) Satisfactory</option>
                                                        <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734; (2/5) Disappointed</option>
                                                        <option value="1">&#9733;&#9734;&#9734;&#9734;&#9734; (1/5) Unsatisfied</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block font-medium text-stone-700 mb-1">Headline</label>
                                                    <input type="text" name="title" required placeholder="e.g. Unbelievable brilliance and finish..." class="w-full text-xs border-stone-200 rounded p-1.5 focus:ring-0">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block font-medium text-stone-700 mb-1">Your Evaluation & Appraisal</label>
                                                <textarea name="comment" rows="2" required placeholder="Describe your experience with the cut, luster, packaging, and craftsmanship..." class="w-full text-xs border-stone-200 rounded p-1.5 focus:ring-0"></textarea>
                                            </div>

                                            <div class="flex justify-end space-x-2">
                                                <button type="button" @click="showReviewModal = false" class="px-3 py-1 border border-stone-200 text-stone-600 rounded">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-4 py-1 bg-stone-900 text-white rounded font-semibold uppercase tracking-wider text-[10px]">
                                                    Submit Testimonial
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pricing Summary -->
                        <div class="mt-6 pt-4 border-t border-stone-100 max-w-xs ml-auto space-y-2 text-xs">
                            <div class="flex justify-between text-stone-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-emerald-700">
                                    <span>Privilege Code ({{ $order->coupon_code }})</span>
                                    <span>-${{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-stone-600">
                                <span>White-Glove Insured Delivery</span>
                                <span>{{ $order->shipping_fee > 0 ? '$'.number_format($order->shipping_fee, 2) : 'Complimentary' }}</span>
                            </div>
                            <div class="flex justify-between items-baseline font-bold text-sm text-stone-950 border-t border-stone-200 pt-2">
                                <span>Total Settled</span>
                                <span class="font-serif text-lg">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Delivery Destination & Method (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm space-y-4 text-xs">
                        <h4 class="font-serif text-base text-stone-900 pb-2 border-b border-stone-100">
                            Insured Consignment Address
                        </h4>
                        <div class="leading-relaxed text-stone-700">
                            <p class="font-semibold text-stone-900">{{ $order->customer_name }}</p>
                            <p>{{ $order->shipping_address_line1 }}</p>
                            @if($order->shipping_address_line2) <p>{{ $order->shipping_address_line2 }}</p> @endif
                            <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }} {{ $order->shipping_postal_code }}</p>
                            <p>{{ $order->shipping_country }}</p>
                            <p class="mt-2 text-stone-500 font-mono">Tel: {{ $order->customer_phone ?? 'On file' }}</p>
                        </div>

                        <div class="pt-3 border-t border-stone-100">
                            <span class="text-[10px] uppercase tracking-wider text-stone-400 block mb-1">Service Tier</span>
                            <p class="font-medium text-stone-900">{{ $order->shippingMethod->name ?? 'TVA Armored Courier' }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm space-y-3 text-xs">
                        <h4 class="font-serif text-base text-stone-900 pb-2 border-b border-stone-100">
                            Payment Verification
                        </h4>
                        <p><span class="text-stone-400">Method:</span> <span class="font-semibold uppercase text-stone-900">{{ $order->payment_method ?? 'Vault Settlement' }}</span></p>
                        <p><span class="text-stone-400">Status:</span> <span class="font-semibold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $order->payment_status }}</span></p>
                        @if($order->paid_at)
                            <p><span class="text-stone-400">Settled On:</span> <span class="text-stone-700 font-mono">{{ $order->paid_at->format('M d, Y H:i') }}</span></p>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

