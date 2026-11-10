<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs tracking-widest uppercase font-semibold text-amber-600">The Vaughn Atelier Administration</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">High Jewelry Collections</h2>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-stone-900 text-white text-xs uppercase font-sans tracking-ultra hover:bg-stone-800 transition-colors">
                + Create Collection
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-white border border-stone-200 rounded-sm shadow-xs overflow-hidden">
                <table class="min-w-full divide-y divide-stone-200 text-left text-xs font-sans">
                    <thead class="bg-stone-100 uppercase tracking-ultra font-semibold text-stone-700">
                        <tr>
                            <th class="px-6 py-3">Collection</th>
                            <th class="px-6 py-3">Slug</th>
                            <th class="px-6 py-3">Creations</th>
                            <th class="px-6 py-3">Featured</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-stone-50">
                                <td class="px-6 py-4 flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded bg-stone-100 overflow-hidden flex-shrink-0">
                                        @if($category->image)
                                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[10px] text-stone-400">TVA</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-stone-900">{{ $category->name }}</p>
                                        @if($category->parent)
                                            <p class="text-[10px] text-stone-400">Parent: {{ $category->parent->name }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-stone-500">{{ $category->slug }}</td>
                                <td class="px-6 py-4 font-medium text-stone-900">{{ $category->products_count }} pieces</td>
                                <td class="px-6 py-4">
                                    @if($category->is_featured)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-800 font-semibold">Featured</span>
                                    @else
                                        <span class="text-stone-400">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($category->is_active)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-semibold">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-stone-100 text-stone-500 font-semibold">Hidden</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-amber-700 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline-block" onsubmit="return confirm('Archive this collection?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-stone-400">No collections defined yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

