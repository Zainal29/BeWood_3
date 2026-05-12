@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif">Marquee / Running Text</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sage-600 hover:text-sage-800">← Kembali</a>
    </div>

    <!-- Form Tambah Item -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="font-serif text-lg mb-4">Tambah Item Marquee</h2>
        <form method="POST" action="{{ route('admin.marquee.store') }}">
            @csrf
            <div class="flex gap-4">
                <input type="text" name="text" placeholder="Teks marquee..." required class="flex-1 border border-sage-200 rounded-lg px-4 py-2">
                <button type="submit" class="btn-primary px-6 py-2">Tambah</button>
            </div>
        </form>
    </div>

    <!-- Daftar Item -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-serif text-lg mb-4">Daftar Item Marquee</h2>
        <div class="space-y-3">
            @foreach($items as $item)
            <form method="POST" action="{{ route('admin.marquee.update', $item) }}" class="border-b border-sage-100 pb-3">
                @csrf @method('PUT')
                <div class="flex flex-wrap gap-3 items-center">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="text" value="{{ $item->text }}" class="w-full border border-sage-200 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="w-24">
                        <input type="number" name="order" value="{{ $item->order }}" class="w-full border border-sage-200 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
                            <span class="text-sm">Aktif</span>
                        </label>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="text-sage-600 hover:text-sage-800 px-3 py-1 text-sm">Update</button>
                        <button type="button" class="delete-item text-red-600 hover:text-red-800 px-3 py-1 text-sm" data-id="{{ $item->id }}">Hapus</button>
                    </div>
                </div>
            </form>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Hapus item?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/marquee/items/${id}`;
                    form.innerHTML = '@csrf @method("DELETE")';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush