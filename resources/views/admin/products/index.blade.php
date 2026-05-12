@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif text-sage-900 font-light">Daftar Produk</h1>
            <p class="text-sage-500 text-sm mt-1">Kelola semua produk furniture Anda</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary px-5 py-2.5 text-sm font-sans font-medium inline-flex items-center gap-2 shadow-sm hover:shadow transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Produk
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <form method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" name="search" placeholder="Cari nama produk..." value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sage-300 focus:border-transparent">
            </form>
        </div>
        @if (request('search'))
            <a href="{{ route('admin.products.index') }}" class="text-sage-500 hover:text-sage-700 text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reset filter
            </a>
        @endif
    </div>

    <!-- Tabel Produk -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100 bg-white">
                    @forelse ($products as $product)
                    <tr class="hover:bg-sage-50/50 transition">
                        <td class="px-6 py-4 text-sm text-sage-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            @if ($product->main_image)
                                <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover shadow-sm">
                            @else
                                <div class="w-12 h-12 bg-sage-100 rounded-lg flex items-center justify-center text-sage-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-sage-800">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sage-600">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sage-800">
                            @if ($product->discount_price && $product->discount_price < $product->price)
                                <span class="line-through text-sage-400 text-xs">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-red-600 font-semibold block">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                            @else
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->stock <= 0 ? 'bg-red-100 text-red-700' : ($product->stock < 5 ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700') }}">
                                {{ $product->stock }} tersisa
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($product->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-sage-100 text-sage-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sage-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sage-600 hover:text-sage-800 transition p-1" aria-label="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                </a>
                                <button type="button" class="delete-product-btn text-red-500 hover:text-red-700 transition p-1" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                                <!-- Form delete tersembunyi -->
                                <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-sage-500">
                            <svg class="w-12 h-12 mx-auto text-sage-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p>Tidak ada produk ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Konfirmasi hapus dengan SweetAlert
        const deleteButtons = document.querySelectorAll('.delete-product-btn');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.id;
                const productName = this.dataset.name;
                
                Swal.fire({
                    title: 'Hapus Produk?',
                    html: `Anda yakin ingin menghapus <strong>${productName}</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#5f7e5f',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    background: '#fff',
                    backdrop: true,
                    customClass: {
                        popup: 'rounded-xl shadow-xl',
                        title: 'font-serif text-xl',
                        htmlContainer: 'font-sans text-sage-600',
                        confirmButton: 'px-5 py-2 text-sm font-medium rounded-lg',
                        cancelButton: 'px-5 py-2 text-sm font-medium rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${productId}`).submit();
                    }
                });
            });
        });

        // Auto-submit search on type (debounce)
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }
    });
</script>
@endpush