@props(['size' => 'md', 'inverted' => false])

@php
    $textClasses = match($size) {
        'sm' => 'text-sm sm:text-base',
        'lg' => 'text-2xl sm:text-3xl',
        'xl' => 'text-3xl sm:text-4xl',
        default => 'text-lg sm:text-xl',
    };
    $subtitleClasses = match($size) {
        'sm' => 'text-[8px] tracking-prestige',
        'lg' => 'text-xs tracking-prestige',
        'xl' => 'text-sm tracking-prestige',
        default => 'text-[9px] tracking-prestige',
    };
@endphp

<a href="{{ route('home') }}" class="inline-flex flex-col items-center group text-center focus:outline-none">
    <div class="flex items-center space-x-2">
        <span class="font-serif font-light {{ $textClasses }} tracking-wider uppercase transition-colors duration-300 {{ $inverted ? 'text-white group-hover:text-gold-300' : 'text-noir-900 group-hover:text-gold-600' }}">
            The Vaughn Atelier
        </span>
    </div>
    <span class="font-sans uppercase font-medium {{ $subtitleClasses }} mt-0.5 {{ $inverted ? 'text-stone-400 group-hover:text-gold-200' : 'text-stone-500 group-hover:text-gold-700' }} transition-colors duration-300">
        Haute Joaillerie
    </span>
</a>

