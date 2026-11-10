<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs tracking-widest uppercase font-semibold text-amber-600">The Vaughn Atelier Haute Joaillerie</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Executive Atelier Dashboard</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 border border-stone-300 text-stone-700 hover:border-stone-900 rounded text-xs uppercase tracking-wider font-semibold">
                    Orders Console
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="px-3 py-1.5 bg-amber-700 hover:bg-amber-800 text-white rounded text-xs uppercase tracking-wider font-semibold shadow-sm">
                    Vault Inventory
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Key Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Net Gross Revenue</span>
                    <p class="text-3xl font-serif text-stone-900 mt-2 font-bold">${{ number_format($metrics['total_sales'], 2) }}</p>
                    <span class="text-xs text-emerald-700 mt-1 block font-medium">+${{ number_format($metrics['month_sales'], 2) }} this month</span>
                </div>

                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Total Orders</span>
                    <p class="text-3xl font-serif text-stone-900 mt-2 font-bold">{{ number_format($metrics['total_orders']) }}</p>
                    <span class="text-xs text-amber-700 mt-1 block font-medium">{{ $metrics['pending_orders'] }} awaiting fulfillment</span>
                </div>

                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">Vault Catalog</span>
                    <p class="text-3xl font-serif text-stone-900 mt-2 font-bold">{{ number_format($metrics['total_products']) }}</p>
                    <span class="text-xs text-stone-400 mt-1 block">{{ $categories->count() }} active collections</span>
                </div>

                <div class="bg-white p-6 rounded-lg border border-stone-200 shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-medium">VIP Patrons</span>
                    <p class="text-3xl font-serif text-stone-900 mt-2 font-bold">{{ number_format($metrics['total_customers']) }}</p>
                    <span class="text-xs text-stone-400 mt-1 block">Registered clientele</span>
                </div>
            </div>

            <!-- Main Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Recent Orders (8 Cols) -->
                <div class="lg:col-span-8 bg-white rounded-lg border border-stone-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <h3 class="font-serif text-lg text-stone-900">Recent Customer Commissions</h3>
                            <p class="text-xs text-stone-500">Live order management and dispatch status.</p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="text-xs uppercase tracking-wider font-semibold text-amber-700 hover:underline">
                            View All Orders &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs divide-y divide-stone-100">
                            <thead class="text-stone-400 uppercase text-[10px] tracking-wider font-semibold">
                                <tr>
                                    <th class="py-2.5">Order</th>
                                    <th class="py-2.5">Patron</th>
                                    <th class="py-2.5">Amount</th>
                                    <th class="py-2.5">Status</th>
                                    <th class="py-2.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-stone-50/50">
                                        <td class="py-3 font-mono font-bold text-stone-900">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-amber-700">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <span class="font-medium text-stone-800 block">{{ $order->customer_name }}</span>
                                            <span class="text-[11px] text-stone-400">{{ $order->customer_email }}</span>
                                        </td>
                                        <td class="py-3 font-serif font-semibold text-stone-900">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </td>
                                        <td class="py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider 
                                                @if($order->status === 'delivered') bg-emerald-100 text-emerald-800
                                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                                @else bg-amber-100 text-amber-800 @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-right">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-amber-700 hover:underline font-semibold text-[11px]">
                                                Dossier &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-stone-400">
                                            No recent orders recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: Low-Stock Alerts & Armored Courier (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Low-Stock Alert Widget -->
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                            <h4 class="font-serif text-base text-stone-900 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                                Low Vault Stock Alerts
                            </h4>
                            <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock']) }}" class="text-xs text-amber-700 hover:underline">
                                Manage &rarr;
                            </a>
                        </div>

                        <div class="divide-y divide-stone-100">
                            @forelse($lowStockProducts as $prod)
                                <div class="py-2.5 flex items-center justify-between text-xs">
                                    <div class="truncate pr-2">
                                        <span class="font-medium text-stone-900 block truncate">{{ $prod->name }}</span>
                                        <span class="text-stone-400 text-[10px] font-mono">{{ $prod->sku }}</span>
                                    </div>
                                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded {{ $prod->stock_quantity <= 0 ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $prod->stock_quantity }} left
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-stone-400 py-3">All jewelry pieces have sufficient vault reserves.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quick Navigation Cards -->
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm p-6 space-y-3">
                        <h4 class="font-serif text-base text-stone-900 pb-2 border-b border-stone-100">
                            Atelier Quick Consoles
                        </h4>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <a href="{{ route('admin.products.create') }}" class="p-3 bg-stone-50 border border-stone-200 rounded hover:border-amber-600 transition-colors text-center font-medium text-stone-800">
                                + New Piece
                            </a>
                            <a href="{{ route('admin.coupons.create') }}" class="p-3 bg-stone-50 border border-stone-200 rounded hover:border-amber-600 transition-colors text-center font-medium text-stone-800">
                                + Privilege Code
                            </a>
                            <a href="{{ route('admin.shipments.index') }}" class="p-3 bg-stone-50 border border-stone-200 rounded hover:border-amber-600 transition-colors text-center font-medium text-stone-800">
                                Armored Shipments
                            </a>
                            <a href="{{ route('admin.customers.index') }}" class="p-3 bg-stone-50 border border-stone-200 rounded hover:border-amber-600 transition-colors text-center font-medium text-stone-800">
                                Patron Directory
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
