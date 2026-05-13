@extends('layouts.app')

@section('title', 'Koleksi Produk - BeWood')

@section('content')

<div class="relative overflow-hidden pt-32 pb-24 px-6 lg:px-14 min-h-screen bg-gradient-to-b from-[#f8f6f1] via-[#fdfcf9] to-[#f4f1ea]">

    <!-- Ambient Background -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-sage-200/20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-[30rem] h-[30rem] bg-amber-100/20 blur-3xl rounded-full"></div>

    <div class="max-w-7xl mx-auto relative z-10">

        <!-- Hero Section -->
        <div class="text-center mb-16 relative z-10">

            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/80 backdrop-blur border border-sage-200 text-sage-700 text-xs tracking-[0.2em] uppercase shadow-sm">
                Premium Furniture Collection
            </span>

            <h1 class="mt-6 font-serif text-5xl lg:text-7xl text-sage-900 font-light tracking-tight leading-tight">
                Koleksi
                <span class="italic text-sage-500">Eksklusif</span>
            </h1>

            <p class="text-sage-500 mt-6 max-w-2xl mx-auto font-sans text-[15px] leading-relaxed">
                Furnitur modern dengan sentuhan craftsmanship premium untuk menghadirkan
                kenyamanan, estetika, dan karakter pada setiap ruang Anda.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-6 mt-8 text-sm text-sage-500">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Handmade Quality
                </div>

                <div class="hidden md:block w-px h-4 bg-sage-300"></div>

                <div>100% Premium Material</div>

                <div class="hidden md:block w-px h-4 bg-sage-300"></div>

                <div>Luxury Interior Style</div>
            </div>

        </div>

        <!-- Filter & Sort Section -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white/60 shadow-[0_10px_60px_rgba(0,0,0,0.06)] p-6 mb-10">

            <div class="flex flex-col lg:flex-row justify-between gap-6">

                <!-- Search -->
                <form method="GET" action="{{ route('products') }}" class="flex-1">
                    <div class="relative group">
                        <input
                            type="text"
                            name="search"
                            placeholder="Cari produk premium..."
                            value="{{ request('search') }}"
                            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-sage-200 bg-white/70 backdrop-blur focus:border-sage-400 focus:ring-4 focus:ring-sage-100 transition outline-none text-sage-700"
                        >

                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-sage-400 group-focus-within:text-sage-600 transition"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.5"
                                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>

                        @if(request('search'))
                            <a href="{{ route('products', array_merge(request()->except('search'), ['search' => null])) }}"
                               class="absolute right-4 top-1/2 -translate-y-1/2 text-sage-400 hover:text-red-500 transition">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filters -->
                <div class="flex flex-wrap gap-3">

                    <!-- Category -->
                    <div class="relative">
                        <select
                            id="category-filter"
                            class="appearance-none px-5 py-3 pr-10 rounded-2xl border border-sage-200 bg-white/70 backdrop-blur text-sage-700 focus:border-sage-400 focus:ring-4 focus:ring-sage-100 outline-none cursor-pointer transition"
                        >
                            <option value="">Semua Kategori</option>

                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}"
                                    {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>

                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sage-400 pointer-events-none"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- Sort -->
                    <div class="relative">
                        <select
                            id="sort-filter"
                            class="appearance-none px-5 py-3 pr-10 rounded-2xl border border-sage-200 bg-white/70 backdrop-blur text-sage-700 focus:border-sage-400 focus:ring-4 focus:ring-sage-100 outline-none cursor-pointer transition"
                        >
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        </select>

                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sage-400 pointer-events-none"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- Mobile Toggle -->
                    <button
                        id="filter-toggle"
                        class="lg:hidden px-5 py-3 rounded-2xl border border-sage-200 bg-white/70 backdrop-blur text-sage-700 hover:bg-white transition flex items-center gap-2"
                    >
                        Filter
                    </button>

                </div>
            </div>

            <!-- Active Filters -->
            @if(request('search') || request('category') || request('sort') != 'latest')
                <div class="flex flex-wrap gap-2 mt-5 pt-5 border-t border-sage-100">

                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-sage-100 text-sage-700 text-xs">
                            "{{ request('search') }}"
                        </span>
                    @endif

                    @if(request('category'))
                        @php
                            $catName = $categories->firstWhere('slug', request('category'))->name ?? '';
                        @endphp

                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-sage-100 text-sage-700 text-xs">
                            {{ $catName }}
                        </span>
                    @endif

                    <a href="{{ route('products') }}"
                       class="text-xs text-sage-500 hover:text-sage-700 underline">
                        Reset semua filter
                    </a>

                </div>
            @endif

        </div>

        <!-- Product Stats -->
        <div class="flex flex-wrap items-center justify-between mb-10 gap-4">

            <div>
                <h2 class="font-serif text-2xl text-sage-900">
                    Menampilkan
                    <span class="text-sage-500">{{ $products->total() }}</span>
                    Produk
                </h2>

                <p class="text-sm text-sage-500 mt-1">
                    Koleksi pilihan dengan kualitas premium
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">

                <div class="px-4 py-2 rounded-2xl bg-white shadow-sm border border-sage-100 text-sm text-sage-700">
                    ✨ Premium Design
                </div>

                <div class="px-4 py-2 rounded-2xl bg-white shadow-sm border border-sage-100 text-sm text-sage-700">
                    🚚 Fast Delivery
                </div>

            </div>

        </div>

        <!-- Divider -->
        <div class="flex items-center gap-4 mb-10">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sage-300 to-transparent"></div>

            <span class="text-sage-500 text-xs tracking-[0.3em] uppercase">
                Curated Collection
            </span>

            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sage-300 to-transparent"></div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">

            @forelse($products as $product)

                <div class="group bg-white/80 backdrop-blur-xl rounded-[2rem] overflow-hidden border border-white/70 shadow-[0_10px_40px_rgba(0,0,0,0.05)] hover:-translate-y-2 hover:shadow-[0_25px_80px_rgba(0,0,0,0.12)] transition-all duration-500 flex flex-col h-full">

                    <!-- Image -->
                    <div class="relative overflow-hidden aspect-[4/3] bg-sage-50">

                        <img
                            src="{{ Storage::url($product->main_image) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                        >

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>

                        <!-- Badge -->
                        @if($product->is_bestseller)
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-sage-800 text-white text-[11px] uppercase tracking-wide">
                                Terlaris
                            </span>
                        @elseif($product->is_new)
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-emerald-600 text-white text-[11px] uppercase tracking-wide">
                                Baru
                            </span>
                        @elseif($product->discount_price)
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-red-500 text-white text-[11px] uppercase tracking-wide">
                                Diskon
                            </span>
                        @endif

                        <!-- Wishlist -->
                        <button class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/80 backdrop-blur flex items-center justify-center shadow-md hover:bg-white transition">
                            <svg class="w-5 h-5 text-sage-700"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.7"
                                      d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364 4.318 12.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </button>

                        <!-- Quick View -->
                        <div class="absolute inset-x-4 bottom-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition duration-500">
                            <a href="{{ route('product.show', $product->slug) }}"
                               class="block text-center py-3 rounded-2xl bg-white text-sage-900 font-medium shadow-xl">
                                Quick View
                            </a>
                        </div>

                    </div>

                    <!-- Content -->
                    <div class="flex flex-col flex-1 p-6">

                        <!-- Category -->
                        <p class="text-xs uppercase tracking-[0.2em] text-sage-400 mb-2">
                            {{ $product->category->name ?? 'Umum' }}
                        </p>

                        <!-- Title -->
                        <h3 class="font-serif text-2xl text-sage-900 leading-snug mb-3 line-clamp-2 min-h-[64px]">
                            {{ $product->name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-sage-500 leading-relaxed mb-5 line-clamp-3 flex-1">
                            {{ $product->description }}
                        </p>

                        <!-- Rating -->
                        <div class="flex items-center gap-2 mb-5">
                            <div class="flex text-amber-400 text-sm">
                                ★★★★★
                            </div>

                            <span class="text-xs text-sage-400">
                                ({{ $product->reviews_count ?? 0 }} ulasan)
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="flex items-end justify-between mt-auto gap-4">

                            <div>

                                @if($product->discount_price)
                                    <p class="text-sm text-sage-400 line-through">
                                        Rp {{ number_format($product->price,0,',','.') }}
                                    </p>
                                @endif

                                <p class="text-2xl font-semibold text-sage-800">
                                    Rp {{ number_format($product->discount_price ?? $product->price,0,',','.') }}
                                </p>

                            </div>

                            <a href="{{ route('product.show', $product->slug) }}"
                               class="px-5 py-2.5 rounded-2xl bg-sage-700 text-white text-sm hover:bg-sage-900 transition-all duration-300 shadow-lg">
                                Detail
                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-24">

                    <div class="w-32 h-32 mx-auto rounded-full bg-white shadow-lg flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-sage-300"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.5"
                                  d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375"/>
                        </svg>
                    </div>

                    <h3 class="font-serif text-3xl text-sage-900 mb-3">
                        Produk Tidak Ditemukan
                    </h3>

                    <p class="text-sage-500 max-w-md mx-auto">
                        Coba gunakan kata kunci lain atau ubah filter pencarian Anda.
                    </p>

                </div>

            @endforelse

        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            {{ $products->links() }}
        </div>

    </div>

</div>

<!-- Mobile Filter Sidebar -->
<div id="mobile-filter-sidebar"
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden lg:hidden">

    <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-2xl p-6 overflow-y-auto">

        <div class="flex justify-between items-center mb-6 pb-4 border-b border-sage-100">
            <h3 class="font-serif text-xl text-sage-900">
                Filter
            </h3>

            <button id="close-filter"
                    class="text-sage-400 hover:text-sage-600 transition">
                ✕
            </button>
        </div>

        <div class="space-y-6">

            <div>
                <label class="block text-sm font-semibold text-sage-700 mb-3">
                    Kategori
                </label>

                <div class="space-y-2">

                    @foreach($categories as $cat)
                        <a href="{{ route('products', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                           class="block text-sage-600 hover:text-sage-900 transition">
                            {{ $cat->name }}
                        </a>
                    @endforeach

                </div>
            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const categoryFilter = document.getElementById('category-filter');
    const sortFilter = document.getElementById('sort-filter');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);

        if (categoryFilter.value && categoryFilter.value !== '') {
            params.set('category', categoryFilter.value);
        } else {
            params.delete('category');
        }

        if (sortFilter.value && sortFilter.value !== 'latest') {
            params.set('sort', sortFilter.value);
        } else {
            params.delete('sort');
        }

        window.location.search = params.toString();
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', applyFilters);
    }

    if (sortFilter) {
        sortFilter.addEventListener('change', applyFilters);
    }

    // Mobile Sidebar
    const filterToggle = document.getElementById('filter-toggle');
    const mobileSidebar = document.getElementById('mobile-filter-sidebar');
    const closeFilter = document.getElementById('close-filter');

    if (filterToggle) {
        filterToggle.addEventListener('click', () => {
            mobileSidebar.classList.remove('hidden');
        });
    }

    if (closeFilter) {
        closeFilter.addEventListener('click', () => {
            mobileSidebar.classList.add('hidden');
        });
    }

    if (mobileSidebar) {
        mobileSidebar.addEventListener('click', (e) => {
            if (e.target === mobileSidebar) {
                mobileSidebar.classList.add('hidden');
            }
        });
    }

});
</script>

<style>

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Pagination Premium */

.pagination {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.7rem;
    height: 2.7rem;
    padding: 0 0.85rem;
    border-radius: 1rem;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.7);
    color: #456945;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}

.pagination .page-link:hover {
    transform: translateY(-2px);
    background: white;
    border-color: #a9c3a9;
}

.pagination .active .page-link {
    background-color: #5a865a;
    border-color: #5a865a;
    color: white;
    box-shadow: 0 10px 30px rgba(90,134,90,0.3);
}

.pagination .disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
}

</style>

@endsection