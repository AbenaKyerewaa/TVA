<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-700 font-semibold">The Vaughn Atelier &bull; Marketing & Growth</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Collector's Circle Subscribers</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.subscribers.export') }}" class="px-4 py-2 bg-stone-900 hover:bg-black text-gold-300 rounded text-xs uppercase tracking-wider font-semibold shadow-sm flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Metrics Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-stone-500 font-semibold block">Total Subscribers</span>
                    <span class="font-serif text-3xl text-stone-900 font-normal mt-2 block">{{ number_format($totalSubscribers) }}</span>
                </div>
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-emerald-700 font-semibold block">Active Collectors</span>
                    <span class="font-serif text-3xl text-emerald-800 font-normal mt-2 block">{{ number_format($activeSubscribers) }}</span>
                </div>
                <div class="bg-white p-6 border border-stone-200 rounded-lg shadow-sm">
                    <span class="text-xs uppercase tracking-wider text-stone-400 font-semibold block">Retention Rate</span>
                    <span class="font-serif text-3xl text-stone-900 font-normal mt-2 block">
                        {{ $totalSubscribers > 0 ? round(($activeSubscribers / $totalSubscribers) * 100, 1) : 100 }}%
                    </span>
                </div>
            </div>

            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-md flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Search and Filter Bar -->
            <div class="bg-white p-4 border border-stone-200 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('admin.subscribers.index') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="flex-1 w-full flex items-center space-x-3">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search subscriber email address..." 
                               class="w-full text-xs rounded border-stone-300 focus:border-amber-600 focus:ring-amber-600">
                        <select name="status" class="text-xs rounded border-stone-300 focus:border-amber-600 focus:ring-amber-600">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="unsubscribed" {{ request('status') === 'unsubscribed' ? 'selected' : '' }}>Unsubscribed Only</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2 w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-stone-800 text-stone-100 rounded text-xs uppercase tracking-wider font-semibold hover:bg-stone-900">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.subscribers.index') }}" class="px-3 py-2 text-stone-500 hover:text-stone-800 text-xs font-semibold">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table of Subscribers -->
            <div class="bg-white border border-stone-200 rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-left text-xs divide-y divide-stone-200">
                    <thead class="bg-stone-50 font-semibold text-stone-700 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-3.5">Subscriber Email</th>
                            <th class="px-6 py-3.5">Origin Channel</th>
                            <th class="px-6 py-3.5">Joined Date</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 font-sans">
                        @forelse($subscribers as $subscriber)
                            <tr class="hover:bg-stone-50/60 transition-colors">
                                <td class="px-6 py-4 font-medium text-stone-900 text-sm">
                                    {{ $subscriber->email }}
                                </td>
                                <td class="px-6 py-4 text-stone-600 uppercase tracking-wider text-[11px]">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-700">
                                        {{ $subscriber->source ?? 'Storefront' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-stone-500 font-mono text-xs">
                                    {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('M d, Y H:i') : $subscriber->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $subscriber->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-700' }}">
                                        {{ $subscriber->is_active ? 'Active Circle' : 'Unsubscribed' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <form method="POST" action="{{ route('admin.subscribers.toggle', $subscriber->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-stone-600 hover:text-stone-900 underline">
                                            {{ $subscriber->is_active ? 'Unsubscribe' : 'Reactivate' }}
                                        </button>
                                    </form>
                                    <span class="text-stone-300">|</span>
                                    <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" class="inline" onsubmit="return confirm('Permanently remove this subscriber from the database?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-900 font-medium">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-stone-400 font-serif text-base italic">
                                    No subscribers registered matching the current query.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($subscribers->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
                        {{ $subscribers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

