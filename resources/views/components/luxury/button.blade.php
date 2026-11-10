@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center font-sans uppercase font-medium tracking-ultra transition-all duration-300 focus:outline-none focus:ring-1 focus:ring-gold-500 disabled:opacity-50 disabled:cursor-not-allowed';

    $sizes = match($size) {
        'sm' => 'px-4 py-2 text-xs',
        'lg' => 'px-8 py-4 text-sm',
        default => 'px-6 py-3 text-xs',
    };

    $variants = match($variant) {
        'gold' => 'bg-gold-500 text-noir-950 hover:bg-gold-400 active:bg-gold-600 shadow-sm border border-gold-400',
        'outline' => 'bg-transparent text-noir-900 border border-noir-900 hover:bg-noir-900 hover:text-white',
        'outline-gold' => 'bg-transparent text-gold-600 border border-gold-500 hover:bg-gold-500 hover:text-noir-950',
        'white' => 'bg-white text-noir-950 hover:bg-ivory-100 border border-stone-200',
        'ghost' => 'bg-transparent text-noir-900 hover:text-gold-600',
        default => 'bg-noir-950 text-white hover:bg-noir-800 active:bg-black border border-noir-900',
    };

    $classes = "{$base} {$sizes} {$variants}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

