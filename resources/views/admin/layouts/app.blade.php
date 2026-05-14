<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BeWood Admin — Premium Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <style>
        /* Animations */
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slide-up 0.2s ease-out; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

<div class="flex min-h-screen relative">
    <!-- SIDEBAR (fixed, tidak bisa collapse) -->
    <aside id="sidebar"
           class="sidebar fixed left-0 top-0 z-40 h-screen bg-white border-r border-slate-200 flex flex-col shadow-sm"
           role="navigation"
           aria-label="Main Navigation">

        <!-- Logo -->
        <div class="logo-wrapper px-6 py-6 border-b border-slate-200 flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M6 4.5L3 6.75v10.5l3 2.25m0-10.5L3 6.75m3-2.25L9 6.75M6 4.5v10.5m9-9L12 6.75v10.5l3 2.25m0-10.5L18 6.75m-3-2.25L21 6.75M12 6.75L9 9m3-2.25L12 17.25M9 9l-3 2.25m3-2.25v10.5m12-10.5L21 9m3-2.25L18 12m3-2.25V17.25M18 12l3 2.25m-3-2.25v10.5"/>
                    </svg>
                </div>
                <div class="logo-text">
                    <span class="font-bold text-xl tracking-tight text-slate-800">BeWood</span>
                    <p class="text-[11px] text-emerald-600 mt-0.5">Admin Panel</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto" role="menubar">
            @php
                $currentRoute = request()->route()?->getName() ?? '';
                $menus = [
                    ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['name' => 'Hero Section', 'route' => 'admin.settings.edit', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['name' => 'Marquee', 'route' => 'admin.marquee.index', 'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
                    ['name' => 'Kategori', 'route' => 'admin.categories.index', 'icon' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5'],
                    ['name' => 'Produk', 'route' => 'admin.products.index', 'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-3.75'],
                    ['name' => 'Mengapa BeWood?', 'route' => 'admin.why-us.index', 'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
                    ['name' => 'Testimonial', 'route' => 'admin.testimonials.index', 'icon' => 'M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z'],
                    ['name' => 'Instagram Feed', 'route' => 'admin.instagram.index', 'icon' => 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5'],
                    ['name' => 'Pesanan', 'route' => 'admin.orders.index', 'icon' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z'],
                    ['name' => 'FAQ', 'route' => 'admin.faqs.index', 'icon' => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z'],
                ];
            @endphp

            @foreach($menus as $menu)
                @php
                    $isActive = str_starts_with($currentRoute, $menu['route']);
                    $menuId = Str::slug($menu['name']);
                @endphp
                <a href="{{ route($menu['route']) }}"
                   id="menu-{{ $menuId }}"
                   role="menuitem"
                   class="menu-item flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 {{ $isActive ? 'active bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}"
                   aria-current="{{ $isActive ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $menu['icon'] }}"/>
                    </svg>
                    <span class="nav-text text-sm font-medium">{{ $menu['name'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Profile Footer -->
        <div class="p-5 border-t border-slate-200 mt-auto shrink-0">
            <div class="profile-wrapper flex items-center gap-3">
                <div class="profile-avatar w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold shrink-0" aria-hidden="true">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="profile-info flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-btn">
                    @csrf
                    <button type="submit"
                            class="text-slate-400 hover:text-slate-600 transition p-2 rounded-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            aria-label="Keluar dari akun">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
                </div>
            </div>

<<<<<<< HEAD
            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-8 space-y-2">
                @php $currentRoute = request()->route()->getName(); @endphp

                <!-- 1. Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.dashboard' ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Dashboard</span>
                </a>

                <!-- 2. Hero Section -->
                <a href="{{ route('admin.settings.edit') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.settings.edit' ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Hero Section</span>
                </a>

                <!-- 3. Marquee / Running Text -->
                <a href="{{ route('admin.marquee.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.marquee.index' || str_contains($currentRoute, 'admin.marquee.') ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Marquee</span>
                </a>

                <!-- 4. Kategori -->
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.categories.index' || str_contains($currentRoute, 'admin.categories.') ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Kategori</span>
                </a>

                <!-- 5. Produk -->
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.products.index' || str_contains($currentRoute, 'admin.products.') ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Produk</span>
                </a>

                <!-- 6. Mengapa BeWood? (Why Us) -->
                <a href="{{ route('admin.why-us.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.why-us.index' ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Mengapa BeWood?</span>
                </a>

                <!-- 7. Testimonial -->
                <a href="{{ route('admin.testimonials.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.testimonials.index' || str_contains($currentRoute, 'admin.testimonials.') ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Testimonial</span>
                </a>

                <!-- 8. Instagram Feed -->
                <a href="{{ route('admin.instagram.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.instagram.index' || str_contains($currentRoute, 'admin.instagram.') ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Instagram Feed</span>
                </a>

                <!-- 9. Pesanan -->
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.orders.index' || str_contains($currentRoute, 'admin.orders.') ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="font-sans text-sm font-medium">Pesanan</span>
                </a>

                <!-- 10. FAQ -->
                <a href="{{ route('admin.faqs.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group
                          {{ $currentRoute == 'admin.faqs.index' || str_contains($currentRoute, 'admin.faqs.') ? 'bg-sage-700/70 text-white shadow-md' : 'text-sage-200 hover:bg-sage-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                    <span class="font-sans text-sm font-medium">FAQ</span>
                </a>
            </nav>

            <!-- User Profile & Logout -->
            <div class="px-4 py-6 border-t border-sage-700/50 mt-auto">
                <div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-sage-800/50">
                    <div class="w-8 h-8 rounded-full bg-sage-600 flex items-center justify-center text-sm font-semibold uppercase">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-sage-300 truncate">Admin</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sage-300 hover:text-white transition-colors" aria-label="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
=======
            <!-- User Menu -->
            <div class="flex items-center gap-3">
                <div class="relative">
                    <button class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-100 transition focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            aria-label="Menu pengguna">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold shrink-0">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </button>
>>>>>>> 0ad3d5dbbb5fc3f3170e83c6d55e2f8ac7faead9
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="w-full max-w-[1600px] mx-auto px-6 lg:px-8 py-8 flex-1">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 animate-slide-up" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-600 hover:text-emerald-800" aria-label="Tutup notifikasi">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center gap-3 animate-slide-up" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800" aria-label="Tutup notifikasi">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="px-6 lg:px-8 py-4 border-t border-slate-200 bg-white shrink-0">
            <p class="text-sm text-slate-500 text-center">
                &copy; {{ date('Y') }} BeWood. All rights reserved.
            </p>
        </footer>
    </main>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-[100] space-y-2 pointer-events-none"></div>

@stack('scripts')
</body>
</html>
