<footer class="bg-noir-950 text-stone-300 font-sans border-t border-stone-800">
    <!-- Newsletter / Collector Circle -->
    <div class="border-b border-stone-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase tracking-ultra text-gold-400 font-medium">The Vaughn Atelier Collector's Circle</span>
            <h3 class="mt-3 font-serif text-3xl sm:text-4xl text-white font-light">Privileged Access to Private Salons & New Creations</h3>
            <p class="mt-3 max-w-xl mx-auto text-sm text-stone-400">
                Receive private invitations to preview limited edition gemstone unveilings, bespoke commissions, and annual high jewelry galas.
            </p>
            @if(session('newsletter_status'))
                <div class="mt-4 p-3 max-w-md mx-auto bg-gold-950/80 border border-gold-500/50 text-gold-300 text-xs rounded-sm">
                    {{ session('newsletter_status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-8 max-w-md mx-auto flex gap-2">
                @csrf
                <input type="email" 
                       name="email"
                       required
                       placeholder="Enter your email address" 
                       class="flex-1 bg-noir-900 border border-stone-700 text-white text-xs px-4 py-3 focus:outline-none focus:border-gold-500 placeholder-stone-500 tracking-wider">
                <input type="hidden" name="source" value="footer_collector_circle">
                <x-luxury.button variant="gold" size="md" type="submit">
                    Join
                </x-luxury.button>
            </form>
        </div>
    </div>

    <!-- Main Footer Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-4">
                <x-luxury.brand-logo size="lg" inverted="true" />
                <p class="text-xs leading-relaxed text-stone-400 max-w-sm mt-4">
                    The Vaughn Atelier embodies the zenith of contemporary high jewelry. Sculpted in recycled solid 18-karat gold and certified conflict-free diamonds, each heirloom is individually numbered and hallmarked by our master artisans.
                </p>
                <div class="pt-2 text-xs text-stone-400 space-y-1">
                    <p class="flex items-center space-x-2">
                        <span class="text-gold-400">&bull;</span>
                        <span>Private Appointments: salon@thevaughnatelier.com</span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <span class="text-gold-400">&bull;</span>
                        <span>Concierge Hotline: +1 (800) 555-8822</span>
                    </p>
                </div>
            </div>

            <!-- Collections -->
            <div class="space-y-3">
                <h4 class="text-xs uppercase font-sans font-semibold tracking-ultra text-white">Collections</h4>
                <ul class="space-y-2 text-xs text-stone-400">
                    <li><a href="{{ route('catalog', ['category' => 'rings']) }}" class="hover:text-gold-300 transition-colors">Solitaire Rings</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'necklaces']) }}" class="hover:text-gold-300 transition-colors">Diamond Pendants</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'earrings']) }}" class="hover:text-gold-300 transition-colors">Pavé Huggies</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'bracelets']) }}" class="hover:text-gold-300 transition-colors">Tennis Bracelets</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'engagement']) }}" class="hover:text-gold-300 transition-colors">Bridal & Bespoke</a></li>
                </ul>
            </div>

            <!-- Client Concierge -->
            <div class="space-y-3">
                <h4 class="text-xs uppercase font-sans font-semibold tracking-ultra text-white">Client Concierge</h4>
                <ul class="space-y-2 text-xs text-stone-400">
                    <li><a href="#shipping" class="hover:text-gold-300 transition-colors">Insured White-Glove Shipping</a></li>
                    <li><a href="#sizing" class="hover:text-gold-300 transition-colors">Ring Size Guide</a></li>
                    <li><a href="#care" class="hover:text-gold-300 transition-colors">Jewelry Care & Warranty</a></li>
                    <li><a href="#certificate" class="hover:text-gold-300 transition-colors">IGI Gemstone Certification</a></li>
                    <li><a href="#returns" class="hover:text-gold-300 transition-colors">Complimentary 30-Day Returns</a></li>
                </ul>
            </div>

            <!-- The Atelier -->
            <div class="space-y-3">
                <h4 class="text-xs uppercase font-sans font-semibold tracking-ultra text-white">The Atelier</h4>
                <ul class="space-y-2 text-xs text-stone-400">
                    <li><a href="{{ route('founder') }}" class="hover:text-gold-300 transition-colors">The Founder: Jennifer Vaughn</a></li>
                    <li><a href="#heritage" class="hover:text-gold-300 transition-colors">Our Craft & Heritage</a></li>
                    <li><a href="#sustainability" class="hover:text-gold-300 transition-colors">Ethical Sourcing Pledge</a></li>
                    <li><a href="#salons" class="hover:text-gold-300 transition-colors">Flagship Salons</a></li>
                    <li><a href="#careers" class="hover:text-gold-300 transition-colors">Artisanal Careers</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Copyright & Badges -->
        <div class="mt-16 pt-8 border-t border-stone-800 flex flex-col sm:flex-row items-center justify-between text-[11px] text-stone-500">
            <p>&copy; {{ date('Y') }} The Vaughn Atelier. All rights reserved. Registered trademark.</p>
            <div class="flex items-center space-x-6 mt-4 sm:mt-0">
                <a href="#privacy" class="hover:text-stone-300 transition-colors">Privacy Policy</a>
                <a href="#terms" class="hover:text-stone-300 transition-colors">Terms of Sale</a>
                <a href="#cookies" class="hover:text-stone-300 transition-colors">Cookie Preferences</a>
            </div>
        </div>
    </div>
</footer>

