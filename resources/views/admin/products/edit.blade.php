{{-- resources/views/admin/products/edit.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb Modern --}}
    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-emerald-600 transition">Dashboard</a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-emerald-600 transition">Produk</a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Edit: {{ $product->name }}</span>
    </nav>

    {{-- Header Section --}}
    <div class="mb-8 flex flex-wrap justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Produk</h1>
            <p class="text-gray-500 mt-1">Perbarui informasi produk koleksi Anda</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Form Container Premium --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 overflow-hidden">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            <div class="p-6 md:p-8 space-y-8">

                {{-- Grid 2 kolom informasi dasar --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none"
                               placeholder="Contoh: Sofa Kayu Jati">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none"
                               placeholder="0">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Diskon (Opsional)</label>
                        <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none"
                               placeholder="0">
                        @error('discount_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none"
                               placeholder="0">
                        @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Deskripsi (full width) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none"
                              placeholder="Deskripsikan produk secara detail...">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Spesifikasi JSON --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi (JSON)</label>
                    <textarea name="specifications" rows="4"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 font-mono text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none"
                              placeholder='{"Material":"Kayu Jati","Dimensi":"180x90cm"}'>{{ old('specifications', is_string($product->specifications) ? $product->specifications : json_encode($product->specifications, JSON_PRETTY_PRINT)) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Gunakan format JSON valid, contoh: {"Material":"Kayu Jati","Dimensi":"180x90cm"}</p>
                    @error('specifications') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Gambar Utama (Preview + Hapus + Upload) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                    @if($product->main_image)
                    <div id="mainImageContainer" class="relative inline-block mb-3">
                        <img src="{{ Storage::url($product->main_image) }}" class="w-32 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                        <button type="button" id="deleteMainImageBtn"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600 transition">
                            ×
                        </button>
                    </div>
                    @else
                    <div id="noMainImageText" class="text-gray-400 text-sm mb-3">Belum ada gambar utama</div>
                    @endif

                    <div id="newMainImagePreview" class="mb-3 hidden">
                        <img id="newMainPreviewImg" src="#" class="w-32 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                    </div>

                    <div id="mainImageDropzone" class="relative border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-gray-50/30 hover:bg-gray-50/50 transition cursor-pointer group">
                        <input type="file" name="main_image" id="mainImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Klik atau seret gambar baru untuk mengganti</p>
                            <p class="text-xs text-gray-400">Kosongkan jika tidak ingin mengubah gambar</p>
                        </div>
                    </div>
                </div>

                {{-- Gambar Tambahan (List + Hapus + Upload Multiple) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Tambahan</label>
                    <div id="additionalImagesList" class="flex flex-wrap gap-3 mb-4">
                        @forelse($product->images as $image)
                        <div class="relative group" data-id="{{ $image->id }}">
                            <img src="{{ Storage::url($image->image) }}" class="w-20 h-20 object-cover rounded-xl border border-gray-200 shadow-sm">
                            <button type="button" class="delete-additional-image absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600 transition">×</button>
                        </div>
                        @empty
                        <div id="noAdditionalImages" class="text-gray-400 text-sm">Belum ada gambar tambahan</div>
                        @endforelse
                    </div>

                    <div id="newAdditionalPreview" class="flex flex-wrap gap-3 mb-3"></div>

                    <div id="additionalDropzone" class="relative border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-gray-50/30 hover:bg-gray-50/50 transition cursor-pointer group">
                        <input type="file" name="images[]" id="additionalImagesInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" multiple accept="image/*">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Klik atau seret beberapa gambar untuk menambah</p>
                            <p class="text-xs text-gray-400">Gambar baru akan ditambahkan, gambar lama tetap ada (kecuali dihapus)</p>
                        </div>
                    </div>
                </div>

                {{-- Checkbox fitur --}}
                <div class="flex flex-wrap gap-6">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Jadikan Unggulan</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Produk Terlaris</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Produk Baru</span>
                    </label>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="px-6 py-5 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                    Update Produk
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== HAPUS GAMBAR UTAMA (AJAX) ==========
        const deleteMainBtn = document.getElementById('deleteMainImageBtn');
        if (deleteMainBtn) {
            deleteMainBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Hapus gambar utama?',
                    text: "Gambar akan langsung dihapus dari server.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#5f7e5f',
                    confirmButtonText: 'Ya, hapus!',
                    background: 'rgba(0,0,0,0.85)',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route("admin.products.destroy-main-image", $product) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('mainImageContainer')?.remove();
                                const noText = document.getElementById('noMainImageText');
                                if (noText) noText.classList.remove('hidden');
                                Swal.fire('Terhapus!', data.message, 'success');
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            });
        }

        // ========== PREVIEW GAMBAR UTAMA BARU ==========
        const mainInput = document.getElementById('mainImageInput');
        const newPreviewDiv = document.getElementById('newMainImagePreview');
        const newPreviewImg = document.getElementById('newMainPreviewImg');
        if (mainInput) {
            mainInput.addEventListener('change', function(e) {
                if (e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        newPreviewImg.src = ev.target.result;
                        newPreviewDiv.classList.remove('hidden');
                        const oldContainer = document.getElementById('mainImageContainer');
                        if (oldContainer) oldContainer.style.display = 'none';
                        const noText = document.getElementById('noMainImageText');
                        if (noText) noText.style.display = 'none';
                    };
                    reader.readAsDataURL(e.target.files[0]);
                } else {
                    newPreviewDiv.classList.add('hidden');
                }
            });
        }

        // ========== HAPUS GAMBAR TAMBAHAN (per item) ==========
        document.querySelectorAll('.delete-additional-image').forEach(btn => {
            btn.addEventListener('click', function() {
                const container = this.closest('.relative.group');
                const imageId = container.dataset.id;
                Swal.fire({
                    title: 'Hapus gambar ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#b91c1c',
                    confirmButtonText: 'Ya, hapus!',
                    background: 'rgba(0,0,0,0.85)',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/product-images/${imageId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                container.remove();
                                const noAdditional = document.getElementById('noAdditionalImages');
                                if (noAdditional && document.querySelectorAll('.delete-additional-image').length === 0) {
                                    noAdditional.classList.remove('hidden');
                                }
                                Swal.fire('Terhapus!', data.message, 'success');
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            });
        });

        // ========== PREVIEW GAMBAR TAMBAHAN BARU (multiple) ==========
        const additionalInput = document.getElementById('additionalImagesInput');
        const additionalPreview = document.getElementById('newAdditionalPreview');
        if (additionalInput) {
            additionalInput.addEventListener('change', function(e) {
                additionalPreview.innerHTML = '';
                const files = Array.from(e.target.files);
                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative inline-block';
                        wrapper.innerHTML = `
                            <img src="${event.target.result}" class="w-20 h-20 object-cover rounded-xl border border-gray-200 shadow-sm">
                            <button type="button" class="remove-preview-btn absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600">×</button>
                        `;
                        additionalPreview.appendChild(wrapper);
                        wrapper.querySelector('.remove-preview-btn').addEventListener('click', () => {
                            wrapper.remove();
                            // Hapus file dari input (re-build DataTransfer)
                            const dt = new DataTransfer();
                            const currentFiles = Array.from(additionalInput.files);
                            const remainingFiles = currentFiles.filter(f => f.name !== file.name);
                            remainingFiles.forEach(f => dt.items.add(f));
                            additionalInput.files = dt.files;
                        });
                    };
                    reader.readAsDataURL(file);
                });
            });
        }

        // ========== DRAG & DROP EFFECT ==========
        const mainDropzone = document.getElementById('mainImageDropzone');
        const additionalDropzone = document.getElementById('additionalDropzone');
        [mainDropzone, additionalDropzone].forEach(zone => {
            if (!zone) return;
            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.classList.add('border-emerald-400', 'bg-emerald-50/30');
            });
            zone.addEventListener('dragleave', () => {
                zone.classList.remove('border-emerald-400', 'bg-emerald-50/30');
            });
            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.classList.remove('border-emerald-400', 'bg-emerald-50/30');
                const input = zone.querySelector('input[type="file"]');
                if (input) input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            });
        });

        // ========== SWEETALERT NOTIFIKASI SESSION ==========
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                background: 'rgba(0,0,0,0.85)',
                color: '#fff',
                confirmButtonColor: '#10b981',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                background: 'rgba(0,0,0,0.85)',
                color: '#fff',
                confirmButtonColor: '#ef4444'
            });
        @endif
    });
</script>
@endsection
