<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-700 font-semibold">The Vaughn Atelier &bull; Atelier Admin</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Client Order Management</h2>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="bg-amber-100 text-amber-900 px-3 py-1 rounded-full font-medium border border-amber-200">
                    {{ number_format($statusCounts['all']) }} Total Orders Recorded
                </span>
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

            <!-- Status Filter Tabs -->
            <div class="bg-white p-4 border border-stone-200 rounded-lg shadow-2xs flex flex-wrap gap-2 items-center justify-between">
                <div class="flex flex-wrap gap-1">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="px-3 py-1.5 text-xs uppercase tracking-wider rounded-md font-medium transition-colors {{ !request('status') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        All ({{ $statusCounts['all'] }})
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                       class="px-3 py-1.5 text-xs uppercase tracking-wider rounded-md font-medium transition-colors {{ request('status') === 'pending' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Pending ({{ $statusCounts['pending'] }})
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" 
                       class="px-3 py-1.5 text-xs uppercase tracking-wider rounded-md font-medium transition-colors {{ request('status') === 'paid' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Paid ({{ $statusCounts['paid'] }})
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
                       class="px-3 py-1.5 text-xs uppercase tracking-wider rounded-md font-medium transition-colors {{ request('status') === 'processing' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Processing ({{ $statusCounts['processing'] }})
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
                       class="px-3 py-1.5 text-xs uppercase tracking-wider rounded-md font-medium transition-colors {{ request('status') === 'shipped' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Shipped ({{ $statusCounts['shipped'] }})
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" 
                       class="px-3 py-1.5 text-xs uppercase tracking-wider rounded-md font-medium transition-colors {{ request('status') === 'delivered' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Delivered ({{ $statusCounts['delivered'] }})
                    </a>
                </div>

                <!-- Search form -->
                <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center space-x-2">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, email, patron..." class="text-xs border-stone-200 rounded px-3 py-1.5 focus:border-amber-500 focus:ring-0">
                    <button type="submit" class="px-3 py-1.5 bg-stone-800 text-white text-xs uppercase tracking-wider rounded hover:bg-stone-700">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" class="text-xs text-rose-600 hover:underline">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs divide-y divide-stone-200">
                        <thead class="bg-stone-50 font-semibold text-stone-700 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-6 py-3.5">Reference</th>
                                <th class="px-6 py-3.5">Patron</th>
                                <th class="px-6 py-3.5">Creations</th>
                                <th class="px-6 py-3.5">Settlement Total</th>
                                <th class="px-6 py-3.5">Payment</th>
                                <th class="px-6 py-3.5">Fulfillment Status</th>
                                <th class="px-6 py-3.5">Commissioned On</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-stone-50/60 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-stone-900">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-amber-700">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-stone-900 block">{{ $order->customer_name }}</span>
                                        <span class="text-stone-400 text-[11px]">{{ $order->customer_email }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-stone-600">
                                        {{ $order->items->count() }} piece(s)
                                    </td>
                                    <td class="px-6 py-4 font-serif font-semibold text-sm text-stone-900">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider 
                                            @if($order->status === 'delivered') bg-emerald-100 text-emerald-800
                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'cancelled') bg-rose-100 text-rose-800
                                            @else bg-amber-100 text-amber-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-stone-500 font-mono text-[11px]">
                                        {{ $order->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1 bg-stone-900 hover:bg-black text-white text-[11px] uppercase tracking-wider rounded font-medium">
                                            Dossier
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-stone-400">
                                        No orders found matching the criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="p-4 border-t border-stone-200">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

