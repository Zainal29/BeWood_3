<div id="quick-view-modal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true" aria-label="Detail Produk">
    <div class="absolute inset-0 bg-charcoal/70 backdrop-blur-sm" onclick="closeQuickView()"></div>
    <div class="relative max-w-5xl mx-auto my-8 bg-cream rounded-lg overflow-hidden shadow-premium">
        <button onclick="closeQuickView()"
                class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/90 flex items-center justify-center text-sage-700 hover:bg-sage-100 transition-colors"
                aria-label="Tutup">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="grid md:grid-cols-2">
            <div class="p-6 md:p-8">
                <img id="qv-image" src="" alt=""
                     class="w-full aspect-square object-cover rounded-lg bg-sage-100" />
                <div class="flex gap-2 mt-4 overflow-x-auto pb-2">
                    <button class="gallery-thumb active w-16 h-16 rounded-lg overflow-hidden bg-sage-100">
                        <img id="qv-thumb-1" src="" class="w-full h-full object-cover" />
                    </button>
                    <button class="gallery-thumb w-16 h-16 rounded-lg overflow-hidden bg-sage-100">
                        <img id="qv-thumb-2" src="" class="w-full h-full object-cover opacity-70" />
                    </button>
                    <button class="gallery-thumb w-16 h-16 rounded-lg overflow-hidden bg-sage-100">
                        <img id="qv-thumb-3" src="" class="w-full h-full object-cover opacity-70" />
                    </button>
                </div>
            </div>
            <div class="p-6 md:p-8 flex flex-col">
                <span id="qv-category" class="text-xs tracking-widest uppercase text-sage-400 font-sans mb-2"></span>
                <h3 id="qv-title" class="font-serif text-2xl md:text-3xl text-sage-900 font-light mb-3"></h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="stars flex text-gold">★★★★★</div>
                    <span id="qv-reviews" class="text-xs text-sage-500">(0 ulasan)</span>
                </div>
                <p id="qv-price" class="font-serif text-2xl text-sage-800 font-semibold mb-4"></p>
                <p id="qv-desc" class="font-sans text-sm text-sage-600 leading-relaxed mb-6"></p>
                <div class="mb-6">
                    <p class="text-xs font-sans text-sage-500 uppercase tracking-wide mb-2">Varian</p>
                    <div class="flex flex-wrap gap-2">
                        <button class="variant-btn px-4 py-2 text-xs border border-sage-300 rounded hover:border-sage-500 hover:bg-sage-50 transition-colors"
                                data-variant="default">Default</button>
                        <button class="variant-btn px-4 py-2 text-xs border border-sage-300 rounded hover:border-sage-500 hover:bg-sage-50 transition-colors"
                                data-variant="premium">Premium Finish (+Rp 500.000)</button>
                    </div>
                </div>
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-xs font-sans text-sage-500 uppercase tracking-wide">Jumlah:</span>
                    <div class="flex items-center border border-sage-300 rounded">
                        <button class="qv-qty-btn w-10 h-10 flex items-center justify-center text-sage-600 hover:bg-sage-100 transition-colors"
                                data-action="dec">−</button>
                        <span id="qv-qty" class="w-12 text-center font-sans text-sm">1</span>
                        <button class="qv-qty-btn w-10 h-10 flex items-center justify-center text-sage-600 hover:bg-sage-100 transition-colors"
                                data-action="inc">+</button>
                    </div>
                </div>
                <div class="mt-auto space-y-3">
                    <button id="qv-add-to-cart"
                            class="btn-primary w-full py-3.5 text-xs font-sans font-semibold">TAMBAHKAN KE
                        KERANJANG</button>
                    <button class="btn-outline-sage w-full py-3 text-xs font-sans font-medium">BELI SEKARANG</button>
                </div>
                <div class="mt-6 pt-6 border-t border-sage-200 space-y-3 text-xs text-sage-500">
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-sage-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12.75L11.25 15 15 9.75M12 3v18m0-18c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z" />
                        </svg>
                        Garansi 5 tahun untuk cacat struktural
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-sage-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        Pengiriman gratis se-Indonesia
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
