@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-serif mb-6">Tambah FAQ</h1>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Pertanyaan <span class="text-red-500">*</span></label>
                <input type="text" name="question" value="{{ old('question') }}" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
                @error('question') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Jawaban <span class="text-red-500">*</span></label>
                <textarea name="answer" rows="5" required class="w-full border border-sage-200 rounded-lg px-4 py-2">{{ old('answer') }}</textarea>
                @error('answer') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Urutan (opsional, semakin kecil semakin atas)</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="w-32 border border-sage-200 rounded-lg px-4 py-2">
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded">
                    <span class="text-sage-700">Aktifkan</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6 py-2">Simpan</button>
                <a href="{{ route('admin.faqs.index') }}" class="btn-outline-sage px-6 py-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection