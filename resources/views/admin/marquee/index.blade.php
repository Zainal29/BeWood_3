@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-serif text-sage-900">Marquee / Running Text</h1>
            <p class="text-sage-500 text-sm">Teks berjalan di bagian atas landing page</p>
        </div>
    </div>

    <!-- Form tambah item -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.marquee.store') }}" class="flex flex-wrap gap-3 items-end">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sage-700 text-sm mb-1">Teks baru</label>
                <input type="text" name="text" placeholder="Masukkan teks marquee..." required
                       class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300">
            </div>
            <div class="w-32">
                <label class="block text-sage-700 text-sm mb-1">Urutan</label>
                <input type="number" name="order" value="0" class="w-full border border-sage-200 rounded-lg px-3 py-2">
            </div>
            <div class="flex items-center gap-3 mb-0.5">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded">
                    <span class="text-sm text-sage-700">Aktif</span>
                </label>
                <button type="submit" class="btn-primary px-5 py-2 text-sm">Tambah</button>
            </div>
        </form>
    </div>

    <!-- Daftar item marquee -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Teks</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100">
                    @forelse($items as $item)
                    <tr class="hover:bg-sage-50 transition">
                        <td class="px-6 py-4 font-medium text-sage-800">{{ $item->text }}</td>
                        <td class="px-6 py-4 text-sage-600">{{ $item->order }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <!-- Tombol Edit (buka modal inline atau edit langsung? Gunakan form terpisah agar rapi) -->
                                <button type="button" onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->text) }}', {{ $item->order }}, {{ $item->is_active ? 'true' : 'false' }})"
                                        class="text-sage-600 hover:text-sage-800">Edit</button>
                                <form method="POST" action="{{ route('admin.marquee.destroy', $item) }}" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="delete-btn text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sage-500">Belum ada item marquee. Silakan tambah di atas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-sage-100">
            {{ $items->links() }}
        </div>
    </div>
</div>

<!-- Modal Edit (Modern dengan SweetAlert? Gunakan modal Tailwind sederhana) -->
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-serif text-xl text-sage-900">Edit Item Marquee</h3>
            <button onclick="closeEditModal()" class="text-sage-400 hover:text-sage-600 text-2xl">&times;</button>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT')
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Teks</label>
                <input type="text" name="text" id="edit_text" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sage-700 text-sm mb-1">Urutan</label>
                <input type="number" name="order" id="edit_order" class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div class="mb-6">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="rounded">
                    <span class="text-sm text-sage-700">Aktif</span>
                </label>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="btn-outline-sage px-4 py-2 text-sm">Batal</button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Konfirmasi hapus
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus item marquee?',
                text: "Tindakan ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#5f7e5f',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) this.closest('form').submit();
            });
        });
    });

    // Modal edit
    function openEditModal(id, text, order, isActive) {
        const form = document.getElementById('editForm');
        form.action = `/admin/marquee/${id}`;
        document.getElementById('edit_text').value = text;
        document.getElementById('edit_order').value = order;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    // Tutup modal jika klik luar area putih
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // SweetAlert notifikasi
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#5f7e5f', timer: 3000, showConfirmButton: false });
    @endif
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: '<ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>', confirmButtonColor: '#b91c1c' });
    @endif
</script>
@endpush
