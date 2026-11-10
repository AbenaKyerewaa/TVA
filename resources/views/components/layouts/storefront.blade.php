<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'The Vaughn Atelier — Fine Jewelry & Solitaire Diamonds' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Discover The Vaughn Atelier. Handcrafted solid 18-karat gold, platinum, and certified diamond jewelry engineered with unyielding architectural elegance.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'haute joaillerie, fine jewelry, 18k solid gold, luxury diamonds, bespoke solitaire, The Vaughn Atelier' }}">
    <meta name="robots" content="{{ $metaRobots ?? 'index, follow' }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">

    <!-- Open Graph Protocol -->
    <meta property="og:site_name" content="The Vaughn Atelier">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'The Vaughn Atelier — Fine Jewelry & Solitaire Diamonds') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($metaDescription ?? 'Discover The Vaughn Atelier. Handcrafted solid 18-karat gold, platinum, and certified diamond jewelry engineered with unyielding architectural elegance.') }}">
    <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-tva-luxury.jpg') }}">

    <!-- Twitter Card Protocol -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle ?? ($title ?? 'The Vaughn Atelier') }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? ($metaDescription ?? 'Handcrafted solid 18-karat gold, platinum, and certified diamond jewelry.') }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-tva-luxury.jpg') }}">

    <!-- Global Organization / JewelryStore JSON-LD Schema -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "JewelryStore",
        "name": "The Vaughn Atelier",
        "url": "{{ url('/') }}",
        "description": "Bespoke fine jewelry, 18-karat gold heirlooms, and certified solitaire diamonds.",
        "priceRange": "$$$$",
        "currenciesAccepted": "USD, EUR, GBP",
        "paymentAccepted": "Credit Card, Private Concierge",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "742 Fifth Avenue, Atelier Suite 14",
            "addressLocality": "New York",
            "addressRegion": "NY",
            "postalCode": "10019",
            "addressCountry": "US"
        }
    }
    </script>
    @stack('schema')

    <!-- Preconnect Luxury Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-stone-900 selection:bg-gold-200 selection:text-noir-950">
    <div class="min-h-screen flex flex-col justify-between">
        <!-- Navigation -->
        <x-navigation />

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-footer />
    </div>
</body>
</html>
