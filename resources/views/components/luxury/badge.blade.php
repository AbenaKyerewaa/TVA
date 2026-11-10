@props([
    'variant' => 'neutral',
])

@php
    $classes = match($variant) {
        'gold' => 'bg-gold-50 text-gold-800 border-gold-200',
        'dark' => 'bg-noir-900 text-ivory-100 border-noir-800',
        'sale' => 'bg-rose-50 text-rose-800 border-rose-200',
        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        default => 'bg-stone-100 text-stone-700 border-stone-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 text-[10px] uppercase font-sans font-medium tracking-widest border rounded-full {$classes}"]) }}>
    {{ $slot }}
</span>

