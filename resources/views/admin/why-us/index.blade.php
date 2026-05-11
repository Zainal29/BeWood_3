@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-serif mb-6">Edit Mengapa BeWood?</h1>

    <!-- Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="font-serif text-xl mb-4">Judul & Subjudul</h2>
        <form method="POST" action="{{ route('admin.why-us.settings.update') }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label>Judul</label>
                <input type="text" name="title" value="{{ $settings['title'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label>Subjudul</label>
                <textarea name="subtitle" rows="3" class="w-full border rounded px-3 py-2">{{ $settings['subtitle'] ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn-primary px-4 py-2">Simpan Pengaturan</button>
        </form>
    </div>

    <!-- Items -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="font-serif text-xl mb-4">Tiga Pilar</h2>
        @foreach($items as $item)
        <form method="POST" action="{{ route('admin.why-us.items.update', $item) }}" class="border-t pt-6 mt-6 first:border-0 first:pt-0 first:mt-0">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ $item->title }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label>Order</label>
                    <input type="number" name="order" value="{{ $item->order }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required>{{ $item->description }}</textarea>
                </div>
                <div class="md:col-span-2 flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}> Aktif
                    </label>
                    <button type="submit" class="btn-primary px-4 py-2">Update Item</button>
                </div>
            </div>
        </form>
        @endforeach
    </div>

    <!-- Stats -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-serif text-xl mb-4">Statistik</h2>
        <div class="grid md:grid-cols-2 gap-6">
        @foreach($stats as $stat)
        <form method="POST" action="{{ route('admin.why-us.stats.update', $stat) }}" class="border p-4 rounded">
            @csrf @method('PUT')
            <div class="mb-2">
                <label>Label</label>
                <input type="text" name="label" value="{{ $stat->label }}" class="w-full border rounded px-2 py-1">
            </div>
            <div class="mb-2">
                <label>Nilai</label>
                <input type="text" name="value" value="{{ $stat->value }}" class="w-full border rounded px-2 py-1">
            </div>
            <div class="mb-2">
                <label>Order</label>
                <input type="number" name="order" value="{{ $stat->order }}" class="w-full border rounded px-2 py-1">
            </div>
            <div class="flex justify-between items-center mt-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ $stat->is_active ? 'checked' : '' }}> Aktif
                </label>
                <button type="submit" class="btn-primary px-3 py-1 text-sm">Update</button>
            </div>
        </form>
        @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#5f7e5f', timer: 3000, showConfirmButton: false });
    @endif
</script>
@endpush
