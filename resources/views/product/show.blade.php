{{-- resources/views/product/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - BeWood')

@section('content')
    {{-- BREADCRUMB --}}
    <div class="pt-24 pb-4 px-6 lg:px-14 bg-cream">
        <div class="max-w-7xl mx-auto">
            <nav class="flex items-center gap-2 font-sans text-xs text-sage-500">
                <a href="{{ route('landing') }}" class="hover:text-sage-700 transition-colors">Beranda</a>
                <span class="bc-sep">›</span>
                <a href="{{ route('landing') }}#produk" class="hover:text-sage-700 transition-colors">{{ $product->category->name ?? 'Kategori' }}</a>
                <span class="bc-sep">›</span>
                <a href="{{ route('landing') }}#produk" class="hover:text-sage-700 transition-colors">Produk</a>
                <span class="bc-sep">›</span>
                <span class="text-sage-700 font-medium">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <main class="px-6 lg:px-14 py-10 bg-cream">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 xl:gap-20">

                <!-- LEFT: GALLERY -->
                <div class="flex flex-col gap-4">
                    <div id="main-img-wrap" class="relative overflow-hidden bg-parchment aspect-[4/3] lg:aspect-[3/3.2] cursor-pointer">
                        <img id="main-img" src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                        <div class="absolute bottom-4 right-4 bg-white/80 backdrop-blur-sm px-3 py-1.5 flex items-center gap-1.5 text-xs font-sans text-sage-600 shadow-sm pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                            </svg>
                            Perbesar
                        </div>
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            @if ($product->is_bestseller)
                                <span class="bg-sage-700 text-white text-xs px-3 py-1 font-sans font-medium tracking-wide">TERLARIS</span>
                            @endif
                            @if ($product->is_new)
                                <span class="bg-gold text-white text-xs px-3 py-1 font-sans font-medium tracking-wide">NEW</span>
                            @endif
                        </div>
                        <button id="wishlist-btn-detail" onclick="toggleWishlistDetail(event)" class="absolute top-4 right-4 bg-white/90 rounded-full w-9 h-9 flex items-center justify-center text-sage-400 hover:text-red-400 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" id="heart-icon-detail" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-5 gap-2">
                        @php
                            $mainImage = Storage::url($product->main_image);
                            $thumbnails = $product->images->map(fn($img) => Storage::url($img->image));
                            $allThumbs = collect([$mainImage])->concat($thumbnails);
                        @endphp
                        @foreach ($allThumbs->take(5) as $idx => $thumb)
                            <div class="thumb-item {{ $idx === 0 ? 'active' : '' }} overflow-hidden aspect-square bg-parchment" data-src="{{ $thumb }}" onclick="switchImg(this)">
                                <img src="{{ $thumb }}" class="w-full h-full object-cover" alt="Thumbnail {{ $idx + 1 }}" />
                            </div>
                        @endforeach
                        @for ($i = $allThumbs->count(); $i < 5; $i++)
                            <div class="thumb-item opacity-40 overflow-hidden aspect-square bg-parchment pointer-events-none"></div>
                        @endfor
                    </div>
                </div>

                <!-- RIGHT: PRODUCT INFO -->
                <div class="flex flex-col gap-6">
                    <div>
                        <p class="font-sans text-xs tracking-widest uppercase text-sage-500 mb-2">
                            {{ $product->category->name ?? 'Kategori' }} · {{ $product->subCategory ?? 'Produk' }}
                        </p>
                        <h1 class="font-serif text-3xl lg:text-4xl font-light text-sage-900 leading-tight mb-3">
                            {{ $product->name }}<br /><em class="not-italic text-sage-600">{{ $product->series ?? 'Koleksi Premium' }}</em>
                        </h1>
                        <div class="flex items-center gap-3 mb-1">
                            <div class="stars flex gap-0.5">{!! str_repeat('★', round($product->rating)) !!}{!! str_repeat('☆', 5 - round($product->rating)) !!}</div>
                            <span class="font-sans text-sm font-medium text-sage-800">{{ number_format($product->rating, 1) }}</span>
                            <span class="font-sans text-xs text-sage-400">({{ $product->reviews_count }} ulasan)</span>
                            <span class="text-sage-200">·</span>
                            <span class="font-sans text-xs text-sage-500">{{ $product->sold }} terjual</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="w-2 h-2 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500' : 'bg-red-500' }} inline-block"></span>
                            <span class="font-sans text-xs text-sage-600 font-medium">Stok {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}</span>
                            @if ($product->stock > 0)
                                <span class="font-sans text-xs text-sage-400">· Tersisa {{ $product->stock }} unit</span>
                            @endif
                        </div>
                    </div>

                    <div class="py-4 border-y border-sage-100">
                        @php
                            $finalPrice = $product->discount_price ?? $product->price;
                            $discountPercent = $product->discount_price ? round((1 - $product->discount_price / $product->price) * 100) : 0;
                        @endphp
                        <div class="flex items-end gap-3">
                            <p class="font-serif text-4xl text-sage-900 font-light">Rp {{ number_format($finalPrice, 0, ',', '.') }}</p>
                            @if ($product->discount_price)
                                <p class="font-sans text-sm text-sage-400 line-through mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <span class="bg-red-50 text-red-600 text-xs font-sans font-semibold px-2 py-1 mb-1">-{{ $discountPercent }}%</span>
                            @endif
                        </div>
                        <p class="font-sans text-xs text-sage-400 mt-1">atau cicil mulai <span class="text-sage-700 font-semibold">Rp {{ number_format($finalPrice / 12, 0, ',', '.') }}/bln</span> (12 bulan)</p>
                    </div>

                    <p class="font-sans text-sm text-sage-600 leading-relaxed font-light">{{ $product->description }}</p>

                    <!-- Variant Picker -->
                    @php
                        $colorVariants = $product->variants->where('type', 'warna')->values();
                        $finishVariants = $product->variants->where('type', 'finishing')->values();
                    @endphp
                    @if ($colorVariants->count())
                        <div>
                            <p class="font-sans text-xs tracking-widest uppercase text-sage-500 mb-3 font-medium">Warna Kain: <span id="swatch-label" class="text-sage-800 font-semibold normal-case tracking-normal">{{ $colorVariants->first()->value }}</span></p>
                            <div class="flex gap-3 flex-wrap">
                                @foreach ($colorVariants as $variant)
                                    <div class="swatch {{ $loop->first ? 'active' : '' }}" style="background:{{ $variant->color_code ?? '#2D6A4F' }};" data-name="{{ $variant->value }}" onclick="selectSwatch(this)" title="{{ $variant->value }}"></div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($finishVariants->count())
                        <div>
                            <p class="font-sans text-xs tracking-widest uppercase text-sage-500 mb-3 font-medium">Finishing Kayu: <span id="finish-label" class="text-sage-800 font-semibold normal-case tracking-normal">{{ $finishVariants->first()->value }}</span></p>
                            <div class="flex gap-2 flex-wrap">
                                @foreach ($finishVariants as $variant)
                                    <button class="finish-btn font-sans text-xs px-4 py-2 border {{ $loop->first ? 'border-sage-700 bg-sage-700 text-white' : 'border-sage-200 text-sage-600' }} font-medium transition-all duration-200 hover:border-sage-400" data-name="{{ $variant->value }}" onclick="selectFinish(this)">{{ $variant->value }}</button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity -->
                    <div class="flex items-center gap-4">
                        <p class="font-sans text-xs tracking-widest uppercase text-sage-500 font-medium">Qty:</p>
                        <div class="flex items-center">
                            <button class="qty-btn" onclick="changeQty(-1)">−</button>
                            <span id="qty-display" class="font-sans text-sm font-semibold text-sage-900 w-10 text-center">1</span>
                            <button class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                        <p class="font-sans text-xs text-sage-400">Maks. {{ min(5, $product->stock) }} unit / pemesanan</p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col gap-3">
                        <button id="main-atc-btn" onclick="addToCartMain()" class="btn-sage w-full py-4 text-sm font-sans font-semibold flex items-center justify-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            TAMBAHKAN KE KERANJANG
                        </button>
                        <a href="https://wa.me/6281234567890?text=Halo%20BeWood%2C%20saya%20tertarik%20dengan%20{{ urlencode($product->name) }}" target="_blank" class="btn-wa w-full py-4 text-sm font-sans font-semibold flex items-center justify-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.556 4.116 1.529 5.845L.057 23.617a.5.5 0 00.612.612l5.772-1.472A11.957 11.957 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.6a9.593 9.593 0 01-4.883-1.335l-.35-.208-3.627.925.954-3.527-.228-.362A9.558 9.558 0 012.4 12C2.4 6.698 6.698 2.4 12 2.4S21.6 6.698 21.6 12 17.302 21.6 12 21.6z" />
                            </svg>
                            CHAT WHATSAPP KAMI
                        </a>
                    </div>

                    <!-- Trust badges -->
                    <div class="grid grid-cols-3 gap-3 pt-2 border-t border-sage-100">
                        <div class="flex flex-col items-center text-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sage-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                            <p class="font-sans text-xs text-sage-600 font-medium">Gratis Ongkir</p>
                            <p class="font-sans text-xs text-sage-400">Se-Indonesia</p>
                        </div>
                        <div class="flex flex-col items-center text-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sage-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            <p class="font-sans text-xs text-sage-600 font-medium">Garansi 5 Thn</p>
                            <p class="font-sans text-xs text-sage-400">Cacat struktural</p>
                        </div>
                        <div class="flex flex-col items-center text-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sage-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <p class="font-sans text-xs text-sage-600 font-medium">Retur 30 Hari</p>
                            <p class="font-sans text-xs text-sage-400">Tanpa syarat</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS (Deskripsi dan Ulasan) -->
            <div class="mt-20">
                <div class="border-b border-sage-200 flex gap-8 mb-10">
                    <button class="tab-btn active pb-4 font-sans text-sm font-medium text-sage-500" data-tab="deskripsi" onclick="switchTab('deskripsi', this)">Deskripsi Lengkap</button>
                    <button class="tab-btn pb-4 font-sans text-sm font-medium text-sage-500" data-tab="ulasan" onclick="switchTab('ulasan', this)">Ulasan ({{ $product->reviews_count }})</button>
                </div>

                <div id="tab-deskripsi" class="tab-content">
                    <div class="max-w-3xl">
                        <h3 class="font-serif text-2xl font-light text-sage-900 mb-6">Tentang Produk Ini</h3>
                        <div class="prose font-sans text-sm text-sage-700 font-light leading-relaxed space-y-5">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>

                <div id="tab-ulasan" class="tab-content hidden">
                    <div class="grid md:grid-cols-3 gap-12">
                        <div class="text-center">
                            <p class="font-serif text-7xl font-light text-sage-900">{{ number_format($product->rating, 1) }}</p>
                            <div class="stars flex gap-1 justify-center my-3">{!! str_repeat('★', round($product->rating)) !!}{!! str_repeat('☆', 5 - round($product->rating)) !!}</div>
                            <p class="font-sans text-sm text-sage-500">{{ $product->reviews_count }} ulasan</p>
                        </div>
                        <div class="md:col-span-2 space-y-6">
                            <div class="text-center text-sage-500 py-8">Ulasan pelanggan akan segera hadir.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Produk Serupa (Dinamis berdasarkan kategori) --}}
    @php
        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();
    @endphp

    @if ($relatedProducts->count())
        <section class="py-20 px-6 lg:px-14 bg-parchment">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div>
                        <p class="text-xs tracking-widest uppercase font-sans font-medium text-sage-500 mb-2">Mungkin Kamu Suka</p>
                        <h2 class="font-serif text-3xl lg:text-4xl font-light text-sage-900">Produk Serupa</h2>
                    </div>
                    <a href="{{ route('landing', ['category' => $product->category->slug]) }}" class="btn-outline-sage px-7 py-3 text-xs font-sans font-semibold inline-block self-start md:self-auto">LIHAT SEMUA →</a>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $related)
                        @include('partials.components.product-card', [
                            'name' => $related->name,
                            'price' => $related->discount_price ?? $related->price,
                            'img' => Storage::url($related->main_image),
                            'desc' => $related->description,
                            'category' => $related->category->name ?? 'Umum',
                            'reviews' => $related->reviews_count,
                            'badge' => $related->is_bestseller ? 'TERLARIS' : ($related->is_new ? 'BARU' : null),
                            'variant' => $related->variants->first()->value ?? 'Default',
                            'slug' => $related->slug,
                        ])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Modal Zoom --}}
    <div id="zoom-overlay" class="fixed inset-0 bg-black/90 z-[9999] hidden items-center justify-center cursor-pointer">
        <img id="zoom-img" src="" alt="Zoom" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl cursor-default" />
        <button id="zoom-close-btn" class="absolute top-6 right-6 w-12 h-12 bg-black/50 hover:bg-black/70 text-white text-3xl rounded-full transition-all flex items-center justify-center">✕</button>
    </div>

    {{-- Data untuk JavaScript (cart, wishlist) --}}
    <script>
        window.currentProduct = {
            id: {{ $product->id }},
            name: @json($product->name),
            price: {{ $product->discount_price ?? $product->price }},
            image: @json(Storage::url($product->main_image))
        };
    </script>
@endsection
