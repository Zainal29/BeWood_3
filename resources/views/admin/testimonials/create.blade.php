@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.testimonials.index') }}" class="text-sage-500 hover:text-sage-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <h1 class="text-2xl font-serif text-sage-900">Tambah Testimonial</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.testimonials.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sage-700 text-sm font-medium mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
                @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm font-medium mb-2">Lokasi (opsional)</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm font-medium mb-2">Nama Produk (opsional)</label>
                <input type="text" name="product_name" value="{{ old('product_name') }}" class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm font-medium mb-2">Testimonial <span class="text-red-500">*</span></label>
                <textarea name="content" rows="4" required class="w-full border border-sage-200 rounded-lg px-4 py-2">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-2">Rating (1-5)</label>
                    <select name="rating" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                        @for($i=5; $i>=1; $i--)
                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-2">Urutan</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded">
                    <span class="text-sage-700 text-sm">Aktifkan</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-semibold rounded-lg">Simpan</button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn-outline-sage px-6 py-2.5 text-sm font-semibold rounded-lg">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
