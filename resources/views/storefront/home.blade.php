<x-storefront-layout>
    <!-- HERO SECTION -->
    <section class="relative bg-noir-950 text-white min-h-[85vh] flex items-center overflow-hidden">
        <!-- Background Imagery with Dark Vignette -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=2000&q=85" 
                 alt="The Vaughn Atelier High Jewelry Masterpiece" 
                 class="w-full h-full object-cover object-center opacity-40 scale-105 transition-transform duration-1000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-noir-950 via-noir-950/80 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 w-full">
            <div class="max-w-2xl space-y-6">

                <h1 class="font-serif text-4xl sm:text-6xl lg:text-7xl font-light tracking-tight text-white leading-[1.08]">
                    Heirlooms Sculpted <br>
                    <span class="italic font-normal text-gold-300">In Light & Gold</span>
                </h1>

                <p class="text-stone-300 text-sm sm:text-base font-light leading-relaxed max-w-lg">
                    Discover architecturally refined solitaire rings, fluid collar necklaces, and pavé diamonds cast exclusively in certified recycled 18-karat gold and pure platinum.
                </p>

                <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <x-luxury.button variant="gold" size="lg" :href="route('catalog')">
                        Explore The Vault
                    </x-luxury.button>
                    <x-luxury.button variant="white" size="lg" :href="route('collections')">
                        View Collections
                    </x-luxury.button>
                </div>
            </div>
        </div>
    </section>

    <!-- CRAFTSMANSHIP TRUST PILLARS -->
    <section class="border-b border-stone-200 bg-ivory-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="p-4 space-y-1">
                    <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900">18K Solid Gold</span>
                    <p class="text-xs text-stone-500">100% Recycled yellow, rose & white gold</p>
                </div>
                <div class="p-4 space-y-1">
                    <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900">Certified Diamonds</span>
                    <p class="text-xs text-stone-500">IGI certified conflict-free brilliance</p>
                </div>
                <div class="p-4 space-y-1">
                    <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900">Insured Delivery</span>
                    <p class="text-xs text-stone-500">Complimentary discrete armored courier</p>
                </div>
                <div class="p-4 space-y-1">
                    <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-stone-900">Lifetime Warranty</span>
                    <p class="text-xs text-stone-500">Annual ultrasonic cleaning & inspection</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED COLLECTIONS -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-16 space-y-2">
                <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">Curated Portfolios</span>
                <h2 class="font-serif text-3xl sm:text-4xl text-stone-900 font-light">Iconic Collections</h2>
                <div class="w-12 h-0.5 bg-gold-400 mx-auto mt-3"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredCategories->take(3) as $category)
                    <a href="{{ route('collection.show', $category->slug) }}" 
                       class="group relative aspect-[4/5] overflow-hidden bg-stone-100 block shadow-xs">
                        <img src="{{ $category->image }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-noir-950/85 via-noir-950/25 to-transparent"></div>
                        <div class="absolute inset-x-6 bottom-6 text-white text-center">
                            <span class="text-[10px] uppercase tracking-prestige text-gold-300 font-medium">
                                {{ $category->products_count }} Creations
                            </span>
                            <h3 class="font-serif text-2xl text-white mt-1">{{ $category->name }}</h3>
                            <p class="text-xs text-stone-300 mt-1 line-clamp-2">{{ $category->description }}</p>
                            <span class="inline-block mt-3 text-xs uppercase font-sans tracking-ultra text-white border-b border-gold-400 pb-0.5 group-hover:text-gold-300 transition-colors">
                                Discover Pieces &rarr;
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <x-luxury.button variant="outline" size="md" :href="route('collections')">
                    Explore All Collections ({{ $featuredCategories->count() }})
                </x-luxury.button>
            </div>
        </div>
    </section>

    <!-- EDITORIAL PROMOTIONAL BANNER -->
    <section class="relative bg-stone-900 text-white overflow-hidden py-20">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=1800&q=80" 
                 alt="The Vaughn Atelier Bridal Engagement Ring" 
                 class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-noir-950 via-noir-950/90 to-noir-950/40"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl space-y-4">
                <span class="text-xs uppercase tracking-prestige text-gold-400 font-semibold">Private Salon Experience</span>
                <h3 class="font-serif text-3xl sm:text-4xl font-light text-white leading-tight">
                    Bespoke Bridal & <br>Solitaire Consultations
                </h3>
                <p class="text-xs sm:text-sm text-stone-300 font-light leading-relaxed">
                    Collaborate directly with our master jeweler to customize center diamond carats, band contours, and secret pavé halos. We reserve dedicated private viewing rooms with complimentary champagne service.
                </p>
                <div class="pt-2 flex items-center space-x-4">
                    <x-luxury.button variant="gold" size="md" :href="route('collection.show', 'engagement')">
                        Bridal Portfolio
                    </x-luxury.button>
                </div>
            </div>
        </div>
    </section>

    <!-- BESTSELLERS & MASTERPIECES -->
    <section class="py-20 bg-stone-50 border-y border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">The Core Vault</span>
                    <h2 class="font-serif text-3xl sm:text-4xl text-stone-900 font-light mt-1">Celebrated Creations</h2>
                </div>
                <a href="{{ route('catalog', ['sort' => 'bestsellers']) }}" class="mt-4 md:mt-0 text-xs uppercase font-sans tracking-ultra text-stone-700 hover:text-gold-600 border-b border-stone-400 pb-0.5 transition-colors">
                    View Entire Vault &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                    <x-luxury.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- ATELIER STORY / HERITAGE -->
    <section class="py-24 bg-noir-950 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-6">
                    <span class="text-xs uppercase tracking-prestige text-gold-400 font-semibold">Artisanal Mastery</span>
                    <h2 class="font-serif text-3xl sm:text-5xl font-light text-white leading-tight">
                        Form, Fire, and <br>
                        <span class="italic text-gold-300 font-normal">Sovereign Precision</span>
                    </h2>
                    <p class="text-stone-400 text-sm leading-relaxed font-light">
                        Every creation from The Vaughn Atelier begins as a hand-drawn architectural gouache in our private studio. Our master goldsmiths spend hundreds of hours casting, polishing, and hand-setting each certified gemstone under microscopic magnification.
                    </p>
                    <p class="text-stone-400 text-sm leading-relaxed font-light">
                        We reject mass manufacturing in favor of limited small-batch creations, ensuring that the ring, pendant, or bracelet you acquire remains as rare as the moments you celebrate.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <x-luxury.button variant="outline-gold" size="md" :href="route('catalog')">
                            Explore High Jewelry
                        </x-luxury.button>
                        <a href="{{ route('founder') }}" class="text-xs uppercase font-sans tracking-ultra text-stone-300 hover:text-gold-300 transition-colors border-b border-stone-600 pb-0.5">
                            Meet Founder Jennifer Vaughn &rarr;
                        </a>
                    </div>
                </div>

                <div class="relative aspect-square sm:aspect-[4/3] rounded overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1603561591411-07134e71a2a9?auto=format&fit=crop&w=1200&q=80" 
                         alt="Goldsmith handcrafted diamond setting" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gold-950/10 mix-blend-multiply"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CLIENT PATRON TESTIMONIALS -->
    <section class="py-20 bg-ivory-100 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-16 space-y-2">
                <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">Patron Accolades</span>
                <h2 class="font-serif text-3xl sm:text-4xl text-stone-900 font-light">Words from Our Collectors</h2>
                <div class="w-12 h-0.5 bg-gold-400 mx-auto mt-3"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 border border-stone-200 rounded-sm shadow-xs space-y-4">
                    <div class="flex text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="font-serif text-sm text-stone-700 italic leading-relaxed">
                        "The Aurelia Solitaire exceeded every dream. The stone refraction and velvet-smooth gold band feel unmistakable on the finger. The private salon consultation was unforgettable."
                    </p>
                    <span class="block text-xs uppercase tracking-wider text-stone-500 font-sans font-medium">
                        &mdash; Victoria Sterling &bull; London
                    </span>
                </div>

                <div class="bg-white p-8 border border-stone-200 rounded-sm shadow-xs space-y-4">
                    <div class="flex text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="font-serif text-sm text-stone-700 italic leading-relaxed">
                        "The Celeste Tennis Bracelet is a masterpiece of precision engineering. It lies perfectly flat with zero pinching and unmatched brilliance under evening chandeliers."
                    </p>
                    <span class="block text-xs uppercase tracking-wider text-stone-500 font-sans font-medium">
                        &mdash; Julian Chen &bull; San Francisco
                    </span>
                </div>

                <div class="bg-white p-8 border border-stone-200 rounded-sm shadow-xs space-y-4">
                    <div class="flex text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="font-serif text-sm text-stone-700 italic leading-relaxed">
                        "White-glove armored courier arrived precisely at my requested hour. The velvet packaging, certificate envelope, and gold seal demonstrate luxury at its finest tier."
                    </p>
                    <span class="block text-xs uppercase tracking-wider text-stone-500 font-sans font-medium">
                        &mdash; Camille Beaumont &bull; Geneva
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- NEW ARRIVALS -->
    @if($newArrivals->isNotEmpty())
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-xl mx-auto mb-16 space-y-2">
                    <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">Fresh Off The Bench</span>
                    <h2 class="font-serif text-3xl sm:text-4xl text-stone-900 font-light">Recent Studio Unveilings</h2>
                    <div class="w-12 h-0.5 bg-gold-400 mx-auto mt-3"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($newArrivals as $product)
                        <x-luxury.product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-storefront-layout>
