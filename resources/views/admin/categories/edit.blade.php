{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif text-sage-900">Edit Kategori</h1>
        <a href="{{ route('admin.categories.index') }}" class="text-sage-600 hover:text-sage-800">← Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-premium p-6">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-4 py-2 border border-sage-200 rounded-lg focus:outline-none focus:border-sage-400 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Slug</label>
                <input type="text" value="{{ $category->slug }}" disabled
                       class="w-full px-4 py-2 border border-sage-200 rounded-lg bg-sage-100 text-sage-500">
                <p class="text-xs text-sage-400 mt-1">Slug dibuat otomatis berdasarkan nama.</p>
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Kategori Induk</label>
                <select name="parent_id" class="w-full px-4 py-2 border border-sage-200 rounded-lg focus:outline-none focus:border-sage-400">
                    <option value="">Tidak ada (kategori utama)</option>
                    @foreach ($categories as $cat)
                        @if ($cat->id !== $category->id)
                            <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Gambar Kategori dengan tombol hapus langsung (AJAX) -->
            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Gambar Kategori</label>

                <div id="imageSection">
                    @if($category->image)
                    <div id="currentImageContainer" class="relative inline-block mb-3">
                        <img src="{{ Storage::url($category->image) }}" class="w-32 h-32 object-cover rounded-lg border shadow-sm">
                        <button type="button" id="removeImageBtn"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600 transition">
                            ×
                        </button>
                    </div>
                    @else
                    <div id="noImageText" class="text-sage-400 text-sm mb-3">Belum ada gambar</div>
                    @endif
                </div>

                <div id="newImagePreview" class="mb-3 hidden">
                    <img id="previewImg" src="#" alt="Preview" class="w-32 h-32 object-cover rounded-lg border shadow-sm hidden">
                </div>

                <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg"
                       class="w-full text-sm text-sage-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sage-50 file:text-sage-700 hover:file:bg-sage-100">
                <p class="text-xs text-sage-400 mt-1">Format: JPG, PNG, maks 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-sage-200 rounded-lg focus:outline-none focus:border-sage-400">{{ old('description', $category->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-sage-600 focus:ring-sage-500 border-sage-300 rounded">
                    <span class="text-sage-700 text-sm">Aktifkan kategori ini</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6 py-2 text-sm font-sans font-semibold">Simpan Perubahan</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-outline-sage px-6 py-2 text-sm font-sans font-semibold">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const previewImg = document.getElementById('previewImg');
        const newPreviewDiv = document.getElementById('newImagePreview');
        const currentContainer = document.getElementById('currentImageContainer');
        const removeBtn = document.getElementById('removeImageBtn');
        const noImageText = document.getElementById('noImageText');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        previewImg.classList.remove('hidden');
                        if (currentContainer) currentContainer.classList.add('hidden');
                        if (noImageText) noImageText.classList.add('hidden');
                        newPreviewDiv.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.classList.add('hidden');
                    newPreviewDiv.classList.add('hidden');
                    if (currentContainer) currentContainer.classList.remove('hidden');
                    if (noImageText) noImageText.classList.remove('hidden');
                }
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus gambar?',
                    text: "Gambar akan langsung dihapus dari server.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#5f7e5f',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route("admin.categories.destroy-image", $category) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (currentContainer) currentContainer.remove();
                                if (noImageText) noImageText.classList.remove('hidden');
                                const removeInput = document.getElementById('removeImageInput');
                                if (removeInput) removeInput.value = '1';
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    confirmButtonColor: '#5f7e5f',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus gambar.', 'error');
                        });
                    }
                });
            });
        }

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