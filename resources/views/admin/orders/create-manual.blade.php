@extends('admin.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-serif text-sage-900">Tambah Pesanan Manual</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-sage-600 hover:text-sage-800">← Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.orders.store-manual') }}">
            @csrf

            <!-- Data Pelanggan (2 kolom) -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="md:col-span-2">
                    <h3 class="font-serif text-lg text-sage-800 mb-4">Informasi Pelanggan</h3>
                </div>
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-2">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                           class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300">
                    @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-2">Telepon/WA <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                           class="w-full border border-sage-200 rounded-lg px-4 py-2">
                    @error('customer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                           class="w-full border border-sage-200 rounded-lg px-4 py-2">
                    @error('customer_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sage-700 text-sm font-medium mb-2">Alamat Pengiriman <span class="text-red-500">*</span></label>
                    <textarea name="shipping_address" rows="3" required class="w-full border border-sage-200 rounded-lg px-4 py-2">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sage-700 text-sm font-medium mb-2">Catatan (opsional)</label>
                    <textarea name="note" rows="2" class="w-full border border-sage-200 rounded-lg px-4 py-2">{{ old('note') }}</textarea>
                    @error('note') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Produk Dipesan -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-serif text-lg text-sage-800">Produk Dipesan</h3>
                    <button type="button" id="addProductBtn" class="text-sage-600 hover:text-sage-800 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Produk
                    </button>
                </div>

                <div id="productsContainer" class="space-y-3">
                    <div class="product-item grid grid-cols-12 gap-3 items-end">
                        <div class="col-span-6">
                            <select name="items[0][product_id]" class="product-select w-full border border-sage-200 rounded-lg px-3 py-2" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->discount_price ?? $p->price }}">
                                        {{ $p->name }} - Rp {{ number_format($p->discount_price ?? $p->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="items[0][quantity]" class="qty-input w-full border border-sage-200 rounded-lg px-3 py-2" value="1" min="1" required>
                        </div>
                        <div class="col-span-3 text-right">
                            <button type="button" class="remove-product text-red-500 hover:text-red-700 p-1">Hapus</button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <div class="w-80 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-sage-600">Subtotal</span>
                            <span id="subtotalDisplay" class="font-semibold text-sage-800">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-sage-600">Ongkos Kirim</span>
                            <input type="number" name="shipping_cost" id="shippingCost" value="0" class="w-32 px-2 py-1 border border-sage-200 rounded text-right">
                        </div>
                        <div class="flex justify-between text-base font-bold pt-2 border-t border-sage-200">
                            <span class="text-sage-800">Total</span>
                            <span id="totalDisplay" class="text-sage-900">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.orders.index') }}" class="btn-outline-sage px-6 py-2.5 text-sm font-semibold rounded-lg">Batal</a>
                <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-semibold rounded-lg">Simpan Pesanan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let productIndex = 1;

    function updateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.product-item').forEach(row => {
            const select = row.querySelector('.product-select');
            const qty = row.querySelector('.qty-input');
            if (select.value) {
                const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                const jumlah = parseInt(qty.value) || 0;
                subtotal += price * jumlah;
            }
        });
        const shipping = parseInt(document.getElementById('shippingCost').value) || 0;
        const total = subtotal + shipping;
        document.getElementById('subtotalDisplay').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('addProductBtn').addEventListener('click', function() {
        const container = document.getElementById('productsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'product-item grid grid-cols-12 gap-3 items-end mt-3';
        newItem.innerHTML = `
            <div class="col-span-6">
                <select name="items[${productIndex}][product_id]" class="product-select w-full border border-sage-200 rounded-lg px-3 py-2" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->discount_price ?? $p->price }}">{{ $p->name }} - Rp {{ number_format($p->discount_price ?? $p->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3">
                <input type="number" name="items[${productIndex}][quantity]" class="qty-input w-full border border-sage-200 rounded-lg px-3 py-2" value="1" min="1" required>
            </div>
            <div class="col-span-3 text-right">
                <button type="button" class="remove-product text-red-500 hover:text-red-700 p-1">Hapus</button>
            </div>
        `;
        container.appendChild(newItem);
        attachEvents(newItem);
        productIndex++;
        updateTotal();
    });

    function attachEvents(item) {
        item.querySelector('.product-select').addEventListener('change', updateTotal);
        item.querySelector('.qty-input').addEventListener('input', updateTotal);
        item.querySelector('.remove-product').addEventListener('click', function() {
            if (document.querySelectorAll('.product-item').length > 1) {
                item.remove();
                updateTotal();
            } else {
                Swal.fire('Info', 'Minimal satu produk harus diisi', 'info');
            }
        });
    }

    document.querySelectorAll('.product-item').forEach(item => attachEvents(item));
    document.getElementById('shippingCost').addEventListener('input', updateTotal);
    updateTotal();

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#5f7e5f' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", confirmButtonColor: '#b91c1c' });
    @endif
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: '<ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>' });
    @endif
</script>
@endpush