@php
    use App\Models\WhyUsItem;
    use App\Models\WhyUsStat;
    use App\Models\WhyUsSetting;
    $title = WhyUsSetting::where('key', 'title')->first()->value ?? 'Mengapa BeWood?';
    $subtitle = WhyUsSetting::where('key', 'subtitle')->first()->value ?? 'Kami percaya furniture bukan sekadar benda, tapi warisan yang menemani cerita hidup Anda.';
    $items = WhyUsItem::where('is_active', true)->orderBy('order')->get();
    $stats = WhyUsStat::where('is_active', true)->orderBy('order')->get();
@endphp

<section id="tentang" class="py-28 px-6 lg:px-14 bg-sage-900 relative overflow-hidden">
    <div class="max-w-7xl mx-auto relative">
        <div class="text-center mb-16 reveal">
            <p class="text-xs tracking-widest uppercase font-sans font-semibold text-sage-400 mb-3">Filosofi Kami</p>
            <h2 class="font-serif text-4xl lg:text-5xl font-light text-white">{{ $title }}</h2>
            <span class="divider-sage" style="background: linear-gradient(90deg, transparent, #7da47d, transparent)"></span>
            <p class="font-sans text-sage-400 text-sm max-w-2xl mx-auto mt-4">{{ $subtitle }}</p>
        </div>
        <div class="grid md:grid-cols-3 gap-12">
            @foreach($items as $index => $item)
            <div class="text-center reveal delay-{{ ($index+1)*100 }}">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full border-2 border-sage-600 flex items-center justify-center bg-sage-800/50">
                    <!-- SVG icon default atau bisa custom per item nanti -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-sage-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        @if($loop->first)
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        @elseif($loop->iteration == 2)
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        @endif
                    </svg>
                </div>
                <h3 class="font-serif text-2xl text-white font-light mb-4">{{ $item->title }}</h3>
                <p class="font-sans text-sage-400 font-light leading-relaxed text-sm">{{ $item->description }}</p>
            </div>
            @endforeach
        </div>
        @if($stats->count())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 pt-14 border-t border-sage-800">
            @foreach($stats as $stat)
            <div class="text-center reveal delay-100">
                <p class="font-serif text-4xl text-sage-300 font-light">{{ $stat->value }}</p>
                <p class="font-sans text-xs tracking-widest uppercase text-sage-600 mt-2">{{ $stat->label }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
