@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-serif text-sage-900">Edit Mengapa BeWood?</h1>
            <p class="text-sage-500 text-sm mt-1">Kelola konten section "Mengapa BeWood?"</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sage-600 hover:text-sage-800">← Dashboard</a>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- ==================== SETTINGS ==================== -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6 mb-8">
        <h2 class="font-serif text-xl text-sage-800 border-b pb-3 mb-4">Pengaturan Judul & Subjudul</h2>
        <form method="POST" action="{{ route('admin.why-us.settings.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sage-700 font-medium mb-1">Judul</label>
                    <input type="text" name="title" value="{{ $settings['title'] ?? 'Mengapa BeWood?' }}"
                           class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sage-700 font-medium mb-1">Subjudul</label>
                    <textarea name="subtitle" rows="2"
                           class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300">{{ $settings['subtitle'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn-primary px-6 py-2 text-sm font-medium">Simpan Pengaturan</button>
            </div>
        </form>
    </div>

    <!-- ==================== ITEMS ==================== -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6 mb-8">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="font-serif text-xl text-sage-800">Tiga Pilar</h2>
            <button type="button" id="btn-add-item" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-sage-700 bg-sage-100 hover:bg-sage-200 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Item
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Urutan</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Judul</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Deskripsi</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Status</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-sage-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100">
                    @foreach($items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->order }}</td>
                        <td class="px-4 py-3 font-medium">{{ $item->title }}</td>
                        <td class="px-4 py-3 text-sage-600">{{ Str::limit($item->description, 60) }}</td>
                        <td class="px-4 py-3">
                            @if($item->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openEditItemModal('{{ $item->id }}', '{{ addslashes($item->title) }}', '{{ addslashes($item->description) }}', '{{ $item->order }}', {{ $item->is_active ? 'true' : 'false' }})"
                                        class="text-sage-500 hover:text-sage-700 p-1.5 rounded-md hover:bg-sage-100 transition bg-transparent" aria-label="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </button>
                                <button type="button" onclick="deleteItem('{{ $item->id }}', 'item')"
                                        class="text-red-500 hover:text-red-700 p-1.5 rounded-md hover:bg-red-100 transition bg-transparent" aria-label="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== STATS ==================== -->
    <div class="bg-white rounded-xl shadow-sm border border-sage-100 p-6">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="font-serif text-xl text-sage-800">Statistik</h2>
            <button type="button" id="btn-add-stat" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-sage-700 bg-sage-100 hover:bg-sage-200 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Statistik
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Urutan</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Label</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Nilai</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-sage-600">Status</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-sage-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100">
                    @foreach($stats as $stat)
                    <tr>
                        <td class="px-4 py-3">{{ $stat->order }}</td>
                        <td class="px-4 py-3 font-medium">{{ $stat->label }}</td>
                        <td class="px-4 py-3">{{ $stat->value }}</td>
                        <td class="px-4 py-3">
                            @if($stat->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openEditStatModal('{{ $stat->id }}', '{{ addslashes($stat->label) }}', '{{ addslashes($stat->value) }}', '{{ $stat->order }}', {{ $stat->is_active ? 'true' : 'false' }})"
                                        class="text-sage-500 hover:text-sage-700 p-1.5 rounded-md hover:bg-sage-100 transition bg-transparent" aria-label="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </button>
                                <button type="button" onclick="deleteItem('{{ $stat->id }}', 'stat')"
                                        class="text-red-500 hover:text-red-700 p-1.5 rounded-md hover:bg-red-100 transition bg-transparent" aria-label="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Item (tanpa blur) -->
<div id="modal-item" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-serif text-xl">Edit Item</h3>
            <button onclick="closeModal('modal-item')" class="text-sage-400 hover:text-sage-600">✕</button>
        </div>
        <form id="form-item" method="POST" action="" class="p-6 space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="id" id="item-id">
            <div>
                <label class="block text-sage-700 mb-1">Judul</label>
                <input type="text" name="title" id="item-title" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sage-700 mb-1">Deskripsi</label>
                <textarea name="description" id="item-desc" rows="4" required class="w-full border border-sage-200 rounded-lg px-4 py-2"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 mb-1">Urutan</label>
                    <input type="number" name="order" id="item-order" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="item-active" value="1" class="rounded">
                        <span class="text-sage-700">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-item')" class="btn-outline-sage px-4 py-2 text-sm">Batal</button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Stat (tanpa blur) -->
<div id="modal-stat" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-serif text-xl">Edit Statistik</h3>
            <button onclick="closeModal('modal-stat')" class="text-sage-400 hover:text-sage-600">✕</button>
        </div>
        <form id="form-stat" method="POST" action="" class="p-6 space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="id" id="stat-id">
            <div>
                <label class="block text-sage-700 mb-1">Label</label>
                <input type="text" name="label" id="stat-label" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sage-700 mb-1">Nilai</label>
                <input type="text" name="value" id="stat-value" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 mb-1">Urutan</label>
                    <input type="number" name="order" id="stat-order" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="stat-active" value="1" class="rounded">
                        <span class="text-sage-700">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-stat')" class="btn-outline-sage px-4 py-2 text-sm">Batal</button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Item (tanpa blur) -->
<div id="modal-add-item" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-serif text-xl">Tambah Item Baru</h3>
            <button onclick="closeModal('modal-add-item')" class="text-sage-400 hover:text-sage-600">✕</button>
        </div>
        <form id="form-add-item" method="POST" action="{{ route('admin.why-us.items.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sage-700 mb-1">Judul</label>
                <input type="text" name="title" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sage-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="4" required class="w-full border border-sage-200 rounded-lg px-4 py-2"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 mb-1">Urutan</label>
                    <input type="number" name="order" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded">
                        <span class="text-sage-700">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-add-item')" class="btn-outline-sage px-4 py-2 text-sm">Batal</button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Stat (tanpa blur) -->
<div id="modal-add-stat" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-serif text-xl">Tambah Statistik Baru</h3>
            <button onclick="closeModal('modal-add-stat')" class="text-sage-400 hover:text-sage-600">✕</button>
        </div>
        <form id="form-add-stat" method="POST" action="{{ route('admin.why-us.stats.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sage-700 mb-1">Label</label>
                <input type="text" name="label" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sage-700 mb-1">Nilai</label>
                <input type="text" name="value" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 mb-1">Urutan</label>
                    <input type="number" name="order" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded">
                        <span class="text-sage-700">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-add-stat')" class="btn-outline-sage px-4 py-2 text-sm">Batal</button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<form id="delete-form" method="POST" class="hidden">
    @csrf @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    // Menampilkan modal edit item
    function openEditItemModal(id, title, desc, order, active) {
        document.getElementById('form-item').action = `/admin/why-us/items/${id}`;
        document.getElementById('item-id').value = id;
        document.getElementById('item-title').value = title;
        document.getElementById('item-desc').value = desc;
        document.getElementById('item-order').value = order;
        document.getElementById('item-active').checked = active;
        document.getElementById('modal-item').style.display = 'flex';
    }
    function openEditStatModal(id, label, value, order, active) {
        document.getElementById('form-stat').action = `/admin/why-us/stats/${id}`;
        document.getElementById('stat-id').value = id;
        document.getElementById('stat-label').value = label;
        document.getElementById('stat-value').value = value;
        document.getElementById('stat-order').value = order;
        document.getElementById('stat-active').checked = active;
        document.getElementById('modal-stat').style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    // SweetAlert konfirmasi hapus
    function deleteItem(id, type) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data akan dihapus permanen",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = `/admin/why-us/${type}s/${id}`;
                form.submit();
            }
        });
    }
    // Modal tambah
    document.getElementById('btn-add-item').addEventListener('click', () => {
        document.getElementById('modal-add-item').style.display = 'flex';
    });
    document.getElementById('btn-add-stat').addEventListener('click', () => {
        document.getElementById('modal-add-stat').style.display = 'flex';
    });
    // Tutup modal klik di luar
    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('bg-black/50')) {
            document.querySelectorAll('[id^=modal-]').forEach(modal => modal.style.display = 'none');
        }
    });
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#5f7e5f', timer: 3000, showConfirmButton: false });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", confirmButtonColor: '#b91c1c' });
    @endif
</script>
@endpush
