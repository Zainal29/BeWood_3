@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-serif mb-6">Edit Postingan Instagram</h1>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.instagram.update', $instagram) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Link Instagram (opsional)</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $instagram->instagram_url) }}" class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Gambar Saat Ini</label>
                <img src="{{ Storage::url($instagram->image) }}" class="w-32 h-32 object-cover rounded-lg border">
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Ganti Gambar (opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ $instagram->is_active ? 'checked' : '' }} class="rounded">
                    <span class="text-sage-700">Aktifkan</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6 py-2">Update</button>
                <a href="{{ route('admin.instagram.index') }}" class="btn-outline-sage px-6 py-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
