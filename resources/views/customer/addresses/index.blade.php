<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs uppercase tracking-prestige text-amber-700 font-semibold">Client Portfolio</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Private Address Book</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs uppercase tracking-wider text-stone-600 hover:text-stone-900 underline">
                &larr; Back to Client Portal
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Existing Addresses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($addresses as $address)
                    <div class="bg-white p-6 border {{ $address->is_default ? 'border-amber-400 ring-1 ring-amber-400' : 'border-stone-200' }} rounded-sm shadow-xs relative">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs uppercase tracking-wider font-semibold text-stone-900">
                                {{ ucfirst($address->type) }} Address
                            </span>
                            @if($address->is_default)
                                <span class="px-2 py-0.5 text-[10px] uppercase font-sans tracking-widest bg-amber-100 text-amber-900 rounded-full font-semibold">
                                    Default
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-stone-900">{{ $address->full_name }}</p>
                        <p class="text-xs text-stone-600 mt-1">{{ $address->address_line_1 }}</p>
                        @if($address->address_line_2)
                            <p class="text-xs text-stone-600">{{ $address->address_line_2 }}</p>
                        @endif
                        <p class="text-xs text-stone-600">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                        <p class="text-xs text-stone-600">{{ $address->country }}</p>
                        <p class="text-xs text-stone-500 mt-2 font-mono">Tel: {{ $address->phone }}</p>

                        <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-end">
                            <form method="POST" action="{{ route('customer.addresses.destroy', $address) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 focus:outline-none">
                                    Remove Address
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 p-8 text-center bg-white border border-stone-200 text-stone-400 text-xs">
                        No saved delivery destinations in your address book yet.
                    </div>
                @endforelse
            </div>

            <!-- Add New Address Card -->
            <div class="bg-white p-8 border border-stone-200 rounded-sm shadow-sm">
                <h3 class="font-serif text-xl text-stone-900 mb-1">Add New Delivery Destination</h3>
                <p class="text-xs text-stone-500 mb-6">Discreet white-glove courier delivery requires a verified physical address with telephone confirmation.</p>

                <form method="POST" action="{{ route('customer.addresses.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="full_name" value="Recipient Full Name" />
                            <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Recipient Contact Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="address_line_1" value="Street Address" />
                            <x-text-input id="address_line_1" name="address_line_1" type="text" class="mt-1 block w-full" placeholder="e.g. 742 Evergreen Terrace" required />
                            <x-input-error class="mt-2" :messages="$errors->get('address_line_1')" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="address_line_2" value="Apartment, Suite, Unit (Optional)" />
                            <x-text-input id="address_line_2" name="address_line_2" type="text" class="mt-1 block w-full" placeholder="Suite 400" />
                        </div>

                        <div>
                            <x-input-label for="city" value="City" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="state" value="State / Province / Region" />
                            <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" />
                        </div>

                        <div>
                            <x-input-label for="postal_code" value="Postal / Zip Code" />
                            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="country" value="Country" />
                            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" value="United States" required />
                        </div>

                        <div class="md:col-span-2 flex items-center space-x-2 pt-2">
                            <input type="checkbox" id="is_default" name="is_default" value="1" class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <label for="is_default" class="text-xs text-stone-700">Set as my default shipping address</label>
                            <input type="hidden" name="type" value="shipping">
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-stone-100">
                        <x-primary-button>
                            Save Destination
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

