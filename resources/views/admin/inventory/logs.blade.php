<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.inventory.index') }}" class="text-xs uppercase tracking-wider text-stone-500 hover:text-amber-700 font-semibold mb-1 inline-block">
                    &larr; Back To Inventory Control
                </a>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Vault Inventory Audit Trail</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Toolbar -->
            <div class="bg-white p-4 border border-stone-200 rounded-lg shadow-2xs flex flex-wrap gap-3 items-center justify-between">
                <div class="flex flex-wrap gap-2 text-xs">
                    <a href="{{ route('admin.inventory.logs') }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ !request('change_type') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        All Events
                    </a>
                    <a href="{{ route('admin.inventory.logs', ['change_type' => 'sale']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('change_type') === 'sale' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Client Sales
                    </a>
                    <a href="{{ route('admin.inventory.logs', ['change_type' => 'adjustment']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('change_type') === 'adjustment' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Manual Adjustments
                    </a>
                    <a href="{{ route('admin.inventory.logs', ['change_type' => 'return']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('change_type') === 'return' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Returns & Restocks
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.inventory.logs') }}" class="flex items-center space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Piece or SKU..." class="text-xs border-stone-200 rounded px-3 py-1.5 focus:border-amber-500 focus:ring-0">
                    <button type="submit" class="px-3 py-1.5 bg-stone-800 text-white text-xs uppercase tracking-wider rounded hover:bg-stone-700">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.inventory.logs') }}" class="text-xs text-rose-600 hover:underline">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Audit Trail Table -->
            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs divide-y divide-stone-200">
                        <thead class="bg-stone-50 font-semibold text-stone-700 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-6 py-3.5">Timestamp</th>
                                <th class="px-6 py-3.5">Jewelry Piece</th>
                                <th class="px-6 py-3.5">SKU</th>
                                <th class="px-6 py-3.5">Change Type</th>
                                <th class="px-6 py-3.5 text-center">Delta Qty</th>
                                <th class="px-6 py-3.5 text-center">Stock Before &rarr; After</th>
                                <th class="px-6 py-3.5">Audit Reason / Reference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 font-sans">
                            @forelse($logs as $log)
                                <tr class="hover:bg-stone-50/60 transition-colors">
                                    <td class="px-6 py-4 font-mono text-[11px] text-stone-500 whitespace-nowrap">
                                        {{ $log->created_at->format('M d, Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-stone-900">
                                        {{ $log->product->name ?? 'Vault Item' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-stone-500 text-[11px]">
                                        {{ $log->product->sku ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider 
                                            @if($log->change_type === 'sale') bg-rose-100 text-rose-800
                                            @elseif($log->change_type === 'cancellation_restock') bg-emerald-100 text-emerald-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ str_replace('_', ' ', $log->change_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono font-bold text-xs {{ $log->quantity < 0 ? 'text-rose-600' : 'text-emerald-700' }}">
                                        {{ $log->quantity > 0 ? '+' . $log->quantity : $log->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono text-stone-600">
                                        {{ $log->previous_stock }} &rarr; <span class="font-bold text-stone-900">{{ $log->current_stock }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-stone-600 text-[11px]">
                                        {{ $log->notes }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                        No inventory audit logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="p-4 border-t border-stone-200">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
