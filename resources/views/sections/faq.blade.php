<section id="faq" class="py-28 px-6 lg:px-14 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16 reveal">
            <p class="text-xs tracking-widest uppercase font-sans font-semibold text-sage-500 mb-3">Bantuan</p>
            <h2 class="font-serif text-4xl lg:text-5xl font-light text-sage-900">Pertanyaan Umum</h2>
            <span class="divider-sage"></span>
        </div>
        <div class="space-y-2">
            <div class="accordion-item reveal">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq1">
                    <span class="font-sans text-sage-800 font-medium">Berapa lama waktu pengiriman?</span>
                    <svg class="accordion-icon w-5 h-5 text-sage-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="faq1" class="accordion-content">
                    <div class="pb-4 font-sans text-sage-600 text-sm leading-relaxed">
                        <p>Waktu pengiriman bervariasi tergantung lokasi:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Jabodetabek: 2-4 hari kerja</li>
                            <li>Jawa & Bali: 5-7 hari kerja</li>
                            <li>Luar Jawa: 7-14 hari kerja</li>
                        </ul>
                        <p class="mt-3">Semua pengiriman dilengkapi asuransi dan tracking real-time.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item reveal delay-100">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq2">
                    <span class="font-sans text-sage-800 font-medium">Apakah ada layanan konsultasi desain?</span>
                    <svg class="accordion-icon w-5 h-5 text-sage-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="faq2" class="accordion-content">
                    <div class="pb-4 font-sans text-sage-600 text-sm leading-relaxed">
                        <p>Ya! Kami menawarkan konsultasi desain gratis via WhatsApp atau video call. Tim desainer kami akan membantu Anda memilih produk yang sesuai.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item reveal delay-200">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq3">
                    <span class="font-sans text-sage-800 font-medium">Bagaimana cara perawatan furniture kayu?</span>
                    <svg class="accordion-icon w-5 h-5 text-sage-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="faq3" class="accordion-content">
                    <div class="pb-4 font-sans text-sage-600 text-sm leading-relaxed">
                        <p>Hindari sinar matahari langsung, bersihkan dengan kain lembut, oleskan minyak kayu alami setiap 6-12 bulan.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item reveal delay-300">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq4">
                    <span class="font-sans text-sage-800 font-medium">Apakah bisa custom ukuran atau desain?</span>
                    <svg class="accordion-icon w-5 h-5 text-sage-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="faq4" class="accordion-content">
                    <div class="pb-4 font-sans text-sage-600 text-sm leading-relaxed">
                        <p>Ya, kami menerima pesanan custom dengan DP 50% dan waktu produksi 4-8 minggu. Konsultasi desain gratis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // ==================== ACCORDION FAQ (PERBAIKAN) ====================
    function initAccordion() {
        const accordionBtns = document.querySelectorAll('.accordion-btn');
        accordionBtns.forEach(btn => {
            // Hapus listener lama jika ada untuk menghindari duplikasi
            btn.removeEventListener('click', toggleAccordion);
            btn.addEventListener('click', toggleAccordion);

            // Set state awal berdasarkan atribut aria-expanded
            const contentId = btn.getAttribute('aria-controls');
            if (contentId) {
                const content = document.getElementById(contentId);
                const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    content.classList.add('open');
                    rotateIcon(btn, true);
                } else {
                    content.classList.remove('open');
                    rotateIcon(btn, false);
                }
            }
        });
    }

    function toggleAccordion(event) {
        const btn = event.currentTarget;
        const contentId = btn.getAttribute('aria-controls');
        if (!contentId) return;

        const content = document.getElementById(contentId);
        const isExpanded = btn.getAttribute('aria-expanded') === 'true';

        // Toggle atribut aria-expanded
        btn.setAttribute('aria-expanded', !isExpanded);

        if (!isExpanded) {
            content.classList.add('open');
            rotateIcon(btn, true);
        } else {
            content.classList.remove('open');
            rotateIcon(btn, false);
        }
    }

    function rotateIcon(btn, isOpen) {
        const icon = btn.querySelector('.accordion-icon');
        if (icon) {
            icon.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }

    // Inisialisasi saat DOM sudah siap
    document.addEventListener('DOMContentLoaded', function() {
        initAccordion();

        // (Opsional) Inisialisasi efek reveal jika ada
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    });
</script>
