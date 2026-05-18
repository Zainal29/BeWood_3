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
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <style>
        @keyframes slide-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slide-up 0.2s ease-out; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

<div class="flex min-h-screen">

    <!-- ========== SIDEBAR ========== -->
    <aside id="adminSidebar" class="admin-sidebar fixed left-0 top-0 z-40 h-screen bg-white border-r border-slate-200 flex flex-col shadow-sm">

        <!-- Logo (bewood Admin) -->
        <div class="px-6 py-6 border-b border-slate-200 flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 4.5L3 6.75v10.5l3 2.25m0-10.5L3 6.75m3-2.25L9 6.75M6 4.5v10.5m9-9L12 6.75v10.5l3 2.25m0-10.5L18 6.75m-3-2.25L21 6.75M12 6.75L9 9m3-2.25L12 17.25M9 9l-3 2.25m3-2.25v10.5m12-10.5L21 9m3-2.25L18 12m3-2.25V17.25M18 12l3 2.25m-3-2.25v10.5"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-xl tracking-tight text-slate-800">BeWood</span>
                    <p class="text-[11px] text-emerald-600 mt-0.5">Admin Panel</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
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
                @endphp
                <a href="{{ route($menu['route']) }}"
                   class="menu-item flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $isActive ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $menu['icon'] }}"/>
                    </svg>
                    <span class="text-sm font-medium">{{ $menu['name'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Profile Footer -->
        <div class="p-5 border-t border-slate-200 mt-auto shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
                <!-- Logout di footer sudah ada, jadi tidak perlu tambah lagi -->
            </div>
        </div>
    </aside>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="admin-main-content flex-1 min-h-screen bg-slate-50">

        <!-- Topbar Sticky -->
        <header class="admin-topbar sticky top-0 z-30 px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <!-- Mobile Menu Toggle -->
                <button id="mobileMenuToggle" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'BeWood Admin')</h1>
            </div>

            <!-- ========== TOMBOL LOGOUT (menggantikan search) ========== -->
            <div class="flex items-center gap-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-red-500 text-white text-sm font-medium hover:bg-red-600 transition shadow-sm">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-6 lg:p-8">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 animate-slide-up">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center gap-3 animate-slide-up">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="text-center text-xs text-gray-400 py-4 border-t border-slate-200">
            &copy; {{ date('Y') }} BeWood Admin. All rights reserved.
        </footer>
    </main>
</div>

<!-- Mobile Sidebar Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        const toggle = document.getElementById('mobileMenuToggle');
        if (sidebar && toggle) {
            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 1024 && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>
