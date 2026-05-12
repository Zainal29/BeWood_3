@props(['name', 'price', 'img', 'desc', 'category', 'reviews', 'badge' => null, 'rating' => 4.5, 'variant' => 'Default', 'slug' => null, 'discount_price' => null])

@php $slug = $slug ?? Str::slug($name); @endphp

<a href="{{ route('product.show', $slug) }}" class="product-card block bg-white rounded-lg overflow-hidden reveal group hover:shadow-premium-hover transition-all duration-500"
   data-name="{{ $name }}" data-price="{{ $price }}" data-img="{{ $img }}" data-desc="{{ $desc }}" data-category="{{ $category }}" data-reviews="{{ $reviews }}" data-slug="{{ $slug }}">
    <div class="relative overflow-hidden aspect-square">
        <img src="{{ $img }}" alt="{{ $name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
        @if ($badge)
        <div class="absolute top-3 left-3 bg-sage-700 text-white text-[10px] px-2.5 py-1 font-sans font-semibold tracking-wide rounded-sm">{{ $badge }}</div>
        @endif
        <div class="absolute inset-0 bg-charcoal/0 group-hover:bg-charcoal/10 transition-colors duration-300"></div>
        <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0">
            <button class="wishlist-btn bg-white/95 rounded-full w-9 h-9 flex items-center justify-center hover:text-gold transition-colors shadow-sm" onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
            </button>
            <button class="quick-view-btn bg-white/95 rounded-full w-9 h-9 flex items-center justify-center hover:text-sage-600 transition-colors shadow-sm" onclick="event.preventDefault(); event.stopPropagation(); openQuickViewFromCard(this);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </button>
        </div>
    </div>
    <div class="p-5">
        <p class="font-sans text-[10px] tracking-widest uppercase text-sage-400 font-semibold mb-1">{{ $category }}</p>
        <h4 class="font-serif text-lg text-sage-900 font-light mb-1 line-clamp-1">{{ $name }}</h4>
        <p class="font-sans text-xs text-sage-400 mb-3">{{ $variant }}</p>
        <div class="flex items-center justify-between">
            <div>
                @if ($discount_price && $discount_price < $price)
                    <span class="line-through text-sage-400 text-xs">Rp {{ number_format($price, 0, ',', '.') }}</span>
                    <p class="font-serif font-semibold text-sage-800">Rp {{ number_format($discount_price, 0, ',', '.') }}</p>
                @else
                    <p class="font-serif font-semibold text-sage-800">Rp {{ number_format($price, 0, ',', '.') }}</p>
                @endif
            </div>
            <button class="add-to-cart bg-sage-700 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-sage-900 transition-colors shrink-0 shadow-sm" onclick="event.preventDefault(); event.stopPropagation(); addToCartFromCard(this);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            </button>
        </div>
    </div>
</a>