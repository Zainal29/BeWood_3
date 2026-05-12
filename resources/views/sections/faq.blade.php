@if(isset($faqs) && count($faqs))
<section id="faq" class="py-28 px-6 lg:px-14 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16 reveal">
            <p class="text-xs tracking-widest uppercase font-sans font-semibold text-sage-500 mb-3">Bantuan</p>
            <h2 class="font-serif text-4xl lg:text-5xl font-light text-sage-900">Pertanyaan Umum</h2>
            <span class="divider-sage"></span>
        </div>
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="accordion-item reveal">
                <button class="accordion-btn w-full text-left py-4 flex justify-between items-center" 
                        aria-expanded="false" 
                        aria-controls="faq{{ $faq->id }}">
                    <span class="font-sans text-sage-800 font-medium">{{ $faq->question }}</span>
                    <svg class="accordion-icon w-5 h-5 text-sage-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="faq{{ $faq->id }}" class="accordion-content overflow-hidden transition-all duration-300" style="max-height: 0">
                    <div class="pb-4 font-sans text-sage-600 text-sm leading-relaxed">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accordionBtns = document.querySelectorAll('.accordion-btn');
        
        accordionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                const contentId = this.getAttribute('aria-controls');
                const content = document.getElementById(contentId);
                
                if (!isExpanded) {
                    // Tutup semua accordion lain (opsional - untuk hanya 1 terbuka)
                    accordionBtns.forEach(otherBtn => {
                        if (otherBtn !== btn && otherBtn.getAttribute('aria-expanded') === 'true') {
                            otherBtn.setAttribute('aria-expanded', 'false');
                            const otherContent = document.getElementById(otherBtn.getAttribute('aria-controls'));
                            otherContent.style.maxHeight = '0';
                            const otherIcon = otherBtn.querySelector('.accordion-icon');
                            if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                        }
                    });
                    
                    // Buka yang diklik
                    this.setAttribute('aria-expanded', 'true');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    const icon = this.querySelector('.accordion-icon');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    this.setAttribute('aria-expanded', 'false');
                    content.style.maxHeight = '0';
                    const icon = this.querySelector('.accordion-icon');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
</script>
@endpush