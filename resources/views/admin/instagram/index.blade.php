@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-serif text-sage-900">Instagram Feed</h1>
            <p class="text-sage-500 text-sm">Kelola postingan Instagram yang tampil di landing page</p>
        </div>
        <a href="{{ route('admin.instagram.create') }}" class="btn-primary px-5 py-2.5 text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Postingan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden group transition hover:shadow-md">
            <div class="aspect-square overflow-hidden bg-sage-100">
                <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover transition group-hover:scale-105 duration-300" loading="lazy">
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    @if($post->instagram_url)
                    <a href="{{ $post->instagram_url }}" target="_blank" class="text-sage-600 hover:text-sage-800 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        Lihat Postingan
                    </a>
                    @else
                    <span class="text-sage-400 text-sm">Tidak ada link</span>
                    @endif

                    {{-- Tombol dengan ukuran lebih besar dan efek hover jelas --}}
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.instagram.edit', $post) }}"
                           class="text-sage-500 hover:text-sage-700 p-1.5 rounded-md hover:bg-sage-100 transition bg-transparent"
                           aria-label="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                            </svg>
                        </a>
                        <button type="button"
                                class="delete-btn text-red-500 hover:text-red-700 p-1.5 rounded-md hover:bg-red-100 transition bg-transparent"
                                data-id="{{ $post->id }}"
                                aria-label="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                        <form id="delete-form-{{ $post->id }}" action="{{ route('admin.instagram.destroy', $post) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $post->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ $post->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="text-xs text-sage-400">{{ $post->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-xl shadow-sm">
            <p class="text-sage-500">Belum ada postingan Instagram.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $posts->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Hapus postingan?',
                text: "Tindakan ini bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush
