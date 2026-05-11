@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif text-sage-900">Testimonial (Kata Mereka)</h1>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary px-4 py-2 text-sm">+ Tambah Testimonial</a>
    </div>

    <!-- Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="font-serif text-xl mb-4">Pengaturan Judul & Subjudul</h2>
        <form method="POST" action="{{ route('admin.testimonials.settings.update') }}">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 text-sm mb-1">Judul</label>
                    <input type="text" name="title" value="{{ $title }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sage-700 text-sm mb-1">Subjudul</label>
                    <input type="text" name="subtitle" value="{{ $subtitle }}" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
            <button type="submit" class="btn-primary px-4 py-2 mt-3">Simpan Pengaturan</button>
        </form>
    </div>

    <!-- Daftar Testimonial -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-sage-200">
            <thead class="bg-sage-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Urutan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Aktif</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testimonials as $testi)
                <tr class="border-b border-sage-100">
                    <td class="px-6 py-3">{{ $testi->order }}</td>
                    <td class="px-6 py-3 font-medium">{{ $testi->customer_name }}</td>
                    <td class="px-6 py-3">{{ $testi->rating }} ★</td>
                    <td class="px-6 py-3">
                        @if($testi->is_active)
                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.testimonials.edit', $testi) }}" class="text-sage-600 hover:text-sage-800 mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testi) }}" class="inline" onsubmit="return confirm('Hapus testimonial ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
