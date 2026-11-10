<x-storefront-layout title="Acquisition Ticket #{{ $order->order_number }} &mdash; The Vaughn Atelier">
    <div class="bg-stone-50 min-h-screen py-12 md:py-20 font-sans text-stone-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs / Steps Indicator -->
            <div class="mb-10 text-center print:hidden">
                <nav class="flex items-center justify-center space-x-3 text-xs uppercase tracking-ultra text-stone-400 font-medium mb-3">
                    <span class="text-stone-400">01. Bag</span>
                    <span>&mdash;</span>
                    <span class="text-stone-400">02. Dispatch Details</span>
                    <span>&mdash;</span>
                    <span class="text-gold-700 font-semibold border-b border-gold-600 pb-0.5">03. Acquisition Ticket</span>
                </nav>
                <h1 class="font-serif text-3xl md:text-4xl text-stone-900 tracking-wide">Acquisition Reservation Ticket</h1>
                <p class="text-stone-500 text-xs uppercase tracking-widest mt-2 font-mono">
                    Ticket Reference: <span class="text-stone-900 font-bold">{{ $order->order_number }}</span>
                </p>
            </div>

            <!-- Print Actions Toolbar (Screen Only) -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-white p-4 border border-stone-200 rounded-sm shadow-2xs print:hidden">
                <div class="flex items-center space-x-2 text-xs text-stone-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="font-medium text-stone-900">Vault Reservation Registered</span>
                    <span class="text-stone-400">&bull;</span>
                    <span>Present this ticket to concierge for delivery dispatch</span>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            onclick="window.print()" 
                            class="inline-flex items-center space-x-2 px-4 py-2 bg-stone-900 hover:bg-stone-800 text-gold-300 hover:text-white text-xs uppercase tracking-ultra font-semibold transition-colors rounded-xs shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Print / Save Ticket</span>
                    </button>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-4 py-2 border border-stone-300 hover:border-stone-800 text-stone-700 text-xs uppercase tracking-ultra font-medium transition-colors rounded-xs">
                        Home
                    </a>
                </div>
            </div>

            <!-- THE TICKET FORM CONTAINER -->
            <div class="bg-white border-2 border-stone-900/10 shadow-lg rounded-sm overflow-hidden relative print:border-stone-400 print:shadow-none">
                
                <!-- Ticket Top Header -->
                <div class="bg-noir-950 text-white p-6 sm:p-8 border-b-2 border-gold-500/60">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <span class="text-[10px] uppercase font-sans tracking-prestige text-gold-400 font-semibold block mb-1">
                                Haute Joaillerie &bull; Official Ticket
                            </span>
                            <span class="font-serif text-2xl sm:text-3xl tracking-wider uppercase text-white">
                                The Vaughn Atelier
                            </span>
                        </div>
                        <div class="sm:text-right">
                            <span class="text-[10px] uppercase tracking-ultra text-stone-400 block mb-0.5">Ticket Reference Number</span>
                            <span class="font-mono text-xl sm:text-2xl font-bold tracking-widest text-gold-300 block">
                                {{ $order->order_number }}
                            </span>
                            <span class="text-[11px] text-stone-400 font-mono mt-0.5 block">
                                Issued: {{ $order->created_at ? $order->created_at->format('M d, Y &bull; H:i T') : now()->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Prominent Delivery Action & Company Contact Notice -->
                <div class="p-6 sm:p-8 bg-amber-50/50 border-b border-amber-200/80">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-full bg-gold-600/10 text-gold-700 border border-gold-500/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 text-xs">
                            <h2 class="font-serif text-lg font-semibold text-stone-900 tracking-wide">
                                Delivery Dispatch & Concierge Instructions
                            </h2>
                            <p class="text-stone-700 mt-1 leading-relaxed">
                                Your gemstone selection has been reserved in our vault under Ticket Reference <strong class="text-stone-950 font-mono">{{ $order->order_number }}</strong>. To coordinate your insured courier delivery, bespoke handover, or private salon viewing, please contact our concierge by direct telephone or email quoting your ticket number below:
                            </p>

                            <!-- Company Telephone & Email Details -->
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 border-t border-amber-200/60">
                                <div class="bg-white p-3 border border-amber-200 rounded-sm">
                                    <span class="text-[10px] uppercase font-sans tracking-ultra text-stone-500 block font-semibold">Concierge Direct Telephone</span>
                                    <a href="tel:+18005558822" class="text-sm font-bold text-stone-900 hover:text-gold-700 transition-colors flex items-center space-x-1.5 mt-0.5 font-mono">
                                        <svg class="w-3.5 h-3.5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span>+1 (800) 555-8822</span>
                                    </a>
                                    <span class="text-[10px] text-stone-400 block mt-0.5">Mon&ndash;Sat, 9:00 AM &ndash; 7:00 PM EST</span>
                                </div>

                                <div class="bg-white p-3 border border-amber-200 rounded-sm">
                                    <span class="text-[10px] uppercase font-sans tracking-ultra text-stone-500 block font-semibold">Concierge Direct Email</span>
                                    <a href="mailto:salon@thevaughnatelier.com?subject=Delivery%20Arrangement%20for%20Ticket%20{{ $order->order_number }}" class="text-sm font-bold text-stone-900 hover:text-gold-700 transition-colors flex items-center space-x-1.5 mt-0.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate">salon@thevaughnatelier.com</span>
                                    </a>
                                    <span class="text-[10px] text-stone-400 block mt-0.5">Reference Ticket #{{ $order->order_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Details & Delivery Destination Grid -->
                <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-stone-200 text-xs">
                    <!-- Customer Information -->
                    <div>
                        <span class="text-[11px] uppercase tracking-ultra font-semibold text-stone-900 block pb-2 border-b border-stone-100">
                            Patron Information
                        </span>
                        <div class="mt-3 space-y-2 text-stone-700">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-stone-400 block">Client Name</span>
                                <span class="font-semibold text-stone-900 text-sm">{{ $order->customer_name }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-stone-400 block">Email Address</span>
                                <span class="font-medium text-stone-800">{{ $order->customer_email }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-stone-400 block">Telephone</span>
                                <span class="font-mono text-stone-800">{{ $order->customer_phone ?: 'On file with atelier' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Destination -->
                    <div>
                        <span class="text-[11px] uppercase tracking-ultra font-semibold text-stone-900 block pb-2 border-b border-stone-100">
                            Delivery Destination
                        </span>
                        <div class="mt-3 space-y-2 text-stone-700">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-stone-400 block">Courier Address</span>
                                <div class="font-medium text-stone-900 leading-relaxed">
                                    <p>{{ $order->shipping_address_line1 }}</p>
                                    @if($order->shipping_address_line2)
                                        <p>{{ $order->shipping_address_line2 }}</p>
                                    @endif
                                    <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }} {{ $order->shipping_postal_code }}</p>
                                    <p>{{ $order->shipping_country }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-stone-400 block">Selected Courier Tier</span>
                                <span class="font-semibold text-gold-700 uppercase tracking-wider">
                                    {{ $order->shippingMethod?->name ?? 'The Vaughn Atelier Armored Courier' }}
                                </span>
                                <span class="text-[11px] text-stone-400 block">
                                    Estimated transit: {{ $order->shippingMethod?->estimated_days ?? '2-4' }} Business Days
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchased Items Section -->
                <div class="p-6 sm:p-8 border-b border-stone-200">
                    <span class="text-[11px] uppercase tracking-ultra font-semibold text-stone-900 block pb-3 border-b border-stone-100">
                        Commissioned Vault Pieces ({{ $order->items->count() }})
                    </span>

                    <div class="divide-y divide-stone-100 mt-2">
                        @foreach($order->items as $item)
                            <div class="py-4 flex items-center justify-between space-x-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-ivory-100 flex-shrink-0 border border-stone-200 overflow-hidden rounded-xs">
                                        @if($item->product && $item->product->primaryImage)
                                            <img src="{{ $item->product->primaryImage->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gold-600 font-serif text-[10px] text-center px-1">
                                                TVA Vault
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-serif text-sm font-semibold text-stone-900">{{ $item->product_name }}</h3>
                                        <div class="text-[11px] text-stone-500 mt-0.5 space-x-2">
                                            @if($item->variant_details)
                                                <span class="text-stone-700 font-medium">{{ $item->variant_details }}</span>
                                                <span>&bull;</span>
                                            @elseif($item->product?->materials)
                                                <span class="text-stone-700 font-medium">{{ $item->product->materials }}</span>
                                                <span>&bull;</span>
                                            @endif
                                            <span>Qty: {{ $item->quantity }} &times; ${{ number_format($item->unit_price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-serif text-sm font-semibold text-stone-900 block">
                                        ${{ number_format($item->total_price, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pricing Breakdown -->
                    <div class="mt-6 pt-4 border-t border-stone-200 space-y-2 text-xs">
                        <div class="flex justify-between text-stone-600">
                            <span>Bag Subtotal</span>
                            <span class="font-serif text-stone-900 font-medium">${{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-800">
                                <span>Privilege Discount @if($order->coupon_code) ({{ $order->coupon_code }}) @endif</span>
                                <span class="font-serif font-medium">&minus;${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-stone-600">
                            <span>Insured Armored Courier</span>
                            <span class="font-medium text-emerald-700 uppercase tracking-wider">
                                {{ $order->shipping_fee == 0 ? 'Complimentary' : '$' . number_format($order->shipping_fee, 2) }}
                            </span>
                        </div>

                        <div class="pt-3 border-t border-stone-200 flex justify-between items-baseline">
                            <span class="text-xs uppercase tracking-ultra font-bold text-stone-900">Total Investment Value</span>
                            <span class="font-serif text-2xl font-bold text-stone-900">
                                ${{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Ticket Footer & Security Authentication -->
                <div class="p-6 sm:p-8 bg-stone-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
                    <div>
                        <p class="font-semibold text-stone-900 uppercase tracking-wider text-[11px]">The Vaughn Atelier Haute Joaillerie</p>
                        <p class="text-[11px] text-stone-500 mt-0.5">Flagship Salon: 742 Fifth Avenue, Atelier Suite 14, New York, NY 10019</p>
                    </div>
                    <div class="text-center sm:text-right">
                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 bg-white border border-stone-200 text-stone-800 text-[10px] uppercase tracking-ultra font-semibold rounded-xs">
                            <span class="w-2 h-2 rounded-full bg-gold-500"></span>
                            <span>Individual Vault Certificate Attached</span>
                        </span>
                    </div>
                </div>

            </div>

            <!-- Bottom Navigation Links (Screen Only) -->
            <div class="mt-8 text-center space-x-6 text-xs uppercase font-sans tracking-ultra print:hidden">
                <a href="{{ route('home') }}" class="text-stone-600 hover:text-gold-700 transition-colors font-medium">
                    &larr; Return to Home
                </a>
                <span class="text-stone-300">|</span>
                <a href="{{ route('catalog') }}" class="text-stone-600 hover:text-gold-700 transition-colors font-medium">
                    Explore Vault Creations &rarr;
                </a>
            </div>

        </div>
    </div>
</x-storefront-layout>
