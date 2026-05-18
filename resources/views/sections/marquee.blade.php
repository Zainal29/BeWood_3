@php
    $marqueeItems = App\Models\MarqueeItem::where('is_active', true)->orderBy('order')->get();
@endphp

@if($marqueeItems->count())
@php
    // Ambil data marquee dari database
    $marqueeItems = App\Models\MarqueeItem::where('is_active', true)->orderBy('order')->get();
    // Default jika belum ada data
    $items = $marqueeItems->count() ? $marqueeItems->pluck('text')->toArray() : [
        'Kayu Jati Premium Bersertifikat',
        'Buatan Tangan Pengrajin Lokal',
        'Pengiriman Gratis Se-Indonesia',
        'Garansi 5 Tahun Penuh',
        'Konsultasi Desain Gratis'
    ];
    // Duplikasi untuk efek seamless
    $displayItems = array_merge($items, $items);
@endphp

<div class="bg-sage-700 text-white py-3 overflow-hidden relative group/marquee">
    <div class="relative w-full overflow-hidden">
        <!-- Gradient overlay -->
        <div class="absolute left-0 top-0 h-full w-20 z-10 pointer-events-none bg-gradient-to-r from-sage-700 to-transparent"></div>
        <div class="absolute right-0 top-0 h-full w-20 z-10 pointer-events-none bg-gradient-to-l from-sage-700 to-transparent"></div>

        <!-- Track marquee -->
        <div class="marquee-track flex w-max" style="animation-play-state: running;">
            <div class="flex items-center">
                @foreach($displayItems as $index => $text)
                    <span class="inline-flex items-center gap-2 mx-6 whitespace-nowrap">
                        @if($index % 2 == 0)
                            <svg class="w-4 h-4 text-sage-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-sage-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/>
                            </svg>
                        @endif
                        {{ $text }}
                    </span>
                    @if($index < count($displayItems) - 1)
                        <span class="text-sage-300 mx-2">✦</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
