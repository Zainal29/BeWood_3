<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BeWood - Furniture Premium')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="antialiased bg-cream text-charcoal">
    @include('partials.loading-screen')
    @include('partials.scroll-progress')
    @include('partials.toast-container')
    @include('partials.cart-sidebar')
    @include('partials.quick-view-modal')
    @include('partials.search-modal')
    @include('partials.back-to-top')
    @include('partials.wa-float')
    @include('partials.image-zoom')
    @include('partials.navbar')

    <main id="main-content">@yield('content')</main>

    @include('partials.footer')
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
