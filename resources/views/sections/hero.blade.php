@php
    use App\Models\Setting;
    $hero = Setting::all()->pluck('value', 'key')->toArray();
@endphp

<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden group">
    <div class="absolute inset-0 z-0">
        @php
            $heroImage = $hero['hero_image'] ?? 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2000&auto=format&fit=crop';
        @endphp
        @if(isset($hero['hero_image']) && !filter_var($hero['hero_image'], FILTER_VALIDATE_URL))
            <img src="{{ Storage::url($hero['hero_image']) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
        @else
            <img src="{{ $heroImage }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
        @endif
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-sage-950/60 via-sage-950/30 to-sage-950/70"></div>
    <div class="relative z-10 text-center px-4 py-12 max-w-7xl mx-auto">
        <div class="flex justify-center items-center gap-2 text-xs md:text-sm font-sans font-semibold tracking-[0.35em] uppercase mb-6 text-sage-200/90">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <span>{{ $hero['hero_top_text'] ?? 'Koleksi Furniture Premium 2025' }}</span>
        </div>
        <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl font-light leading-tight mb-7 text-white">
            {{ $hero['hero_title_top'] ?? 'Ruang yang' }}<br>
            <em class="not-italic text-sage-300 font-normal">{{ $hero['hero_title_bottom'] ?? 'Bercerita' }}</em>
        </h1>
        <p class="font-sans font-light text-white/90 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
            {{ $hero['hero_description'] ?? 'Dari tangan pengrajin terbaik Indonesia — setiap detail dirancang dengan hati untuk menciptakan rumah yang benar-benar terasa seperti rumah.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('landing') }}#produk" class="btn-primary px-9 py-4 text-xs font-sans">LIHAT KOLEKSI</a>
            <a href="{{ route('landing') }}#tentang" class="btn-outline px-9 py-4 text-xs font-sans">CERITA KAMI</a>
        </div>
        <div class="mt-12 flex flex-wrap justify-center gap-6 text-white/75 text-xs font-sans">
            <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M12 3v18m0-18c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z"/></svg> {{ $hero['hero_badge_1_text'] ?? 'Garansi 5 Tahun' }}</span>
            <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg> {{ $hero['hero_badge_2_text'] ?? 'Pengiriman Gratis' }}</span>
            <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ $hero['hero_badge_3_text'] ?? 'Konsultasi Gratis' }}</span>
        </div>
    </div>
</section>
