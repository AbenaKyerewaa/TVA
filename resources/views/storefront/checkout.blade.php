<x-storefront-layout>
    <x-slot:title>
        White-Glove Checkout — The Vaughn Atelier Haute Joaillerie
    </x-slot:title>

    <div class="bg-stone-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 space-y-1">
                <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">The Vaughn Atelier</span>
                <h1 class="font-serif text-3xl sm:text-4xl text-stone-900 font-light">White-Glove Order Placement</h1>
            </div>

            @if($errors->any())
                <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-sm max-w-4xl mx-auto">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                @csrf

                <!-- Left Column: Checkout Stages (7 Cols) -->
                <div class="lg:col-span-7 space-y-8">
                    
                    <!-- 1. Contact Information -->
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-sm shadow-2xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                            <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900">
                                1. Patron Verification & Contact
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="customer_email" value="Email for Courier Notifications" />
                                <x-text-input id="customer_email" name="customer_email" type="email" class="mt-1 block w-full" :value="old('customer_email', $user?->email)" required />
                                <x-input-error class="mt-1" :messages="$errors->get('customer_email')" />
                            </div>

                            <div>
                                <x-input-label for="customer_phone" value="Direct Telephone (For Courier Signature)" />
                                <x-text-input id="customer_phone" name="customer_phone" type="text" class="mt-1 block w-full" :value="old('customer_phone', $user?->phone)" placeholder="+1 (555) 000-0000" required />
                                <x-input-error class="mt-1" :messages="$errors->get('customer_phone')" />
                            </div>
                        </div>
                    </div>

                    <!-- 2. Delivery Destination -->
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-sm shadow-2xs space-y-4">
                        <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 block pb-3 border-b border-stone-100">
                            2. Delivery Destination
                        </span>

                        @if($savedAddresses->isNotEmpty())
                            <div class="mb-4">
                                <span class="text-xs text-stone-600 block mb-2 font-medium">Quick-Select From Your Private Address Book:</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-data="{ selectedAddr: '{{ $savedAddresses->first()->id }}' }">
                                    @foreach($savedAddresses as $addr)
                                        <div class="p-3 border rounded-sm text-xs cursor-pointer transition-colors"
                                             :class="selectedAddr === '{{ $addr->id }}' ? 'border-gold-600 bg-gold-50/30' : 'border-stone-200 bg-white'"
                                             @click="selectedAddr = '{{ $addr->id }}'; document.getElementById('full_name').value = '{{ $addr->full_name }}'; document.getElementById('address_line_1').value = '{{ $addr->address_line_1 }}'; document.getElementById('address_line_2').value = '{{ $addr->address_line_2 }}'; document.getElementById('city').value = '{{ $addr->city }}'; document.getElementById('state').value = '{{ $addr->state }}'; document.getElementById('postal_code').value = '{{ $addr->postal_code }}';">
                                            <p class="font-semibold text-stone-900">{{ $addr->full_name }}</p>
                                            <p class="text-stone-500 truncate">{{ $addr->address_line_1 }}</p>
                                            <p class="text-stone-500">{{ $addr->city }}, {{ $addr->postal_code }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <x-input-label for="full_name" value="Recipient Full Name" />
                                <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $savedAddresses->first()?->full_name ?? $user?->name)" required />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="address_line_1" value="Street Address" />
                                <x-text-input id="address_line_1" name="address_line_1" type="text" class="mt-1 block w-full" :value="old('address_line_1', $savedAddresses->first()?->address_line_1)" placeholder="e.g. 742 Evergreen Terrace" required />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="address_line_2" value="Apartment, Suite, Floor (Optional)" />
                                <x-text-input id="address_line_2" name="address_line_2" type="text" class="mt-1 block w-full" :value="old('address_line_2', $savedAddresses->first()?->address_line_2)" placeholder="Suite 400" />
                            </div>

                            <div>
                                <x-input-label for="city" value="City" />
                                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $savedAddresses->first()?->city)" required />
                            </div>

                            <div>
                                <x-input-label for="state" value="State / Region" />
                                <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $savedAddresses->first()?->state)" />
                            </div>

                            <div>
                                <x-input-label for="postal_code" value="Postal / Zip Code" />
                                <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code', $savedAddresses->first()?->postal_code)" required />
                            </div>

                            <div>
                                <x-input-label for="country" value="Country" />
                                <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $savedAddresses->first()?->country ?? 'United States')" required />
                            </div>

                            @auth
                                <div class="sm:col-span-2 flex items-center space-x-2 pt-1">
                                    <input type="checkbox" id="save_address" name="save_address" value="1" class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                                    <label for="save_address" class="text-xs text-stone-700">Save to my private address book</label>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- 3. Shipping Tier Selection -->
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-sm shadow-2xs space-y-4">
                        <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 block pb-3 border-b border-stone-100">
                            3. Select White-Glove Courier Service
                        </span>

                        <div class="space-y-3">
                            @foreach($shippingMethods as $method)
                                <label class="flex items-start justify-between p-4 border rounded-sm cursor-pointer hover:border-gold-500 transition-colors bg-stone-50/50">
                                    <div class="flex items-start space-x-3">
                                        <input type="radio" 
                                               name="shipping_method_id" 
                                               value="{{ $method->id }}" 
                                               {{ (old('shipping_method_id', $shippingMethods->first()->id) == $method->id) ? 'checked' : '' }}
                                               class="mt-1 text-gold-600 focus:ring-gold-500">
                                        <div>
                                            <span class="text-xs font-semibold text-stone-900 uppercase tracking-wider block">
                                                {{ $method->name }}
                                            </span>
                                            <p class="text-xs text-stone-500 mt-0.5">{{ $method->description }}</p>
                                            <span class="text-[10px] uppercase tracking-ultra text-gold-700 font-medium mt-1 inline-block">
                                                Estimated: {{ $method->estimated_days }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="font-serif text-xs font-semibold text-stone-900">
                                        {{ $method->fee == 0 ? 'Complimentary' : '$' . number_format($method->fee, 2) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 4. Payment Method -->
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-sm shadow-2xs space-y-4">
                        <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 block pb-3 border-b border-stone-100">
                            4. Payment Method
                        </span>

                        <div class="space-y-3">
                            <label class="flex items-center justify-between p-4 border rounded-sm cursor-pointer hover:border-gold-500 transition-colors bg-white">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="bank_transfer" checked class="text-gold-600 focus:ring-gold-500">
                                    <div>
                                        <span class="text-xs font-semibold text-stone-900 uppercase tracking-wider block">Atelier Private Wire Transfer</span>
                                        <span class="text-xs text-stone-500">Direct wire details provided for vault authorization</span>
                                    </div>
                                </div>
                                <span class="text-xs text-stone-400 font-mono">Wire</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Order Summary (5 Cols) -->
                <div class="lg:col-span-5 bg-white border border-stone-200 p-6 sm:p-8 rounded-sm shadow-sm space-y-6 sticky top-24">
                    <h2 class="font-serif text-xl text-stone-900 pb-3 border-b border-stone-100">Creation Summary</h2>

                    <!-- Items Preview -->
                    <div class="divide-y divide-stone-100 max-h-72 overflow-y-auto pr-1">
                        @foreach($cart->items as $item)
                            <div class="py-3 flex items-center justify-between space-x-3 text-xs">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded bg-ivory-100 border border-stone-200 overflow-hidden flex-shrink-0">
                                        <img src="{{ $item->product->primaryImage?->image_url ?? $item->product->primaryImage?->image_path ?? asset('images/products/aurelia-solitaire-diamond-ring.jpg') }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-medium text-stone-900 block">{{ $item->product->name }}</span>
                                        <span class="text-stone-400 text-[11px]">Qty: {{ $item->quantity }} &bull; {{ $item->variant?->name ?? $item->product->materials }}</span>
                                    </div>
                                </div>
                                <span class="font-serif font-medium text-stone-900">${{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Financial Breakdown -->
                    <div class="space-y-3 pt-4 border-t border-stone-100 text-xs">
                        <div class="flex justify-between text-stone-600">
                            <span>Bag Subtotal</span>
                            <span class="font-serif text-stone-900 font-medium">${{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($appliedCoupon)
                            <div class="flex justify-between text-emerald-800 bg-emerald-50 p-2 rounded-xs">
                                <span>Invitation Code ({{ $appliedCoupon->code }})</span>
                                <span class="font-serif font-medium">&minus;${{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-stone-600">
                            <span>Insured Armored Delivery</span>
                            <span class="text-emerald-700 font-medium uppercase tracking-wider">
                                {{ $shippingFee == 0 ? 'Complimentary' : '$' . number_format($shippingFee, 2) }}
                            </span>
                        </div>

                        <div class="pt-4 border-t border-stone-200 flex justify-between items-baseline">
                            <span class="font-serif text-base text-stone-900">Total Investment</span>
                            <span class="font-serif text-2xl font-semibold text-stone-900">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <x-luxury.button variant="gold" size="lg" type="submit" class="w-full text-center">
                            Continue
                        </x-luxury.button>
                    </div>

                    <p class="text-[11px] text-stone-400 text-center leading-relaxed">
                        By placing your order, you authorize The Vaughn Atelier to reserve certified gemstones from the vault and initiate white-glove security verification.
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-storefront-layout>

