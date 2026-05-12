@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-sage-500 hover:text-sage-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-serif text-sage-900">Edit Produk: {{ $product->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="bg-white rounded-xl shadow-sm p-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Nama Produk -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                       class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Kategori</label>
                <select name="category_id" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Harga</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                       class="w-full border border-sage-200 rounded-lg px-4 py-2">
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Harga Diskon -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Harga Diskon (opsional)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                       class="w-full border border-sage-200 rounded-lg px-4 py-2">
                @error('discount_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Stok -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                       class="w-full border border-sage-200 rounded-lg px-4 py-2">
                @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Gambar Utama (Preview + Tombol Hapus) -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Gambar Utama</label>

                @if($product->main_image)
                <div id="mainImageContainer" class="relative inline-block mb-3">
                    <img src="{{ Storage::url($product->main_image) }}" class="w-32 h-32 object-cover rounded-lg border shadow-sm">
                    <button type="button" id="deleteMainImageBtn"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600">
                        ×
                    </button>
                </div>
                @else
                <div id="noMainImageText" class="text-sage-400 text-sm mb-3">Belum ada gambar utama</div>
                @endif

                <div id="newMainImagePreview" class="mb-3 hidden">
                    <img id="newMainPreviewImg" src="#" class="w-32 h-32 object-cover rounded-lg border shadow-sm">
                </div>

                <input type="file" name="main_image" id="mainImageInput" accept="image/*"
                       class="w-full text-sm text-sage-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sage-50 file:text-sage-700 hover:file:bg-sage-100">
                <p class="text-xs text-sage-400 mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
                @error('main_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-6">
            <label class="block text-sage-700 text-sm font-medium mb-2">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border border-sage-200 rounded-lg px-4 py-2">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Spesifikasi JSON -->
        <div class="mt-6">
            <label class="block text-sage-700 text-sm font-medium mb-2">Spesifikasi (JSON)</label>
            <textarea name="specifications" rows="4" class="w-full border border-sage-200 rounded-lg px-4 py-2 font-mono text-sm">{{ old('specifications', is_string($product->specifications) ? $product->specifications : json_encode($product->specifications, JSON_PRETTY_PRINT)) }}</textarea>
            <p class="text-xs text-sage-400 mt-1">Contoh: {"Material":"Kayu Jati","Dimensi":"180x90cm"}</p>
            @error('specifications') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Gambar Tambahan (List + Tombol Hapus per item + Upload baru) -->
        <div class="mt-6">
            <label class="block text-sage-700 text-sm font-medium mb-2">Gambar Tambahan</label>

            <div id="additionalImagesList" class="flex flex-wrap gap-3 mb-4">
                @forelse($product->images as $image)
                <div class="relative group" data-id="{{ $image->id }}">
                    <img src="{{ Storage::url($image->image) }}" class="w-20 h-20 object-cover rounded-lg border shadow-sm">
                    <button type="button" class="delete-additional-image absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600">×</button>
                </div>
                @empty
                <div id="noAdditionalImages" class="text-sage-400 text-sm">Belum ada gambar tambahan</div>
                @endforelse
            </div>

            <div id="newAdditionalPreview" class="flex flex-wrap gap-3 mb-3"></div>

            <input type="file" name="images[]" id="additionalImagesInput" multiple accept="image/*"
                   class="w-full text-sm text-sage-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sage-50 file:text-sage-700 hover:file:bg-sage-100">
            <p class="text-xs text-sage-400 mt-1">* Pilih beberapa gambar. Gambar baru akan ditambahkan, gambar lama tetap ada (kecuali dihapus).</p>
            @error('images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Checkbox fitur -->
        <div class="mt-6 flex gap-6">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                       class="w-4 h-4 text-sage-600 rounded">
                <span class="text-sage-700">Unggulan</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}
                       class="w-4 h-4 text-sage-600 rounded">
                <span class="text-sage-700">Terlaris</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}
                       class="w-4 h-4 text-sage-600 rounded">
                <span class="text-sage-700">Baru</span>
            </label>
        </div>

        <!-- Tombol submit -->
        <div class="mt-8 flex gap-3">
            <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-semibold rounded-lg">Update Produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn-outline-sage px-6 py-2.5 text-sm font-semibold rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== HAPUS GAMBAR UTAMA ==========
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
                    confirmButtonText: 'Ya, hapus!'
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
                    confirmButtonText: 'Ya, hapus!'
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
                            <img src="${event.target.result}" class="w-20 h-20 object-cover rounded-lg border shadow-sm">
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

        // ========== SWEETALERT NOTIFIKASI SESSION ==========
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#5f7e5f',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#b91c1c'
            });
        @endif
    });
</script>
@endpush