@props([
    'product_id' => null,
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
    $originalPriceValue = $original_price ?? $price;
    $hasDiscount = $discount_price && $discount_price < $originalPriceValue;
    $discountPercent = $hasDiscount ? round((1 - ($discount_price / $originalPriceValue)) * 100) : 0;
    
    $productId = $product_id ?? abs(crc32($slug));
    
    $productData = [
        'id' => $productId,
        'slug' => $slug,
        'name' => $name,
        'price' => $finalPrice,
        'image' => $img,
        'description' => $desc,
        'category' => $category,
        'rating' => $rating,
        'reviews' => $reviews,
        'variant' => $variant,
    ];
@endphp

<div class="product-card group relative block bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-sage-100/50">
    
    {{-- IMAGE CONTAINER --}}
    <a href="{{ route('product.show', $slug) }}" class="relative overflow-hidden bg-gradient-to-br from-sage-50 to-cream aspect-square block">
        <img src="{{ $img }}" 
             alt="{{ $name }}" 
             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
             loading="lazy">
        
        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-sage-900/60 via-sage-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
        
        {{-- Badge --}}
        @if($badge)
        <div class="absolute top-4 left-4 z-10">
            <span class="inline-block px-3 py-1 text-[11px] font-bold tracking-wider uppercase rounded-full shadow-lg
                {{ $badge == 'Terlaris' ? 'bg-amber-500 text-sage-900' : '' }}
                {{ $badge == 'Baru' ? 'bg-emerald-600 text-white' : '' }}
                {{ $badge == 'Diskon' ? 'bg-red-500 text-white' : '' }}
                {{ !in_array($badge, ['Terlaris', 'Baru', 'Diskon']) ? 'bg-sage-800 text-white' : '' }}">
                {{ $badge }}
            </span>
        </div>
        @endif
        
        {{-- Discount Badge --}}
        @if($hasDiscount)
        <div class="absolute top-4 right-4 z-10">
            <div class="w-12 h-12 rounded-full bg-red-500 text-white flex items-center justify-center text-sm font-bold shadow-lg animate-pulse">
                -{{ $discountPercent }}%
            </div>
        </div>
        @endif
    </a>
    
    {{-- Quick Action Buttons (Muncul saat hover) --}}
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-3 opacity-0 translate-y-8 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-400 z-20">
        
        {{-- Quick View --}}
        <button type="button"
                onclick="event.preventDefault(); event.stopPropagation(); quickView(@json($productData))"
                class="quick-view-btn w-10 h-10 rounded-full bg-white/95 backdrop-blur-sm text-sage-700 flex items-center justify-center hover:bg-sage-600 hover:text-white transition-all duration-300 shadow-lg hover:scale-110">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>
        
        {{-- Wishlist --}}
        <button type="button"
                onclick="event.preventDefault(); event.stopPropagation(); addToWishlist(@json($productData), this)"
                class="wishlist-btn w-10 h-10 rounded-full bg-white/95 backdrop-blur-sm text-sage-700 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-300 shadow-lg hover:scale-110">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
            </svg>
        </button>
        
        {{-- Add to Cart --}}
        <button type="button"
                onclick="event.preventDefault(); event.stopPropagation(); addToCart(@json($productData))"
                class="add-to-cart-btn w-10 h-10 rounded-full bg-white/95 backdrop-blur-sm text-sage-700 flex items-center justify-center hover:bg-sage-600 hover:text-white transition-all duration-300 shadow-lg hover:scale-110">
            <svg class="w-4 h-4 transition-transform duration-300 hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
        </button>
    </div>
    
    {{-- CONTENT --}}
    <div class="p-5 bg-white">
        {{-- Category & Rating --}}
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-semibold tracking-wider uppercase text-amber-600 font-sans">
                {{ $category }}
            </span>
            @if($rating > 0)
            <div class="flex items-center gap-1">
                <div class="flex text-amber-500 text-[11px]">
                    @for($i=1; $i<=5; $i++)
                        @if($i <= round($rating))
                            ★
                        @elseif($i - 0.5 <= round($rating))
                            ½
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <span class="text-[10px] text-sage-400">({{ $reviews }})</span>
            </div>
            @endif
        </div>
        
        {{-- Title --}}
        <a href="{{ route('product.show', $slug) }}" class="block">
            <h3 class="font-serif text-xl font-medium text-sage-900 mb-1 line-clamp-1 group-hover:text-sage-600 transition-colors">
                {{ $name }}
            </h3>
        </a>
        
        {{-- Variant --}}
        <p class="text-xs text-sage-400 mb-3 font-sans">{{ $variant }}</p>
        
        {{-- Description --}}
        <p class="text-sm text-sage-500 leading-relaxed line-clamp-2 mb-4 font-sans">
            {{ Str::limit($desc, 80) }}
        </p>
        
        {{-- Price & CTA --}}
        <div class="flex items-center justify-between pt-3 border-t border-sage-100">
            <div class="space-y-0.5">
                @if($hasDiscount)
                <p class="text-xs text-sage-400 line-through">Rp {{ number_format($originalPriceValue, 0, ',', '.') }}</p>
                @endif
                <p class="font-serif text-xl font-semibold text-sage-800">
                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </p>
                @if($hasDiscount)
                <p class="text-[10px] text-emerald-600 font-medium">Hemat {{ $discountPercent }}%</p>
                @endif
            </div>
            <button type="button" 
                    onclick="event.preventDefault(); event.stopPropagation(); addToCart(@json($productData))"
                    class="add-to-cart-mobile w-10 h-10 rounded-full bg-sage-800 text-white flex items-center justify-center hover:bg-sage-600 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-110">
                <svg class="w-4 h-4 transition-transform duration-300 hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- Styles --}}
<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.animate-pulse {
    animation: pulse 1s ease-in-out 2;
}
</style>