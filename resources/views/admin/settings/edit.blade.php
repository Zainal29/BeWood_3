@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif text-sage-900">Edit Hero Section</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sage-600 hover:text-sage-800">← Dashboard</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <h3 class="font-serif text-lg text-sage-800 mb-4">Hero Content</h3>
                </div>
                <div>
                    <label class="block text-sage-700 mb-1">Badge Text</label>
                    <input type="text" name="hero_top_text" value="{{ old('hero_top_text', $settings['hero_top_text'] ?? 'Koleksi Furniture Premium 2025') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sage-700 mb-1">Title Line 1</label>
                    <input type="text" name="hero_title_top" value="{{ old('hero_title_top', $settings['hero_title_top'] ?? 'Ruang yang') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sage-700 mb-1">Title Line 2 (highlighted)</label>
                    <input type="text" name="hero_title_bottom" value="{{ old('hero_title_bottom', $settings['hero_title_bottom'] ?? 'Bercerita') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sage-700 mb-1">Description</label>
                    <textarea name="hero_description" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('hero_description', $settings['hero_description'] ?? 'Dari tangan pengrajin terbaik Indonesia — setiap detail dirancang...') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sage-700 mb-1">Background Image</label>
                    @if(isset($settings['hero_image']))
                        <div class="mb-3">
                            <img src="{{ Storage::url($settings['hero_image']) }}" class="w-48 h-32 object-cover rounded-lg border">
                        </div>
                    @endif
                    <input type="file" name="hero_image" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-sage-50">
                </div>

                <div class="md:col-span-2">
                    <h3 class="font-serif text-lg text-sage-800 mt-4 mb-4">Trust Badges</h3>
                </div>
                <div>
                    <label>Badge 1</label>
                    <input type="text" name="hero_badge_1_text" value="{{ old('hero_badge_1_text', $settings['hero_badge_1_text'] ?? 'Garansi 5 Tahun') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label>Badge 2</label>
                    <input type="text" name="hero_badge_2_text" value="{{ old('hero_badge_2_text', $settings['hero_badge_2_text'] ?? 'Pengiriman Gratis') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label>Badge 3</label>
                    <input type="text" name="hero_badge_3_text" value="{{ old('hero_badge_3_text', $settings['hero_badge_3_text'] ?? 'Konsultasi Gratis') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="btn-primary px-6 py-2">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}" });
</script>
@endif
@endpush
