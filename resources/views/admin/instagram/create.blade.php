@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif text-sage-900">Tambah Postingan Instagram</h1>
        <a href="{{ route('admin.instagram.index') }}" class="text-sage-600 hover:text-sage-800">← Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.instagram.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sage-700 font-medium mb-2">Link Instagram (opsional)</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url') }}" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                @error('instagram_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sage-700 font-medium mb-2">Gambar <span class="text-red-500">*</span></label>
                <div id="previewContainer" class="mb-3 hidden">
                    <img id="previewImg" class="w-32 h-32 object-cover rounded-lg border shadow-sm">
                </div>
                <input type="file" name="image" id="imageInput" accept="image/*" required class="w-full text-sm text-sage-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-sage-50 file:text-sage-700 hover:file:bg-sage-100">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-6">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-sage-300 text-sage-600">
                    <span class="text-sage-700">Aktif</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6 py-2 text-sm font-semibold">Simpan</button>
                <a href="{{ route('admin.instagram.index') }}" class="btn-outline-sage px-6 py-2 text-sm font-semibold">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');
    const previewImg = document.getElementById('previewImg');
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    });
</script>
@endpush
