@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif text-sage-900">Edit Testimonial</h1>
        <a href="{{ route('admin.testimonials.index') }}" class="text-sage-600 hover:text-sage-800">← Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Nama Customer <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $testimonial->customer_name) }}" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Lokasi (opsional)</label>
                <input type="text" name="location" value="{{ old('location', $testimonial->location) }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Nama Produk (opsional)</label>
                <input type="text" name="product_name" value="{{ old('product_name', $testimonial->product_name) }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Rating (1-5)</label>
                <select name="rating" class="w-full border rounded-lg px-3 py-2">
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>
                            {{ $i }} ★
                        </option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Testimonial</label>
                <textarea name="content" rows="4" required class="w-full border rounded-lg px-3 py-2">{{ old('content', $testimonial->content) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Urutan (semakin kecil semakin atas)</label>
                <input type="number" name="order" value="{{ old('order', $testimonial->order) }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                    Aktif
                </label>
            </div>

            <button type="submit" class="btn-primary px-6 py-2">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
