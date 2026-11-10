<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-700 font-semibold">The Vaughn Atelier &bull; Atelier Marketing</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Promotional Invitation Codes</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.coupons.create') }}" class="px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded text-xs uppercase tracking-wider font-semibold shadow-sm">
                    + Register Privilege Code
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
                            <th class="px-6 py-3.5">Code</th>
                            <th class="px-6 py-3.5">Discount Tier</th>
                            <th class="px-6 py-3.5">Min Spend</th>
                            <th class="px-6 py-3.5">Claims Used</th>
                            <th class="px-6 py-3.5">Validity Window</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 font-sans">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-stone-50/60 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-stone-900 text-sm">
                                    {{ $coupon->code }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-stone-900">
                                    {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }} OFF
                                </td>
                                <td class="px-6 py-4 text-stone-600 font-mono">
                                    ${{ number_format($coupon->min_spend, 2) }}
                                </td>
                                <td class="px-6 py-4 text-stone-600 font-mono">
                                    {{ $coupon->times_used }} {{ $coupon->usage_limit ? '/ ' . $coupon->usage_limit : 'claimed' }}
                                </td>
                                <td class="px-6 py-4 text-stone-500 font-mono text-[11px]">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Indefinite' }}
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.coupons.toggle', $coupon->id) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider {{ $coupon->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-600' }}">
                                            {{ $coupon->is_active ? 'Active' : 'Deactivated' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-amber-700 hover:underline font-semibold">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                    No promotional invitation codes registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($coupons->hasPages())
                    <div class="p-4 border-t border-stone-200">
                        {{ $coupons->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

