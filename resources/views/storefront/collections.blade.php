<x-storefront-layout>
    <x-slot:title>
        Curated Jewelry Collections — The Vaughn Atelier
    </x-slot:title>

    <!-- Header Banner -->
    <div class="bg-noir-950 text-white py-16 border-b border-stone-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
            <span class="text-xs uppercase tracking-prestige text-gold-400 font-semibold">The Vaughn Atelier</span>
            <h1 class="font-serif text-3xl sm:text-5xl font-light text-white">Curated Collections</h1>
            <p class="text-xs sm:text-sm text-stone-400 max-w-lg mx-auto font-light leading-relaxed">
                Explore our distinct portfolios sculpted in recycled 18-karat gold, pure platinum, and certified conflict-free diamonds.
            </p>
        </div>
    </div>

    <!-- Collections Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($categories as $category)
                <a href="{{ route('collection.show', $category->slug) }}" 
                   class="group flex flex-col bg-white border border-stone-200 overflow-hidden shadow-xs hover:border-gold-500 transition-all duration-300">
                    <div class="aspect-[4/3] w-full overflow-hidden bg-stone-100 relative">
                        <img src="{{ $category->image }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-noir-950/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-[10px] uppercase font-sans font-semibold tracking-ultra text-noir-950 rounded-full">
                                {{ $category->products_count }} Creations
                            </span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-serif text-2xl text-stone-900 group-hover:text-gold-700 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="mt-2 text-xs text-stone-600 line-clamp-2 font-light leading-relaxed">
                                {{ $category->description }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900 group-hover:text-gold-700">
                            <span>Explore Portfolio</span>
                            <span>&rarr;</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-storefront-layout>

