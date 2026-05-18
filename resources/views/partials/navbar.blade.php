@php
    $navMenus = App\Models\NavMenu::where('is_active', true)->orderBy('order')->get();
    $isHomePage = request()->routeIs('landing');
@endphp

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 py-4 md:py-5 {{ $isHomePage ? '' : 'scrolled' }}">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 lg:px-8">
        <a href="{{ route('landing') }}" class="nav-logo font-serif text-2xl tracking-widest font-light transition-all duration-300">
            Be<span class="{{ $isHomePage ? 'text-sage-300' : 'text-sage-500' }}">Wood</span>
        </a>

        <ul class="hidden md:flex items-center gap-10 text-xs tracking-widest uppercase font-sans font-medium">
            @foreach($navMenus as $item)
            <li>
                <a href="{{ url($item->url) }}" class="transition-colors relative group">
                    {{ $item->label }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            @endforeach
        </ul>

        <div class="flex items-center gap-3">
            {{-- <button id="search-btn" class="transition-colors p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </button> --}}
            <button id="wishlist-btn" class="relative transition-colors p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                <span id="wishlist-badge" class="hidden absolute -top-1 -right-1 bg-gold text-charcoal text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </button>
            <button id="open-cart" class="relative transition-colors p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <span id="cart-badge" class="hidden absolute -top-1 -right-1 bg-sage-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </button>

            {{-- <div class="theme-toggle ml-1 hidden md:block" id="theme-toggle" onclick="toggleTheme()"></div>
            <button id="mobile-menu-btn" class="md:hidden transition-colors p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div> --}}
    </div>



    <div id="mobile-menu" class="hidden md:hidden mt-3 bg-white/95 backdrop-blur-md border border-sage-200 px-6 py-4 space-y-3 rounded-lg shadow-lg mx-6">
        @foreach($navMenus as $item)
        <a href="{{ url($item->url) }}" class="block text-sm font-medium text-sage-800 tracking-wide hover:text-sage-500 py-2">{{ $item->label }}</a>
        @endforeach
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const isHomePage = {{ $isHomePage ? 'true' : 'false' }};

        function updateNavbarTheme() {
            if (!isHomePage) {
                // Halaman selain beranda: langsung putih solid, teks gelap
                navbar.style.background = '#ffffff';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
                navbar.style.backdropFilter = 'none';
                document.body.classList.add('non-home-navbar');
                document.body.classList.remove('home-top', 'home-scrolled');
                return;
            }

            // Halaman beranda: tergantung scroll
            if (window.scrollY > 50) {
                navbar.style.background = '#ffffff';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
                navbar.style.backdropFilter = 'none';
                document.body.classList.add('home-scrolled');
                document.body.classList.remove('home-top');
            } else {
                navbar.style.background = 'rgba(0, 0, 0, 0.25)';
                navbar.style.backdropFilter = 'blur(10px)';
                navbar.style.boxShadow = 'none';
                document.body.classList.add('home-top');
                document.body.classList.remove('home-scrolled');
            }
        }

        if (isHomePage) {
            window.addEventListener('scroll', updateNavbarTheme);
            updateNavbarTheme();
        } else {
            updateNavbarTheme(); // langsung putih solid
        }

        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>

<style>
    /* Default transition */
    #navbar .nav-logo,
    #navbar ul li a,
    #navbar .flex button:not(.theme-toggle),
    #navbar .flex a {
        transition: all 0.2s ease;
    }

    /* ==================== Halaman lain (non-home) ==================== */
    body.non-home-navbar #navbar .nav-logo,
    body.non-home-navbar #navbar ul li a,
    body.non-home-navbar #navbar .flex button:not(.theme-toggle),
    body.non-home-navbar #navbar .flex a {
        color: #2c4b3e;
    }
    body.non-home-navbar #navbar .nav-logo span {
        color: #6b8f6e;
    }
    body.non-home-navbar #navbar .flex button svg,
    body.non-home-navbar #navbar .flex a svg {
        stroke: #2c4b3e;
    }
    body.non-home-navbar #navbar .flex button:hover,
    body.non-home-navbar #navbar .flex a:hover {
        background: rgba(0, 0, 0, 0.04);
    }
    body.non-home-navbar #navbar ul li a:hover {
        color: #0f2d23;
    }
    body.non-home-navbar #navbar ul li a span {
        background-color: #6b8f6e;
    }

    /* ==================== Halaman beranda - posisi atas (transparan) ==================== */
    body.home-top #navbar .nav-logo,
    body.home-top #navbar ul li a,
    body.home-top #navbar .flex button:not(.theme-toggle),
    body.home-top #navbar .flex a {
        color: #ffffff;
    }
    body.home-top #navbar .nav-logo span {
        color: #d4e0d4;
    }
    body.home-top #navbar .flex button svg,
    body.home-top #navbar .flex a svg {
        stroke: #ffffff;
    }
    body.home-top #navbar .flex button:hover,
    body.home-top #navbar .flex a:hover {
        background: rgba(255, 255, 255, 0.15);
    }
    body.home-top #navbar ul li a:hover {
        color: #f0f5f0;
    }
    body.home-top #navbar ul li a span {
        background-color: #d4e0d4;
    }

    /* ==================== Halaman beranda - discroll (putih solid teks gelap) ==================== */
    body.home-scrolled #navbar .nav-logo,
    body.home-scrolled #navbar ul li a,
    body.home-scrolled #navbar .flex button:not(.theme-toggle),
    body.home-scrolled #navbar .flex a {
        color: #2c4b3e;
    }
    body.home-scrolled #navbar .nav-logo span {
        color: #6b8f6e;
    }
    body.home-scrolled #navbar .flex button svg,
    body.home-scrolled #navbar .flex a svg {
        stroke: #2c4b3e;
    }
    body.home-scrolled #navbar .flex button:hover,
    body.home-scrolled #navbar .flex a:hover {
        background: rgba(0, 0, 0, 0.04);
    }
    body.home-scrolled #navbar ul li a:hover {
        color: #0f2d23;
    }
    body.home-scrolled #navbar ul li a span {
        background-color: #6b8f6e;
    }
</style>
