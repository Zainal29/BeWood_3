@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-serif mb-6">Tambah Produk</h1>

    <form method="POST" action="{{ route('admin.products.store') }}" class="bg-white p-6 rounded-xl shadow-sm" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Nama Produk -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Nama Produk</label>
                <input type="text" name="name" required class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Kategori</label>
                <select name="category_id" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Harga</label>
                <input type="number" name="price" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>

            <!-- Harga Diskon -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Harga Diskon (opsional)</label>
                <input type="number" name="discount_price" class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>

            <!-- Stok -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Stok</label>
                <input type="number" name="stock" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
            </div>

            <!-- Gambar Utama dengan preview -->
            <div>
                <label class="block text-sage-700 text-sm font-medium mb-2">Gambar Utama</label>
                <div id="mainImagePreview" class="mb-3 hidden">
                    <img id="mainPreviewImg" src="#" class="w-32 h-32 object-cover rounded-lg border shadow-sm">
                </div>
                <input type="file" name="main_image" id="mainImageInput" required accept="image/*" class="w-full text-sm text-sage-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sage-50 file:text-sage-700 hover:file:bg-sage-100">
            </div>

            <!-- Deskripsi -->
            <div class="col-span-2">
                <label class="block text-sage-700 text-sm font-medium mb-2">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full border border-sage-200 rounded-lg px-4 py-2"></textarea>
            </div>

            <!-- Spesifikasi JSON -->
            <div class="col-span-2">
                <label class="block text-sage-700 text-sm font-medium mb-2">Spesifikasi (JSON)</label>
                <textarea name="specifications" rows="3" class="w-full border border-sage-200 rounded-lg px-4 py-2 font-mono text-sm" placeholder='{"Material":"Kayu Jati","Dimensi":"180x90cm"}'></textarea>
            </div>

            <!-- Gambar Tambahan dengan preview multiple -->
            <div class="col-span-2">
                <label class="block text-sage-700 text-sm font-medium mb-2">Gambar Tambahan (bisa pilih beberapa)</label>
                <div id="additionalImagesPreview" class="flex flex-wrap gap-3 mb-3"></div>
                <input type="file" name="images[]" id="additionalImagesInput" multiple accept="image/*" class="w-full text-sm text-sage-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-sage-50 file:text-sage-700 hover:file:bg-sage-100">
            </div>

            <!-- Checkbox -->
            <div class="col-span-2 flex gap-6">
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_featured" value="1"> Unggulan</label>
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_bestseller" value="1"> Terlaris</label>
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_new" value="1"> Baru</label>
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-semibold rounded-lg">Simpan Produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn-outline-sage px-6 py-2.5 text-sm font-semibold rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview gambar utama
        const mainImageInput = document.getElementById('mainImageInput');
        const mainPreview = document.getElementById('mainImagePreview');
        const mainPreviewImg = document.getElementById('mainPreviewImg');

        mainImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    mainPreviewImg.src = event.target.result;
                    mainPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                mainPreview.classList.add('hidden');
            }
        });

        // Preview gambar tambahan (multiple)
        const additionalInput = document.getElementById('additionalImagesInput');
        const additionalPreview = document.getElementById('additionalImagesPreview');

        additionalInput.addEventListener('change', function(e) {
            additionalPreview.innerHTML = '';
            const files = Array.from(e.target.files);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const imgWrapper = document.createElement('div');
                    imgWrapper.className = 'relative inline-block';
                    imgWrapper.innerHTML = `
                        <img src="${event.target.result}" class="w-20 h-20 object-cover rounded-lg border shadow-sm">
                        <button type="button" class="remove-preview-btn absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600">×</button>
                    `;
                    additionalPreview.appendChild(imgWrapper);
                    // Hapus preview jika tombol × diklik
                    imgWrapper.querySelector('.remove-preview-btn').addEventListener('click', () => {
                        imgWrapper.remove();
                        // Hapus file dari input (tidak bisa langsung, jadi kita rebuild)
                        const dt = new DataTransfer();
                        const currentFiles = Array.from(additionalInput.files);
                        const remainingFiles = currentFiles.filter(f => f.name !== file.name);
                        remainingFiles.forEach(f => dt.items.add(f));
                        additionalInput.files = dt.files;
                    });
                };
                reader.readAsDataURL(file);
            });
        });

        // SweetAlert untuk error validasi
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#b91c1c'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#5f7e5f',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    });
</script>
@endpush