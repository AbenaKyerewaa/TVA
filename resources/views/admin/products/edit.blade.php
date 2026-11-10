<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs tracking-widest uppercase font-semibold text-amber-600">The Vaughn Atelier Administration</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Edit Creation &bull; {{ $product->name }}</h2>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-xs uppercase tracking-wider text-stone-600 hover:text-stone-900 underline">
                &larr; Back to Vault Inventory
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 border border-stone-200 rounded-sm shadow-xs">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="category_id" value="Jewelry Collection" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="sku" value="Atelier SKU Code" />
                            <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full" :value="old('sku', $product->sku)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('sku')" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Creation Title" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="price" value="Retail Price ($ USD)" />
                            <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="compare_at_price" value="Compare-At / Appraisal Value ($ USD)" />
                            <x-text-input id="compare_at_price" name="compare_at_price" type="number" step="0.01" class="mt-1 block w-full" :value="old('compare_at_price', $product->compare_at_price)" />
                        </div>

                        <div>
                            <x-input-label for="materials" value="Precious Metal & Karat" />
                            <x-text-input id="materials" name="materials" type="text" class="mt-1 block w-full" :value="old('materials', $product->materials)" />
                        </div>

                        <div>
                            <x-input-label for="metal_color" value="Metal Color Family" />
                            <select id="metal_color" name="metal_color" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm">
                                <option value="">Select color...</option>
                                <option value="Yellow Gold" {{ old('metal_color', $product->metal_color) === 'Yellow Gold' ? 'selected' : '' }}>Yellow Gold</option>
                                <option value="White Gold" {{ old('metal_color', $product->metal_color) === 'White Gold' ? 'selected' : '' }}>White Gold</option>
                                <option value="Rose Gold" {{ old('metal_color', $product->metal_color) === 'Rose Gold' ? 'selected' : '' }}>Rose Gold</option>
                                <option value="Platinum" {{ old('metal_color', $product->metal_color) === 'Platinum' ? 'selected' : '' }}>Platinum</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="gemstone" value="Gemstone / Diamond Specifications" />
                            <x-text-input id="gemstone" name="gemstone" type="text" class="mt-1 block w-full" :value="old('gemstone', $product->gemstone)" />
                        </div>

                        <div>
                            <x-input-label for="weight_grams" value="Approximate Metal Weight (Grams)" />
                            <x-text-input id="weight_grams" name="weight_grams" type="number" step="0.01" class="mt-1 block w-full" :value="old('weight_grams', $product->weight_grams)" />
                        </div>

                        <div>
                            <x-input-label for="stock_quantity" value="Current Vault Quantity" />
                            <x-text-input id="stock_quantity" name="stock_quantity" type="number" class="mt-1 block w-full" :value="old('stock_quantity', $product->stock_quantity)" required />
                        </div>

                        <div>
                            <x-input-label for="low_stock_threshold" value="Low Stock Alert Threshold" />
                            <x-text-input id="low_stock_threshold" name="low_stock_threshold" type="number" class="mt-1 block w-full" :value="old('low_stock_threshold', $product->low_stock_threshold)" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="image_url" value="Primary Photography URL" />
                            <x-text-input id="image_url" name="image_url" type="url" class="mt-1 block w-full" :value="old('image_url', $product->primaryImage?->image_path)" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="short_description" value="Short Atelier Summary" />
                            <textarea id="short_description" name="short_description" rows="2" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description" value="Complete Curatorial Narrative" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Marketing Toggles -->
                    <div class="pt-4 border-t border-stone-200 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-2 text-xs text-stone-800">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span>Featured Piece</span>
                        </label>
                        <label class="flex items-center space-x-2 text-xs text-stone-800">
                            <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span>New Arrival</span>
                        </label>
                        <label class="flex items-center space-x-2 text-xs text-stone-800">
                            <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span>Bestseller</span>
                        </label>
                        <label class="flex items-center space-x-2 text-xs text-stone-800">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span>Active in Vault</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-stone-100">
                        <x-primary-button>
                            Update Jewelry Piece
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

