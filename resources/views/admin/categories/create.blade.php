<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs tracking-widest uppercase font-semibold text-amber-600">The Vaughn Atelier Administration</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Create High Jewelry Collection</h2>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-xs uppercase tracking-wider text-stone-600 hover:text-stone-900 underline">
                &larr; Back to Collections
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 border border-stone-200 rounded-sm shadow-xs">
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Collection Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="e.g. Solitaire Rings & Bands" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="slug" value="URL Slug (Optional - auto-generated if blank)" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" placeholder="e.g. solitaire-rings" />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>

                    <div>
                        <x-input-label for="parent_id" value="Parent Category (Optional)" />
                        <select id="parent_id" name="parent_id" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm">
                            <option value="">None (Top-Level Collection)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="image" value="Cover Image URL" />
                        <x-text-input id="image" name="image" type="url" class="mt-1 block w-full" :value="old('image')" placeholder="https://images.unsplash.com/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Atelier Curatorial Description" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm" placeholder="Describe the gemstones, gold karats, and aesthetic inspiration...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <x-input-label for="sort_order" value="Display Order" />
                            <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full" :value="old('sort_order', 0)" />
                        </div>

                        <div class="flex items-center space-x-6 pt-6">
                            <label class="flex items-center space-x-2 text-xs text-stone-800">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                                <span>Featured on Homepage</span>
                            </label>

                            <label class="flex items-center space-x-2 text-xs text-stone-800">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                                <span>Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-stone-100">
                        <x-primary-button>
                            Publish Collection
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

