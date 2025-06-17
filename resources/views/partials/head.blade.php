<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php
    $description = "Cardbeta is a secure and easy-to-use platform for buying discounted gift cards and e-cards from top global brands. Get the best rates with fast delivery, full transparency, and zero hassle. Perfect for both vendors and everyday users.";
@endphp
@stack('meta')
<title>@yield('title') | Cardbeta</title>
<meta name="description" content="{{ $description }}" />

<!-- Open Graph Tags -->
<meta property="og:title" content="@yield('title') | Cardbeta">
<meta property="og:description" content="{{ $description }}">
{{--<meta property="og:image" content="{{ asset('images/social-image.jpg') }}">--}}
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title') | Cardbeta">
<meta name="twitter:description" content="{{ $description }}">
{{--<meta name="twitter:image" content="{{ asset('images/social-image.jpg') }}">--}}
<meta name="twitter:site" content="@profitchain">

<link rel="canonical" href="@yield('canonical', url()->current())">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Favicon -->
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<!-- Favicon - End -->

@vite(['resources/css/app.css', 'resources/js/app.js'])

