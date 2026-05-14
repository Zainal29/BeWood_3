@if(isset($products) && $products->count())
<section id="produk" class="py-28 px-6 lg:px-14 bg-parchment relative">
    <div class="max-w-7xl mx-auto relative">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4 reveal">
            <div>
                <p class="text-xs tracking-widest uppercase font-sans font-semibold text-sage-500 mb-3">Koleksi Terlaris</p>
                <h2 class="font-serif text-4xl lg:text-5xl font-light text-sage-900">Produk Unggulan</h2>
                <p class="font-sans text-sage-500 text-sm mt-2 max-w-md">Dipilih berdasarkan kualitas, desain, dan kepuasan pelanggan.</p>
            </div>
            <div class="flex items-center gap-3">
                <select id="product-sort" class="font-sans text-xs text-sage-600 bg-white border border-sage-200 rounded px-3 py-2">
                    <option value="terbaru">Urutkan: Terbaru</option>
                    <option value="harga_rendah">Harga: Rendah ke Tinggi</option>
                    <option value="harga_tinggi">Harga: Tinggi ke Rendah</option>
                    <option value="terlaris">Terlaris</option>
                    <option value="rating">Rating Tertinggi</option>
                </select>
                <a href="{{ route('landing') }}#produk" class="btn-outline-sage px-6 py-2.5 text-xs font-sans font-semibold inline-flex items-center gap-1">
                    LIHAT SEMUA
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                @include('partials.components.product-card', [
                    'name' => $product->name,
                    'price' => $product->discount_price ?? $product->price,
                    'img' => Storage::url($product->main_image),
                    'desc' => $product->description,
                    'category' => $product->category->name ?? 'Umum',
                    'reviews' => $product->reviews_count ?? 0,
                    'badge' => $product->is_bestseller ? 'TERLARIS' : ($product->is_new ? 'BARU' : null),
                    'variant' => $product->variants->first()->value ?? 'Default',
                    'slug' => $product->slug,
                ])
            @endforeach
        </div>

        {{-- HAPUS bagian pagination ini --}}
        {{-- <div class="text-center mt-12 reveal">
            {{ $products->appends(request()->query())->links() }}
        </div> --}}
    </div>
</section>

<script>
    document.getElementById('product-sort')?.addEventListener('change', function() {
        let url = new URL(window.location.href);
        let sort = this.value;
        if (sort && sort !== 'terbaru') url.searchParams.set('sort', sort);
        else url.searchParams.delete('sort');
        window.location.href = url.toString();
    });
</script>
@endif
