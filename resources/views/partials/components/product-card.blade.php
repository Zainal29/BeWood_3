@props([
    'name',
    'price',
    'img',
    'desc',
    'category',
    'reviews',
    'badge' => null,
    'rating' => 0,
    'variant' => 'Default',
    'slug' => null,
    'discount_price' => null,
    'original_price' => null,
])

@php
    $slug = $slug ?? Str::slug($name);
    $finalPrice = $discount_price ?? $price;
    $hasDiscount = $discount_price && $discount_price < $price;
@endphp

<a href="{{ route('product.show', $slug) }}" class="product-card block bg-white rounded-xl overflow-hidden reveal group hover:shadow-xl transition-all duration-500">
    <div class="relative overflow-hidden aspect-square">
        <img src="{{ $img }}" alt="{{ $name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
        @if($badge)
        <div class="absolute top-3 left-3 {{ $badge == 'DISKON' ? 'bg-red-500' : 'bg-sage-700' }} text-white text-[10px] px-2.5 py-1 font-sans font-semibold tracking-wide rounded-full shadow-md">
            {{ $badge }}
        </div>
        @endif
        @if($hasDiscount)
        <div class="absolute top-3 right-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full shadow-md">-{{ round((1 - $discount_price / $original_price) * 100) }}%</div>
        @endif
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
        
        <!-- Action buttons -->
        <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
            <button class="wishlist-btn bg-white/95 rounded-full w-9 h-9 flex items-center justify-center hover:text-gold transition-colors shadow-md" onclick="event.preventDefault(); toggleWishlist(this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
            </button>
            <button class="quick-view-btn bg-white/95 rounded-full w-9 h-9 flex items-center justify-center hover:text-sage-600 transition-colors shadow-md" onclick="event.preventDefault(); openQuickView(this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="p-4">
        <p class="font-sans text-[10px] tracking-widest uppercase text-sage-400 font-semibold mb-1">{{ $category }}</p>
        <h4 class="font-serif text-lg text-sage-900 font-light mb-1 line-clamp-1">{{ $name }}</h4>
        @if($rating > 0)
        <div class="flex items-center gap-1 mb-2">
            <div class="stars flex text-gold text-xs">
                @for($i=1; $i<=5; $i++) {!! $i <= round($rating) ? '★' : ($i - 0.5 <= round($rating) ? '½' : '☆') !!} @endfor
            </div>
            <span class="text-xs text-sage-400">({{ $reviews }})</span>
        </div>
        @endif
        <div class="flex items-center justify-between mt-2">
            <div>
                @if($hasDiscount)
                <span class="line-through text-sage-400 text-xs">Rp {{ number_format($original_price, 0, ',', '.') }}</span>
                <p class="font-serif font-semibold text-sage-800">Rp {{ number_format($discount_price, 0, ',', '.') }}</p>
                @else
                <p class="font-serif font-semibold text-sage-800">Rp {{ number_format($price, 0, ',', '.') }}</p>
                @endif
            </div>
            <button class="add-to-cart bg-sage-700 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-sage-900 transition-all duration-200 shadow-md hover:shadow-lg" onclick="event.preventDefault(); addToCartFromCard(this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </button>
        </div>
    </div>
</a>