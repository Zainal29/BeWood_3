<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 py-4 md:py-5">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 lg:px-8">
        <a href="{{ route('landing') }}" class="nav-logo font-serif text-2xl tracking-widest text-sage-900 font-light">Be<span class="text-sage-500">Wood</span></a>
        <ul class="hidden md:flex items-center gap-10 text-xs tracking-widest uppercase font-sans font-medium text-sage-700">
            <li><a href="{{ route('landing') }}#hero" class="hover:text-sage-900 transition-colors relative group">Beranda<span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-sage-500 transition-all duration-300 group-hover:w-full"></span></a></li>
            <li><a href="{{ route('landing') }}#produk" class="hover:text-sage-900 transition-colors relative group">Toko<span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-sage-500 transition-all duration-300 group-hover:w-full"></span></a></li>
            <li><a href="{{ route('landing') }}#tentang" class="hover:text-sage-900 transition-colors relative group">Tentang<span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-sage-500 transition-all duration-300 group-hover:w-full"></span></a></li>
            <li><a href="{{ route('landing') }}#faq" class="hover:text-sage-900 transition-colors relative group">FAQ<span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-sage-500 transition-all duration-300 group-hover:w-full"></span></a></li>
            <li><a href="{{ route('landing') }}#kontak" class="hover:text-sage-900 transition-colors relative group">Kontak<span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-sage-500 transition-all duration-300 group-hover:w-full"></span></a></li>
        </ul>
        <div class="flex items-center gap-3">
            <button id="search-btn" class="text-sage-700 hover:text-sage-900 p-2 rounded-full hover:bg-sage-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </button>
            <button id="wishlist-btn" class="relative text-sage-700 hover:text-sage-900 p-2 rounded-full hover:bg-sage-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                <span id="wishlist-badge" class="hidden absolute -top-1 -right-1 bg-gold text-charcoal text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </button>
            <button id="open-cart" class="relative text-sage-700 hover:text-sage-900 p-2 rounded-full hover:bg-sage-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <span id="cart-badge" class="hidden absolute -top-1 -right-1 bg-sage-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </button>
            @auth
            <div class="relative group hidden md:block">
                <button class="text-sage-700 hover:text-sage-900 p-2 rounded-full hover:bg-sage-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-50">
                    <a href="#" class="block px-4 py-2 text-sm text-sage-700 hover:bg-sage-50">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-sage-50">Logout</button></form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="text-sage-700 hover:text-sage-900 p-2 rounded-full hover:bg-sage-100 hidden md:flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </a>
            @endauth
            <div class="theme-toggle ml-1 hidden md:block" id="theme-toggle" onclick="toggleTheme()"></div>
            <button id="mobile-menu-btn" class="md:hidden text-sage-700 p-2 rounded-full hover:bg-sage-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() { navbar.classList.toggle('scrolled', window.scrollY > 50); });
        if (window.scrollY > 50) navbar.classList.add('scrolled');
    });
</script>