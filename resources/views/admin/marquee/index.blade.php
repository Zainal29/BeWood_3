@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Marquee / Running Text</h1>
            <p class="text-slate-500 text-sm mt-0.5">Teks berjalan di bagian atas landing page</p>
        </div>
    </div>

    <!-- Form Tambah Item -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.marquee.store') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label class="block text-slate-700 text-sm font-medium mb-1">Teks Baru</label>
                <input type="text" name="text" placeholder="Masukkan teks marquee..." required
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm
                              focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:bg-white transition
                              outline-none">
            </div>
            <div class="w-28">
                <label class="block text-slate-700 text-sm font-medium mb-1">Urutan</label>
                <input type="number" name="order" value="0"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm
                              focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:bg-white transition
                              outline-none">
            </div>
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm text-slate-700">Aktif</span>
                </label>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-sm transition-all hover:shadow-md">
                    Tambah
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Item Marquee -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Teks</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $item->text }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $item->order }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Edit button -->
                                <button type="button" onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->text) }}', {{ $item->order }}, {{ $item->is_active ? 'true' : 'false' }})"
                                        class="text-sage-500 hover:text-sage-700 p-1.5 rounded-md hover:bg-sage-100 transition bg-transparent">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                </button>

                                <!-- Delete button dengan form terpisah -->
                                <form method="POST" action="{{ route('admin.marquee.destroy', $item) }}" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-btn text-red-500 hover:text-red-700 p-1.5 rounded-md hover:bg-red-100 transition bg-transparent">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada item marquee. Silakan tambah di atas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-white">
            {{ $items->links() }}
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-200">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold tracking-tight text-slate-800">Edit Item Marquee</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition text-2xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-medium mb-1">Teks</label>
                <input type="text" name="text" id="edit_text" required
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm
                              focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:bg-white transition
                              outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-medium mb-1">Urutan</label>
                <input type="number" name="order" id="edit_order"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm
                              focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:bg-white transition
                              outline-none">
            </div>
            <div class="mb-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm text-slate-700">Aktif</span>
                </label>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium shadow-sm transition-all hover:shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Konfirmasi hapus dengan SweetAlert (menggunakan class delete-btn)
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus item marquee?',
                text: "Tindakan ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
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

    // SweetAlert notifikasi dari session
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#10b981', timer: 3000, showConfirmButton: false });
    @endif
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: '<ul class="text-left">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>', confirmButtonColor: '#b91c1c' });
    @endif
</script>
@endpush
