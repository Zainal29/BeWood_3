@if(isset($categories) && count($categories) > 0)
<section class="py-28 px-6 lg:px-14 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto relative">
        <div class="text-center mb-16 reveal">
            <p class="text-xs tracking-widest uppercase font-sans font-semibold text-sage-500 mb-3">Jelajahi Koleksi</p>
            <h2 class="font-serif text-4xl lg:text-5xl font-light text-sage-900">Kategori Unggulan</h2>
            <span class="divider-sage"></span>
            <p class="font-sans text-sage-500 text-sm max-w-2xl mx-auto mt-4">Setiap kategori dirancang dengan perhatian penuh terhadap detail, fungsi, dan estetika timeless.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($categories as $index => $cat)
            <a href="{{ route('landing', ['category' => $cat->slug]) }}" 
               class="cat-card group relative overflow-hidden cursor-pointer reveal delay-{{ $index * 100 }}">
                <div class="aspect-[4/5] overflow-hidden">
                    <img src="{{ Storage::url($cat->image) }}" 
                         alt="{{ $cat->name }}" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                         loading="lazy">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-sage-950/80 via-sage-950/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-7">
                    <p class="font-sans text-xs tracking-widest uppercase text-sage-300 mb-1">{{ sprintf('%02d', $index+1) }}</p>
                    <h3 class="font-serif text-2xl text-white font-light">{{ $cat->name }}</h3>
                    <p class="font-sans text-xs text-white/70 mt-1">{{ $cat->products_count }} produk</p>
                </div>
                <div class="absolute bottom-7 right-7 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-6 h-6 text-sage-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif