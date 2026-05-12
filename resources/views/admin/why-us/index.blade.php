@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif">Edit Mengapa BeWood?</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sage-600 hover:text-sage-800">← Kembali ke Dashboard</a>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="font-serif text-xl mb-4">Judul & Subjudul</h2>
        <form method="POST" action="{{ route('admin.why-us.settings.update') }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Judul</label>
                <input type="text" name="title" value="{{ $settings['title'] ?? 'Mengapa BeWood?' }}"
                       class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sage-700 mb-2">Subjudul</label>
                <textarea name="subtitle" rows="3"
                          class="w-full border border-sage-200 rounded-lg px-4 py-2">{{ $settings['subtitle'] ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn-primary px-6 py-2 text-sm">Simpan Pengaturan</button>
        </form>
    </div>

    <!-- Items (Tiga Pilar) -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="font-serif text-xl mb-4">Tiga Pilar</h2>
        @forelse($items as $item)
        <form method="POST" action="{{ route('admin.why-us.items.update', $item) }}"
              class="border-t pt-6 mt-6 first:border-0 first:pt-0 first:mt-0">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 mb-2">Judul</label>
                    <input type="text" name="title" value="{{ $item->title }}" required
                           class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sage-700 mb-2">Order</label>
                    <input type="number" name="order" value="{{ $item->order }}"
                           class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sage-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4" required
                              class="w-full border border-sage-200 rounded-lg px-4 py-2">{{ $item->description }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-between items-center">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
                        <span class="text-sage-700">Aktif</span>
                    </label>
                    <button type="submit" class="btn-primary px-6 py-2 text-sm">Update Item</button>
                </div>
            </div>
        </form>
        @empty
        <p class="text-sage-500">Belum ada item. Silakan tambahkan melalui database.</p>
        @endforelse
    </div>

    <!-- Statistik -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-serif text-xl mb-4">Statistik</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @forelse($stats as $stat)
            <form method="POST" action="{{ route('admin.why-us.stats.update', $stat) }}"
                  class="border border-sage-200 rounded-lg p-4">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="block text-sage-600 text-sm mb-1">Label</label>
                    <input type="text" name="label" value="{{ $stat->label }}"
                           class="w-full border border-sage-200 rounded-lg px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sage-600 text-sm mb-1">Nilai</label>
                    <input type="text" name="value" value="{{ $stat->value }}"
                           class="w-full border border-sage-200 rounded-lg px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sage-600 text-sm mb-1">Order</label>
                    <input type="number" name="order" value="{{ $stat->order }}"
                           class="w-32 border border-sage-200 rounded-lg px-3 py-2">
                </div>
                <div class="flex justify-between items-center mt-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $stat->is_active ? 'checked' : '' }}>
                        <span class="text-sm text-sage-600">Aktif</span>
                    </label>
                    <button type="submit" class="btn-primary px-4 py-1 text-sm">Update</button>
                </div>
            </form>
            @empty
            <p class="text-sage-500">Belum ada statistik.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            confirmButtonColor: '#5f7e5f',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
            confirmButtonColor: '#b91c1c'
        });
    @endif
</script>
@endpush
