@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-serif text-sage-900">FAQ</h1>
            <p class="text-sage-500 text-sm">Kelola pertanyaan yang sering diajukan</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary px-5 py-2.5 text-sm inline-flex items-center gap-2">+ Tambah FAQ</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Pertanyaan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Jawaban</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100">
                    @foreach($faqs as $faq)
                    <tr class="hover:bg-sage-50 transition">
                        <td class="px-6 py-4 font-medium text-sage-800">{{ $faq->question }}</td>
                        <td class="px-6 py-4 text-sage-600">{{ Str::limit($faq->answer, 80) }}</td>
                        <td class="px-6 py-4 text-sage-600">{{ $faq->order }}</td>
                        <td class="px-6 py-4">
                            @if($faq->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-sage-600 hover:text-sage-800">Edit</a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="delete-btn text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-sage-100">{{ $faqs->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({ title: 'Hapus FAQ?', text: "Tindakan ini tidak bisa dibatalkan.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#b91c1c', confirmButtonText: 'Ya, hapus!' }).then((result) => {
                if (result.isConfirmed) this.closest('form').submit();
            });
        });
    });
    @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#5f7e5f', timer: 3000, showConfirmButton: false }); @endif
</script>
@endpush
