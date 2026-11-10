<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-700 font-semibold">The Vaughn Atelier &bull; Atelier Logistics</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Courier Service Tiers</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.shipments.index') }}" class="px-4 py-2 border border-stone-300 text-stone-700 hover:border-stone-900 rounded text-xs uppercase tracking-wider font-semibold">
                    View Live Shipments &rarr;
                </a>
                <a href="{{ route('admin.shipping-methods.create') }}" class="px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded text-xs uppercase tracking-wider font-semibold shadow-sm">
                    + Add Courier Tier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
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
                <table class="w-full text-left text-xs divide-y divide-stone-200">
                    <thead class="bg-stone-50 font-semibold text-stone-700 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-3.5">Courier Tier</th>
                            <th class="px-6 py-3.5">Code</th>
                            <th class="px-6 py-3.5">Fee</th>
                            <th class="px-6 py-3.5">Estimated Duration</th>
                            <th class="px-6 py-3.5">Acquisitions Serviced</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($methods as $method)
                            <tr class="hover:bg-stone-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-stone-900 block text-sm">{{ $method->name }}</span>
                                    <span class="text-stone-500 text-[11px]">{{ $method->description }}</span>
                                </td>
                                <td class="px-6 py-4 font-mono text-stone-500 text-[11px]">
                                    {{ $method->code }}
                                </td>
                                <td class="px-6 py-4 font-serif font-semibold text-sm text-stone-900">
                                    {{ $method->fee == 0 ? 'Complimentary' : '$' . number_format($method->fee, 2) }}
                                </td>
                                <td class="px-6 py-4 text-stone-600 font-medium">
                                    {{ $method->estimated_days }}
                                </td>
                                <td class="px-6 py-4 text-stone-600 font-mono">
                                    {{ $method->orders_count }} orders
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.shipping-methods.toggle', $method->id) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider {{ $method->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-600' }}">
                                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.shipping-methods.edit', $method->id) }}" class="text-amber-700 hover:underline font-semibold">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                    No shipping courier tiers registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>

