@if(isset($products) && $products->count())
<section id="produk" class="py-28 px-6 lg:px-14 bg-parchment relative">
    <div class="max-w-7xl mx-auto relative">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4 reveal">
            <div>
                <p class="text-xs tracking-widest uppercase font-sans font-semibold text-sage-500 mb-3">Koleksi Terlaris</p>
                <h2 class="font-serif text-4xl lg:text-5xl font-light text-sage-900">Produk Unggulan</h2>
                <p class="font-sans text-sage-500 text-sm mt-2 max-w-md">Dipilih berdasarkan kualitas, desain, dan kepuasan pelanggan.</p>
            </div>
        </div>

        {{-- ✅ PERBAIKAN: Loop melalui $products --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                @php
                    $imgUrl = $product->main_image
                        ? (str_starts_with($product->main_image, 'http')
                            ? $product->main_image
                            : asset('storage/' . $product->main_image))
                        : asset('images/placeholder.jpg');
                @endphp

                @include('partials.components.product-card', [
                    'product_id'     => $product->id,
                    'name'           => $product->name,
                    'price'          => $product->price,
                    'discount_price' => $product->discount_price,
                    'original_price' => $product->price,
                    'img'            => $imgUrl,
                    'desc'           => $product->description,
                    'category'       => $product->category->name ?? 'Umum',
                    'reviews'        => $product->reviews_count ?? 0,
                    'badge'          => $product->is_bestseller ? 'Terlaris'
                                        : ($product->is_new ? 'Baru' : null),
                    'variant'        => $product->variants->first()->value ?? 'Default',
                    'slug'           => $product->slug,
                ])
            @endforeach
        </div>
    </div>
</section>
@endif
