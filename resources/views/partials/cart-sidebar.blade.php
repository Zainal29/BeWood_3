<div id="cart-overlay"
     class="fixed inset-0 bg-charcoal/60 z-50 opacity-0 pointer-events-none transition-opacity duration-300 cursor-pointer"
     aria-hidden="true" onclick="closeCart()"></div>
<aside id="cart-sidebar"
       class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-cream z-[60] translate-x-full flex flex-col shadow-2xl transition-transform duration-500"
       role="dialog" aria-modal="true" aria-label="Keranjang Belanja">
    <div class="flex items-center justify-between px-6 py-5 border-b border-sage-200">
        <h2 class="font-serif text-xl text-sage-900">Keranjang Belanja</h2>
        <button onclick="closeCart()"
                class="text-sage-600 hover:text-sage-900 transition-colors p-2 rounded-full hover:bg-sage-100"
                aria-label="Tutup keranjang">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div id="cart-items" class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
        <div id="cart-empty" class="flex flex-col items-center justify-center h-64 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-sage-300 mb-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            <p class="font-sans text-sage-500 text-sm">Keranjang kamu masih kosong.</p>
            <p class="font-sans text-sage-400 text-xs mt-1">Tambahkan produk favoritmu!</p>
            <button onclick="closeCart(); window.location.href='{{ route('landing') }}'; setTimeout(() => { document.querySelector('#produk').scrollIntoView({behavior:'smooth'}) }, 300);"
                    class="mt-4 btn-outline-sage px-6 py-2.5 text-xs">JELAJAHI PRODUK</button>
        </div>
    </div>
    <div id="cart-footer" class="hidden px-6 py-5 border-t border-sage-200 space-y-4 bg-parchment/50">
        <div class="flex justify-between items-center">
            <span class="font-sans text-sage-600 text-sm">Subtotal</span>
            <span id="cart-total" class="font-serif text-lg text-sage-900 font-semibold">Rp 0</span>
        </div>
        <p class="font-sans text-xs text-sage-400">Ongkos kirim dihitung saat checkout. Gratis untuk pembelian di atas
            Rp 5.000.000.</p>
        <button class="btn-primary w-full py-3.5 text-xs font-sans font-semibold rounded-none">LANJUTKAN KE
            PEMBAYARAN</button>
        <button id="empty-cart-btn" class="btn-outline-sage w-full py-3 text-xs font-sans font-medium rounded-none">Kosongkan Keranjang</button>
    </div>
</aside>

<script>
    function closeCart() {
        const sidebar = document.getElementById('cart-sidebar');
        const overlay = document.getElementById('cart-overlay');
        if (sidebar && overlay) {
            sidebar.classList.add('translate-x-full');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }
</script>
