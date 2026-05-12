@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-serif text-sage-900">FAQ</h1>
            <p class="text-sage-500 text-sm">Kelola pertanyaan yang sering diajukan</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary px-5 py-2.5 text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah FAQ
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-sage-200">
            <thead class="bg-sage-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600">Pertanyaan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600">Jawaban</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600">Urutan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-sage-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sage-100">
                @foreach($faqs as $faq)
                <tr>
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
                                <button type="button" class="delete-btn text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $faqs->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus FAQ?',
                text: "Tindakan ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endpush