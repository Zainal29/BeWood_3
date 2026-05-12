<div id="search-modal" class="fixed inset-0 z-[55] hidden" role="dialog" aria-modal="true" aria-label="Pencarian">
    <div class="absolute inset-0 bg-charcoal/60 backdrop-blur-sm" onclick="closeSearch()"></div>
    <div class="relative max-w-2xl mx-auto mt-24 px-4">
        <div class="bg-cream rounded-xl shadow-premium overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-sage-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sage-400" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" id="search-input" placeholder="Cari produk, kategori, atau inspirasi..."
                       class="flex-1 bg-transparent outline-none font-sans text-sage-800 placeholder-sage-400"
                       autofocus />
                <button onclick="closeSearch()" class="text-sage-400 hover:text-sage-700 transition-colors"
                        aria-label="Tutup pencarian">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="search-results" class="p-4 max-h-96 overflow-y-auto">
                <p class="text-sm text-sage-500 text-center py-8">Mulai mengetik untuk mencari produk...</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Event listener untuk pencarian dinamis
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    let searchTimeout = null;

    async function performSearch() {
        const query = searchInput.value.trim();
        if (query.length < 2) {
            searchResults.innerHTML =
                '<p class="text-sm text-sage-500 text-center py-8">Ketik minimal 2 karakter untuk mencari...</p>';
            return;
        }

        // Tampilkan loading
        searchResults.innerHTML =
            '<div class="text-center py-8"><div class="inline-block w-6 h-6 border-2 border-sage-400 border-t-transparent rounded-full animate-spin"></div><p class="text-sm text-sage-500 mt-2">Mencari...</p></div>';

        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('Gagal mengambil data');
            const products = await response.json();

            if (products.length === 0) {
                searchResults.innerHTML =
                    '<p class="text-sm text-sage-500 text-center py-8">Tidak ada produk ditemukan.</p>';
                return;
            }

            // Render hasil
            searchResults.innerHTML = products.map(product => `
                <a href="/product/${product.slug}" class="flex items-center gap-3 p-3 hover:bg-sage-50 rounded-lg transition-colors group" onclick="closeSearch()">
                    <div class="w-12 h-12 bg-sage-100 rounded overflow-hidden flex-shrink-0">
                        <img src="${product.image}" class="w-full h-full object-cover" alt="${product.name}">
                    </div>
                    <div class="flex-1">
                        <p class="font-sans text-sm font-medium text-sage-800 group-hover:text-sage-600">${product.name}</p>
                        <p class="text-xs text-sage-400">${product.category} • ${formatRupiah(product.price)}</p>
                    </div>
                </a>
            `).join('');
        } catch (error) {
            console.error('Search error:', error);
            searchResults.innerHTML =
                '<p class="text-sm text-sage-500 text-center py-8">Terjadi kesalahan, coba lagi nanti.</p>';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300); // debounce 300ms
        });
        // Optional: support Enter key untuk langsung search (bisa juga diarahkan ke halaman pencarian)
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = searchInput.value.trim();
                if (query.length >= 2) {
                    window.location.href = `/products?search=${encodeURIComponent(query)}`;
                }
            }
        });
    }
</script>
