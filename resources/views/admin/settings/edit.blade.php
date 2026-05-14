@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-emerald-600 transition">Dashboard</a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Hero Section</span>
    </nav>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 overflow-hidden">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="heroForm">
            @csrf
            @method('PUT')
            <div class="p-6 md:p-8 space-y-8">
                <!-- Badge Text -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Badge Text</label>
                    <input type="text" name="hero_top_text" value="{{ old('hero_top_text', $settings['hero_top_text'] ?? 'Premium Furniture') }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
                </div>

                <!-- Title (2 baris) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Baris 1</label>
                        <input type="text" name="hero_title_top" value="{{ old('hero_title_top', $settings['hero_title_top'] ?? 'Furniture Kayu') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Baris 2 (Highlight)</label>
                        <input type="text" name="hero_title_bottom" value="{{ old('hero_title_bottom', $settings['hero_title_bottom'] ?? 'Minimalis Modern') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="hero_description" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">{{ old('hero_description', $settings['hero_description'] ?? 'Hadirkan nuansa elegan dan natural untuk rumah Anda dengan produk kayu premium berkualitas tinggi.') }}</textarea>
                </div>

                <!-- Trust Badges (3 item) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @for($i = 1; $i <= 3; $i++)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Badge {{ $i }}</label>
                        <input type="text" name="hero_badge_{{ $i }}_text" value="{{ old('hero_badge_'.$i.'_text', $settings['hero_badge_'.$i.'_text'] ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
                    </div>
                    @endfor
                </div>

                <!-- Gambar Hero (Upload + Preview + Hapus) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Hero</label>
                    <div id="imagePreviewWrapper" class="relative inline-block mb-4">
                        @if(isset($settings['hero_image']))
                        <img id="previewImage" src="{{ Storage::url($settings['hero_image']) }}" class="w-48 h-48 object-cover rounded-xl border border-gray-200 shadow-sm">
                        <button type="button" id="removeImageBtn" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center hover:bg-red-600 transition">✕</button>
                        <input type="hidden" name="remove_image" id="removeHeroImage" value="0">
                        @else
                        <div id="noImageText" class="text-gray-400 text-sm mb-3">Belum ada gambar</div>
                        <img id="previewImage" src="#" class="hidden w-48 h-48 object-cover rounded-xl border border-gray-200 shadow-sm">
                        <input type="hidden" name="remove_image" id="removeHeroImage" value="0">
                        @endif
                    </div>
                    <div id="imageDropzone" class="relative border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-gray-50/30 hover:bg-gray-50/50 transition cursor-pointer group">
                        <input type="file" name="hero_image" id="heroImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/jpeg,image/png,image/jpg">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Klik atau seret gambar baru</p>
                            <p class="text-xs text-gray-400">JPG, PNG, maks 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 bg-gray-50/50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const imageInput = document.getElementById('heroImageInput');
        const previewImage = document.getElementById('previewImage');
        const removeInput = document.getElementById('removeHeroImage');
        const noImageText = document.getElementById('noImageText');
        let removeBtn = document.getElementById('removeImageBtn');

        function updatePreview(src) {
            if (!previewImage) return;
            previewImage.src = src;
            previewImage.classList.remove('hidden');
            if (noImageText) noImageText.style.display = 'none';
            if (removeInput) removeInput.value = 0;
            if (!removeBtn && document.getElementById('imagePreviewWrapper')) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.id = 'removeImageBtn';
                btn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center hover:bg-red-600 transition';
                btn.innerHTML = '✕';
                document.getElementById('imagePreviewWrapper').appendChild(btn);
                btn.addEventListener('click', removeImage);
                removeBtn = btn;
            }
        }

        function removeImage() {
            if (previewImage) {
                previewImage.src = '#';
                previewImage.classList.add('hidden');
            }
            if (imageInput) imageInput.value = '';
            if (removeInput) removeInput.value = 1;
            if (noImageText) noImageText.style.display = 'block';
            if (removeBtn) removeBtn.remove();
            removeBtn = null;
        }

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        updatePreview(ev.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Drag & drop
        const dropzone = document.getElementById('imageDropzone');
        if (dropzone) {
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-emerald-400', 'bg-emerald-50/30');
            });
            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-emerald-400', 'bg-emerald-50/30');
            });
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-emerald-400', 'bg-emerald-50/30');
                const file = e.dataTransfer.files[0];
                if (file) {
                    imageInput.files = e.dataTransfer.files;
                    imageInput.dispatchEvent(new Event('change'));
                }
            });
        }

        // If remove button already exists (existing image)
        if (removeBtn) {
            removeBtn.addEventListener('click', removeImage);
        }
    })();
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        background: '#ffffffee',
        backdrop: 'rgba(0,0,0,0.4)',
        confirmButtonColor: '#10B981',
        customClass: { popup: 'rounded-[28px] shadow-2xl' }
    });
</script>
@endif
@endsection
