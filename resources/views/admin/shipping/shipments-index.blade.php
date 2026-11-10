<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-700 font-semibold">The Vaughn Atelier &bull; Atelier Logistics</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Armored Courier Dispatches & Shipments</h2>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <a href="{{ route('admin.shipping-methods.index') }}" class="px-4 py-2 border border-stone-300 text-stone-700 hover:border-stone-900 rounded uppercase tracking-wider font-semibold">
                    Manage Courier Tiers
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

            <!-- Status Tabs -->
            <div class="bg-white p-4 border border-stone-200 rounded-lg shadow-2xs flex flex-wrap gap-2 items-center justify-between">
                <div class="flex flex-wrap gap-1 text-xs">
                    <a href="{{ route('admin.shipments.index') }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ !request('status') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        All ({{ $metrics['all'] }})
                    </a>
                    <a href="{{ route('admin.shipments.index', ['status' => 'preparing']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('status') === 'preparing' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Preparing Vault ({{ $metrics['preparing'] }})
                    </a>
                    <a href="{{ route('admin.shipments.index', ['status' => 'in_transit']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('status') === 'in_transit' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        In Transit ({{ $metrics['in_transit'] }})
                    </a>
                    <a href="{{ route('admin.shipments.index', ['status' => 'out_for_delivery']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('status') === 'out_for_delivery' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Out for Delivery ({{ $metrics['out_for_delivery'] }})
                    </a>
                    <a href="{{ route('admin.shipments.index', ['status' => 'delivered']) }}" 
                       class="px-3 py-1.5 rounded uppercase tracking-wider font-medium {{ request('status') === 'delivered' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Delivered ({{ $metrics['delivered'] }})
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.shipments.index') }}" class="flex items-center space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tracking #, order, patron..." class="text-xs border-stone-200 rounded px-3 py-1.5 focus:border-amber-500 focus:ring-0">
                    <button type="submit" class="px-3 py-1.5 bg-stone-800 text-white text-xs uppercase tracking-wider rounded hover:bg-stone-700">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.shipments.index') }}" class="text-xs text-rose-600 hover:underline">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Shipments Table -->
            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs divide-y divide-stone-200">
                        <thead class="bg-stone-50 font-semibold text-stone-700 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-6 py-3.5">Tracking Number</th>
                                <th class="px-6 py-3.5">Order Reference</th>
                                <th class="px-6 py-3.5">Patron</th>
                                <th class="px-6 py-3.5">Security Carrier</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5">Dispatched On</th>
                                <th class="px-6 py-3.5 text-right">Update Dispatch</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 font-sans">
                            @forelse($shipments as $shipment)
                                <tr class="hover:bg-stone-50/60 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-stone-900">
                                        <a href="{{ route('order.tracking', ['tracking' => $shipment->tracking_number]) }}" target="_blank" class="hover:text-amber-700">
                                            {{ $shipment->tracking_number }} &nearr;
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-stone-600">
                                        <a href="{{ route('admin.orders.show', $shipment->order->id) }}" class="hover:underline font-semibold">
                                            {{ $shipment->order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-stone-900 block">{{ $shipment->order->customer_name }}</span>
                                        <span class="text-stone-400 text-[11px]">{{ $shipment->order->shipping_city }}, {{ $shipment->order->shipping_country }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-stone-700 font-medium">
                                        {{ $shipment->carrier }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider 
                                            @if($shipment->status === 'delivered') bg-emerald-100 text-emerald-800
                                            @elseif($shipment->status === 'in_transit') bg-blue-100 text-blue-800
                                            @elseif($shipment->status === 'out_for_delivery') bg-purple-100 text-purple-800
                                            @else bg-amber-100 text-amber-800 @endif">
                                            {{ str_replace('_', ' ', $shipment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-stone-500 font-mono text-[11px]">
                                        {{ $shipment->dispatched_at ? $shipment->dispatched_at->format('M d, Y H:i') : 'Pending Vault Release' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <!-- Inline status update form -->
                                        <form method="POST" action="{{ route('admin.shipments.update', $shipment->id) }}" class="inline-flex items-center space-x-1 justify-end">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="carrier" value="{{ $shipment->carrier }}">
                                            <input type="hidden" name="tracking_number" value="{{ $shipment->tracking_number }}">
                                            <select name="status" class="text-[11px] border-stone-200 rounded py-1 px-2 focus:ring-0">
                                                <option value="preparing" {{ $shipment->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                <option value="in_transit" {{ $shipment->status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                                <option value="out_for_delivery" {{ $shipment->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                                <option value="delivered" {{ $shipment->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            </select>
                                            <button type="submit" class="px-2.5 py-1 bg-stone-900 hover:bg-black text-white rounded text-[10px] uppercase tracking-wider font-semibold">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                        No active shipments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($shipments->hasPages())
                    <div class="p-4 border-t border-stone-200">
                        {{ $shipments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

