<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-700 font-semibold">The Vaughn Atelier &bull; Atelier CRM</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Distinguished Patron Directory</h2>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="bg-amber-100 text-amber-900 px-3 py-1 rounded-full font-medium border border-amber-200">
                    {{ number_format($metrics['total_customers']) }} Registered Patrons
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Metrics Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Total Patrons</span>
                    <p class="text-3xl font-serif text-stone-900 mt-1 font-bold">{{ number_format($metrics['total_customers']) }}</p>
                    <span class="text-[11px] text-stone-400">Registered client accounts</span>
                </div>
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-amber-700 font-medium">VIP Circle Patrons</span>
                    <p class="text-3xl font-serif text-amber-800 mt-1 font-bold">{{ number_format($metrics['vip_clients']) }}</p>
                    <span class="text-[11px] text-stone-400">Gold, Platinum & Diamond members</span>
                </div>
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-2xs">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Verified Patron Acquisitions</span>
                    <p class="text-3xl font-serif text-stone-900 mt-1 font-bold">${{ number_format($metrics['total_patron_spend'], 2) }}</p>
                    <span class="text-[11px] text-stone-400">Cumulative settled volume</span>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 border border-stone-200 rounded-lg shadow-2xs flex flex-wrap gap-3 items-center justify-between">
                <div class="flex flex-wrap gap-2 text-xs">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ !request('tier') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        All Patrons
                    </a>
                    <a href="{{ route('admin.customers.index', ['tier' => 'diamond']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('tier') === 'diamond' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Diamond Tier
                    </a>
                    <a href="{{ route('admin.customers.index', ['tier' => 'platinum']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('tier') === 'platinum' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Platinum Tier
                    </a>
                    <a href="{{ route('admin.customers.index', ['tier' => 'gold']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('tier') === 'gold' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Gold Tier
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Patron name, email..." class="text-xs border-stone-200 rounded px-3 py-1.5 focus:border-amber-500 focus:ring-0">
                    <button type="submit" class="px-3 py-1.5 bg-stone-800 text-white text-xs uppercase tracking-wider rounded hover:bg-stone-700">Search</button>
                    @if(request('search') || request('tier'))
                        <a href="{{ route('admin.customers.index') }}" class="text-xs text-rose-600 hover:underline">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Customers Table -->
            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs divide-y divide-stone-200">
                        <thead class="bg-stone-50 font-semibold text-stone-700 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-6 py-3.5">Patron</th>
                                <th class="px-6 py-3.5">VIP Tier</th>
                                <th class="px-6 py-3.5">Telephone</th>
                                <th class="px-6 py-3.5">Acquisitions</th>
                                <th class="px-6 py-3.5">Lifetime Valuation</th>
                                <th class="px-6 py-3.5">Member Since</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 font-sans">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-stone-50/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="font-bold text-stone-900 hover:text-amber-700 block">
                                            {{ $customer->name }}
                                        </a>
                                        <span class="text-stone-400 text-[11px]">{{ $customer->email }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                                            @if(($customer->customerProfile->vip_tier ?? '') === 'diamond') bg-sky-100 text-sky-800 border border-sky-300
                                            @elseif(($customer->customerProfile->vip_tier ?? '') === 'platinum') bg-stone-100 text-stone-800 border border-stone-300
                                            @else bg-amber-100 text-amber-900 border border-amber-300 @endif">
                                            {{ ucfirst($customer->customerProfile->vip_tier ?? 'Standard') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-stone-500 text-[11px]">
                                        {{ $customer->phone ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-stone-900">
                                        {{ $customer->orders_count }} orders
                                    </td>
                                    <td class="px-6 py-4 font-serif font-bold text-sm text-stone-900">
                                        ${{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-stone-500 text-[11px]">
                                        {{ $customer->created_at->format('M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="px-3 py-1 bg-stone-900 hover:bg-black text-white rounded text-[11px] uppercase tracking-wider font-medium">
                                            Dossier
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                        No patrons found matching criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($customers->hasPages())
                    <div class="p-4 border-t border-stone-200">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

