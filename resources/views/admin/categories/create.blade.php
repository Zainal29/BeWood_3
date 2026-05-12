@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif text-sage-900">Tambah Kategori</h1>
        <a href="{{ route('admin.categories.index') }}" class="text-sage-600 hover:text-sage-800">← Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-premium p-6">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Nama Kategori -->
            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border border-sage-200 rounded-lg focus:outline-none focus:border-sage-400 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Kategori Induk -->
            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Kategori Induk</label>
                <select name="parent_id" class="w-full px-4 py-2 border border-sage-200 rounded-lg focus:outline-none focus:border-sage-400">
                    <option value="">Tidak ada (kategori utama)</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Gambar dengan preview -->
            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Gambar Kategori</label>
                <div id="image-preview" class="mb-3">
                    <img id="preview-img" src="#" alt="Preview" class="w-32 h-32 object-cover rounded-lg border shadow-sm hidden">
                </div>
                <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-sage-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sage-50 file:text-sage-700 hover:file:bg-sage-100">
                <p class="text-xs text-sage-400 mt-1">Format: JPG, PNG, maks 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label class="block text-sage-700 font-sans text-sm mb-2">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-sage-200 rounded-lg focus:outline-none focus:border-sage-400">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Aktif -->
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-sage-600 focus:ring-sage-500 border-sage-300 rounded">
                    <span class="text-sage-700 text-sm">Aktifkan kategori ini</span>
                </label>
            </div>

            <!-- Tombol -->
            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6 py-2 text-sm font-sans font-semibold">Simpan</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-outline-sage px-6 py-2 text-sm font-sans font-semibold">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image-input');
        const previewImg = document.getElementById('preview-img');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        previewImg.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.classList.add('hidden');
                    previewImg.src = '#';
                }
            });
        }

        // Notifikasi SweetAlert dari session
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

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#b91c1c'
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