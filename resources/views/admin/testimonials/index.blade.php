@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-serif text-sage-900">Testimonial (Kata Mereka)</h1>
            <p class="text-sage-500 text-sm">Kelola ulasan dan testimoni pelanggan</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary px-5 py-2.5 text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Testimonial
        </a>
    </div>

    <!-- Settings Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-serif text-lg text-sage-800 mb-4">Pengaturan Judul & Subjudul</h2>
        <form method="POST" action="{{ route('admin.testimonials.settings.update') }}">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 text-sm mb-1">Judul</label>
                    <input type="text" name="title" value="{{ $title }}" class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300">
                </div>
                <div>
                    <label class="block text-sage-700 text-sm mb-1">Subjudul</label>
                    <input type="text" name="subtitle" value="{{ $subtitle }}" class="w-full border border-sage-200 rounded-lg px-4 py-2">
                </div>
            </div>
            <button type="submit" class="btn-primary px-5 py-2 text-sm mt-3">Simpan Pengaturan</button>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100">
                    @forelse($testimonials as $testi)
                    <tr class="hover:bg-sage-50 transition">
                        <td class="px-6 py-4 text-sage-600">{{ $testi->order }}</td>
                        <td class="px-6 py-4 font-medium text-sage-800">{{ $testi->customer_name }}</td>
                        <td class="px-6 py-4 text-gold">★ {{ $testi->rating }}</td>
                        <td class="px-6 py-4">
                            @if($testi->is_active)
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
                                <a href="{{ route('admin.testimonials.edit', $testi) }}" class="text-sage-600 hover:text-sage-800">Edit</a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $testi) }}" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="delete-btn text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sage-500">Belum ada testimonial. Klik "Tambah Testimonial" untuk membuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-sage-100">
            {{ $testimonials->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus testimonial?',
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
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#5f7e5f', timer: 3000, showConfirmButton: false });
    @endif
</script>
@endpush
