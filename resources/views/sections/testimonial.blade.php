@php
    use App\Models\Testimonial;
    use App\Models\Setting;
    $title = Setting::where('key', 'testimonials_title')->first()->value ?? 'Kata Mereka';
    $subtitle = Setting::where('key', 'testimonials_subtitle')->first()->value ?? 'Pengalaman nyata dari keluarga Indonesia yang telah mempercayakan rumah mereka pada BeWood.';
    $testimonials = Testimonial::where('is_active', true)->orderBy('order')->get();
@endphp

@if($testimonials->count())
<section class="py-28 px-6 lg:px-14 bg-parchment">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 reveal">
            <p class="text-xs tracking-widest uppercase font-sans font-semibold text-sage-500 mb-3">{{ $title }}</p>
            <h2 class="font-serif text-4xl lg:text-5xl font-light text-sage-900">{{ $title }}</h2>
            <span class="divider-sage"></span>
            <p class="font-sans text-sage-500 text-sm max-w-2xl mx-auto mt-4">{{ $subtitle }}</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($testimonials as $testi)
            <div class="bg-white p-8 rounded-lg shadow-sm reveal delay-{{ $loop->index * 100 }} border border-sage-100">
                <div class="flex items-center gap-1 mb-4 text-gold">
                    @for($i=1; $i<=5; $i++)
                        @if($i <= $testi->rating)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <p class="font-serif text-lg text-sage-800 font-light leading-relaxed italic mb-6">"{{ $testi->content }}"</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sage-200 to-sage-300 flex items-center justify-center font-serif text-sage-800 font-semibold text-sm">{{ substr($testi->customer_name, 0, 2) }}</div>
                    <div>
                        <p class="font-sans font-semibold text-sage-900 text-sm">{{ $testi->customer_name }}</p>
                        <p class="font-sans text-xs text-sage-500">{{ $testi->location }} • {{ $testi->product_name }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
