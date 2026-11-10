<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.customers.index') }}" class="text-xs uppercase tracking-wider text-stone-500 hover:text-amber-700 font-semibold mb-1 inline-block">
                    &larr; Back To Patron Directory
                </a>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">
                    Patron Confidential Dossier: <span class="font-bold">{{ $customer->name }}</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Patron Overview Banner -->
            <div class="bg-noir-950 text-white p-6 sm:p-8 rounded-lg shadow-sm border border-stone-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-gold-500/20 text-gold-300 border border-gold-400/40">
                            {{ ucfirst($customer->customerProfile->vip_tier ?? 'Gold') }} Circle Patron
                        </span>
                        <span class="text-xs text-stone-400">&bull; Client Since {{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="font-serif text-2xl font-light text-white">{{ $customer->name }}</h3>
                    <p class="text-xs text-stone-400 mt-1">{{ $customer->email }} &bull; {{ $customer->phone ?? 'Telephone on file' }}</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 block">Total Lifetime Portfolio</span>
                    <span class="font-serif text-2xl font-bold text-gold-300">${{ number_format($lifetimeSpend, 2) }}</span>
                    <span class="text-[11px] text-stone-400 block mt-0.5">{{ $customer->orders->count() }} total commissions</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Orders History (8 Cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-lg shadow-sm">
                        <h3 class="font-serif text-lg text-stone-900 pb-3 border-b border-stone-100 mb-4">
                            Acquisition History ({{ $customer->orders->count() }})
                        </h3>

                        <div class="divide-y divide-stone-100">
                            @forelse($customer->orders as $order)
                                <div class="py-4 flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono text-xs font-bold text-stone-900 hover:text-amber-700">
                                            {{ $order->order_number }}
                                        </a>
                                        <p class="text-[11px] text-stone-500 mt-0.5">
                                            {{ $order->created_at->format('M d, Y') }} &bull; {{ $order->items->count() }} item(s)
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="font-serif font-bold text-sm text-stone-900">${{ number_format($order->total_amount, 2) }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider 
                                            @if($order->status === 'delivered') bg-emerald-100 text-emerald-800
                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                            @else bg-amber-100 text-amber-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-amber-700 hover:underline font-semibold text-xs">
                                            Dossier &rarr;
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-stone-400 py-4">No historical orders recorded for this patron.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right: Profile Preferences & Addresses (4 Cols) -->
                <div class="lg:col-span-4 space-y-6 text-xs">
                    
                    <!-- Sizing & Preferences -->
                    <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm space-y-3">
                        <h4 class="font-serif text-base text-stone-900 pb-2 border-b border-stone-100">
                            Bespoke Sizing & Tastes
                        </h4>
                        <p><span class="text-stone-400">Ring Size:</span> <span class="font-bold text-stone-900">{{ $customer->customerProfile?->ring_size ?? 'Not specified' }}</span></p>
                        <p><span class="text-stone-400">Preferred Metal:</span> <span class="font-bold text-stone-900">{{ $customer->customerProfile?->preferred_metal ?? '18K Yellow Gold' }}</span></p>
                        <p><span class="text-stone-400">Anniversary Date:</span> <span class="text-stone-700">{{ $customer->customerProfile?->anniversary_date ? $customer->customerProfile->anniversary_date->format('F d') : 'None on file' }}</span></p>
                        <p><span class="text-stone-400">Birth Date:</span> <span class="text-stone-700">{{ $customer->customerProfile?->birth_date ? $customer->customerProfile->birth_date->format('F d') : 'None on file' }}</span></p>
                    </div>

                    <!-- Registered Addresses -->
                    <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm space-y-3">
                        <h4 class="font-serif text-base text-stone-900 pb-2 border-b border-stone-100">
                            Registered Delivery Salons
                        </h4>
                        @forelse($customer->addresses as $address)
                            <div class="p-3 bg-stone-50 rounded border border-stone-200 leading-relaxed text-stone-700">
                                <p class="font-bold text-stone-900">{{ $address->full_name }}</p>
                                <p>{{ $address->address_line_1 }}</p>
                                <p>{{ $address->city }}, {{ $address->postal_code }}</p>
                                <p>{{ $address->country }}</p>
                            </div>
                        @empty
                            <p class="text-stone-400">No saved addresses on file.</p>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
