<x-storefront-layout>
    <x-slot:title>
        Jennifer Vaughn — Founder & CEO &mdash; The Vaughn Atelier
    </x-slot:title>
    <x-slot:metaDescription>
        Meet Jennifer Vaughn, Founder and Chief Executive Officer of The Vaughn Atelier. Discover her architectural philosophy, gemological heritage, and devotion to sovereign high jewelry.
    </x-slot:metaDescription>
    <x-slot:ogImage>
        {{ asset('images/found.jpg') }}
    </x-slot:ogImage>

    <!-- HERO SECTION -->
    <section class="relative bg-noir-950 text-white overflow-hidden pt-12 pb-20 lg:py-28 border-b border-stone-800">
        <!-- Ambient Background Glow -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-gold-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left: Biography Lead (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/15">
                        <span class="w-2 h-2 rounded-full bg-gold-400"></span>
                        <span class="text-[10px] uppercase font-sans tracking-prestige text-stone-200">Maison Heritage & Leadership</span>
                    </div>

                    <h1 class="font-serif text-4xl sm:text-6xl font-light text-white tracking-tight leading-[1.1]">
                        Jennifer Vaughn
                    </h1>
                    
                    <p class="text-xs uppercase tracking-ultra font-sans text-gold-400 font-semibold">
                        Founder, Creative Director & Chief Executive Officer
                    </p>

                    <blockquote class="border-l-2 border-gold-500/80 pl-6 py-2 italic font-serif text-lg sm:text-xl text-stone-200 font-light leading-relaxed">
                        &ldquo;High jewelry is wearable architecture. Each setting must protect the sovereign brilliance of the earth&rsquo;s rarest gemstones while yielding seamlessly to the human form.&rdquo;
                    </blockquote>

                    <p class="text-stone-300 text-sm sm:text-base font-light leading-relaxed">
                        Under the visionary direction of Jennifer Vaughn, The Vaughn Atelier has redefined contemporary haute joaillerie. Uniting classical Parisian craftsmanship with bold architectural silhouettes, Jennifer has built an independent sanctuary dedicated exclusively to ethically mined, museum-grade solitaires and handcrafted 18-karat heirlooms.
                    </p>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <x-luxury.button variant="gold" size="md" href="#story">
                            Read Her Story
                        </x-luxury.button>
                        <x-luxury.button variant="outline-white" size="md" :href="route('catalog')">
                            Explore The Vault
                        </x-luxury.button>
                    </div>
                </div>

                <!-- Right: CEO Portrait (5 cols) -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-md">
                        <!-- Frame Accents -->
                        <div class="absolute -inset-2 bg-gradient-to-tr from-gold-600/30 via-transparent to-amber-400/20 rounded-sm blur-xs"></div>
                        <div class="relative bg-noir-900 border border-stone-800 p-2 shadow-2xl rounded-sm">
                            <div class="aspect-[4/5] overflow-hidden bg-noir-950">
                                <img src="{{ asset('images/found.jpg') }}" 
                                     alt="Jennifer Vaughn, Founder & CEO of The Vaughn Atelier" 
                                     class="w-full h-full object-cover object-top filter contrast-[1.03] hover:scale-105 transition-transform duration-700 ease-out">
                            </div>
                            <div class="p-4 bg-noir-950 border-t border-stone-800/80 text-center">
                                <p class="font-serif text-sm text-stone-200 font-light">Jennifer Vaughn</p>
                                <p class="text-[10px] uppercase font-sans tracking-ultra text-gold-400 mt-0.5">Private Salon &bull; Place Vendôme</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- KEY ATELIER METRICS -->
    <section class="bg-noir-900 text-white py-12 border-b border-stone-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-stone-800">
                <div class="pt-4 md:pt-0">
                    <span class="block font-serif text-3xl sm:text-4xl text-gold-300 font-light">18+</span>
                    <span class="text-[11px] uppercase tracking-ultra font-sans text-stone-400 mt-1 block">Years of Gemological Mastery</span>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="block font-serif text-3xl sm:text-4xl text-gold-300 font-light">100%</span>
                    <span class="text-[11px] uppercase tracking-ultra font-sans text-stone-400 mt-1 block">Traceable & Conflict-Free</span>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="block font-serif text-3xl sm:text-4xl text-gold-300 font-light">18K & PT</span>
                    <span class="text-[11px] uppercase tracking-ultra font-sans text-stone-400 mt-1 block">Recycled Fairmined Metals</span>
                </div>
                
            </div>
        </div>
    </section>

    <!-- EDITORIAL BIOGRAPHY & WRITINGS -->
    <section id="story" class="py-24 bg-white text-stone-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
            
            <!-- Chapter 1 -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <div class="md:col-span-4">
                    <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-gold-700 block">Chapter I</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-light text-stone-900 mt-1">
                        The Architecture of Light
                    </h2>
                </div>
                <div class="md:col-span-8 space-y-4 text-sm leading-relaxed text-stone-600 font-light">
                    <p>
                        Born into a family heritage shaped by Swiss architectural preservation and gemstone scholarship in Geneva and Liverpool, Jennifer Vaughn developed an early appreciation for structural symmetry, mineral composition, and the interaction of light with precious materials.
                    </p>
                    <p>
                        Her formative years were influenced by the study of architectural geometry, cathedral structures, and the intricate relationship between natural gemstones and refined craftsmanship. This foundation cultivated her distinctive approach to jewellery design, where precision, balance, and artistic expression converge.
                    </p>
                    <p>
                        Following her studies in metal sculpture and fine jewellery fabrication at Paris&rsquo;s renowned École Boulle, Jennifer pursued advanced gemological education through the Gemological Institute of America (GIA). Combining technical expertise with an appreciation for traditional artistry, she established a design philosophy centred on exceptional materials, meticulous execution, and enduring elegance.
                    </p>
                </div>
            </div>

            <!-- Decorative Divider -->
            <div class="flex items-center justify-center">
                <span class="h-px w-24 bg-stone-200"></span>
                <span class="mx-4 text-gold-600 text-xs font-serif">&bull; &diams; &bull;</span>
                <span class="h-px w-24 bg-stone-200"></span>
            </div>

            <!-- Chapter 2 -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <div class="md:col-span-4">
                    <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-gold-700 block">Chapter II</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-light text-stone-900 mt-1">
                        Founding the Independent Atelier
                    </h2>
                </div>
                <div class="md:col-span-8 space-y-4 text-sm leading-relaxed text-stone-600 font-light">
                    <p>
                        In founding <strong>The Vaughn Atelier</strong>, Jennifer created a private salon where clients deal not with sales intermediaries, but with master jewelers. She instituted an uncompromising standard: no hollow castings, no thin electroplating, and no synthetic treatments. Every piece must be formed of solid 18-karat gold or 950 platinum, engineered to endure daily wear across generations.
                    </p>
                    <p>
                        As Chief Executive Officer, Jennifer maintains complete artistic and commercial autonomy. By operating independently of luxury conglomerates, The Vaughn Atelier can prioritize time-intensive hand pavé, micro-beveled bezels, and custom gemstone cuts that mass manufacturers deem commercially impractical.
                    </p>
                </div>
            </div>

            <!-- Decorative Divider -->
            <div class="flex items-center justify-center">
                <span class="h-px w-24 bg-stone-200"></span>
                <span class="mx-4 text-gold-600 text-xs font-serif">&bull; &diams; &bull;</span>
                <span class="h-px w-24 bg-stone-200"></span>
            </div>

            <!-- Chapter 3 -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <div class="md:col-span-4">
                    <span class="text-xs uppercase font-sans tracking-ultra font-semibold text-gold-700 block">Chapter III</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-light text-stone-900 mt-1">
                        Unwavering Ethical Sourcing
                    </h2>
                </div>
                <div class="md:col-span-8 space-y-4 text-sm leading-relaxed text-stone-600 font-light">
                    <p>
                        &ldquo;When a patron acquires a piece to celebrate a lifetime milestone,&rdquo; Jennifer notes, &ldquo;the provenance of the earth must be as pure as the sentiment it represents.&rdquo;
                    </p>
                    <p>
                        Jennifer personally oversees direct partnerships with responsible mining collectives in Botswana, Colombia&rsquo;s historic Muzo Valley, and Sri Lanka. The Vaughn Atelier enforces strict provenance protocols, ensuring living wages for mining communities, environmental reclamation, and full Kimberley Process and RJC certification. Every center diamond is dual-inscribed and registered in the Atelier's permanent vault register.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- PERSONAL LETTER FROM THE CEO -->
    <section class="py-20 bg-stone-50 border-y border-stone-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 sm:p-12 border border-stone-200 rounded-sm shadow-md space-y-6">
                <div class="text-center pb-4 border-b border-stone-100">
                    <span class="text-xs uppercase tracking-prestige font-sans text-gold-600 font-semibold">A Personal Note to Our Patrons</span>
                    <h3 class="font-serif text-2xl sm:text-3xl text-stone-900 font-light mt-1">From the Desk of Jennifer Vaughn</h3>
                </div>

                <div class="space-y-4 text-stone-700 font-serif text-base sm:text-lg leading-relaxed font-light">
                    <p>
                        &ldquo;Welcome to The Vaughn Atelier.
                    </p>
                    <p>
                        When we shape gold and set diamonds, we are conscious that our work will outlast us. The ring worn today will one day rest upon the hand of a granddaughter; the necklace chosen for an evening will become the definitive signature of a family&rsquo;s most treasured milestones.
                    </p>
                    <p>
                        This truth demands unwavering integrity in every bezel, prong, and clasp. I invite you to explore our high jewelry vault, or to join me in our private viewing salons in Paris or New York to commission an heirloom conceived exclusively for your story.&rdquo;
                    </p>
                </div>

                <div class="pt-6 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <p class="font-serif italic text-xl text-stone-900">Jennifer Vaughn</p>
                        <p class="text-[11px] uppercase font-sans tracking-wider text-stone-500">Founder & CEO &bull; The Vaughn Atelier</p>
                    </div>
                    <a href="mailto:jennifer.vaughn@thevaughnatelier.com" class="text-xs uppercase font-sans tracking-ultra text-gold-700 hover:text-stone-900 border-b border-gold-600 pb-0.5 transition-colors">
                        Direct Inquiries &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SIGNATURE CURATIONS BY JENNIFER -->
    @if(isset($signaturePieces) && $signaturePieces->isNotEmpty())
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-xl mx-auto mb-12 space-y-2">
                    <span class="text-xs uppercase tracking-prestige text-gold-600 font-semibold">Hand-Selected Creations</span>
                    <h2 class="font-serif text-3xl sm:text-4xl text-stone-900 font-light">The Founder's Personal Edit</h2>
                    <p class="text-xs text-stone-500 font-light">A curated trio exemplifying Jennifer Vaughn's devotion to architectural solitaires and fluid collar necklaces.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($signaturePieces as $piece)
                        <x-luxury.product-card :product="$piece" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- BESPOKE SALON INVITATION -->
    <section class="py-20 bg-noir-950 text-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="text-xs uppercase tracking-ultra text-gold-400 font-medium">Private Appointments</span>
            <h2 class="font-serif text-3xl sm:text-5xl font-light text-white leading-tight">
                Experience the Salon in Paris or New York
            </h2>
            <p class="text-sm text-stone-400 max-w-xl mx-auto font-light leading-relaxed">
                Reserve an intimate consultation with Jennifer Vaughn and our master goldsmiths. Examine loose certified diamonds under 40&times; magnification and co-create bespoke heirloom commissions.
            </p>
            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <x-luxury.button variant="gold" size="lg" :href="route('catalog')">
                    Explore The Full Vault
                </x-luxury.button>
                <a href="mailto:salon@thevaughnatelier.com" class="px-6 py-3.5 bg-stone-900 text-stone-200 hover:text-white hover:bg-stone-800 border border-stone-700 text-xs uppercase font-sans tracking-ultra font-semibold transition-all">
                    Request Salon Appointment
                </a>
            </div>
        </div>
    </section>
</x-storefront-layout>
