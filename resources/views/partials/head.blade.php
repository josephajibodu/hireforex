<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php
    $description = "Buy USD and trade the arbitrage market and earn reasonable profit margin.";
@endphp
@stack('meta')
<title>@yield('title') | Profitchain</title>
<meta name="description" content="{{ $description }}" />

<!-- Open Graph Tags -->
<meta property="og:title" content="@yield('title') | Profitchain">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ asset('images/social-image.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title') | Profitchain">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ asset('images/social-image.jpg') }}">
<meta name="twitter:site" content="@profitchain">

<link rel="canonical" href="@yield('canonical', url()->current())">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<!-- Favicon - End -->

@vite(['resources/css/app.css', 'resources/js/app.js'])

