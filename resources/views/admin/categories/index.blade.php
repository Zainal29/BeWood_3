@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif text-sage-900 font-light">Kategori</h1>
            <p class="text-sage-500 text-sm mt-1">Kelola kategori produk furniture</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary px-5 py-2.5 text-sm font-sans font-medium inline-flex items-center gap-2 shadow-sm hover:shadow transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Kategori
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100 bg-white">
                    @forelse ($categories as $category)
                    <tr class="hover:bg-sage-50/50 transition">
                        <td class="px-6 py-4 text-sm text-sage-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
    @if ($category->image)
        <img src="{{ Storage::url($category->image) }}" class="w-12 h-12 rounded-lg object-cover shadow-sm">
    @else
        <div class="w-12 h-12 bg-sage-100 rounded-lg flex items-center justify-center text-sage-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    @endif

                        <td class="px-6 py-4 font-medium text-sage-800">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sage-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            @if ($category->is_active)
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
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.categories.edit', $category) }}" class="text-sage-500 hover:text-sage-700 p-1.5 rounded-md hover:bg-sage-100 transition bg-transparent" aria-label="Edit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
            </svg>
        </a>
        <button type="button" class="delete-category-btn text-red-500 hover:text-red-700 p-1.5 rounded-md hover:bg-red-100 transition bg-transparent"
                data-id="{{ $category->id }}" data-name="{{ $category->name }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        </button>
        <form id="delete-category-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sage-500">
                            <svg class="w-12 h-12 mx-auto text-sage-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <p>Belum ada kategori.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== Image Preview Logic (existing) =====
    const imageInput = document.getElementById('imageInput');
    const previewImg = document.getElementById('previewImg');
    const newPreviewDiv = document.getElementById('newImagePreview');
    const currentContainer = document.getElementById('currentImageContainer');
    const removeBtn = document.getElementById('removeImageBtn');
    const removeInput = document.getElementById('removeImageInput');
    const noImageText = document.getElementById('noImageText');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    previewImg.classList.remove('hidden');
                    if (currentContainer) currentContainer.classList.add('hidden');
                    if (noImageText) noImageText.classList.add('hidden');
                    newPreviewDiv.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeBtn && removeInput) {
        removeBtn.addEventListener('click', function() {
            removeInput.value = '1';
            if (currentContainer) currentContainer.classList.add('hidden');
            previewImg.classList.add('hidden');
            if (imageInput) imageInput.value = '';
            if (noImageText) noImageText.classList.remove('hidden');
            newPreviewDiv.classList.add('hidden');
        });
    }

    // ===== DELETE CATEGORY LOGIC (NEW) =====
    document.querySelectorAll('.delete-category-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;

            // SweetAlert confirmation
            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Kategori "${name}" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form delete
                    document.getElementById(`delete-category-${id}`).submit();
                }
            });
        });
    });
});
</script>
@endpush
