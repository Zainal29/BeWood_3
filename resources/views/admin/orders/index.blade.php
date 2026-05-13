@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif text-sage-900 font-light">Pesanan</h1>
            <p class="text-sage-500 text-sm mt-1">Kelola semua pesanan pelanggan</p>
        </div>
        <button type="button" id="btn-tambah-pesanan" class="btn-primary px-5 py-2.5 text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Pesanan
        </button>
    </div>

    <!-- Filter Status -->
    <div class="bg-white rounded-xl shadow-sm p-4 flex flex-wrap gap-3">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Semua</a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'pending' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Pending</a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'processing' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Processing</a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'shipped' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Shipped</a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'delivered' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Delivered</a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'cancelled' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Cancelled</a>
    </div>

    <!-- Tabel Pesanan -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">No. Order</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Status Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Status Kirim</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100 bg-white">
                    @forelse ($orders as $order)
                    <tr class="hover:bg-sage-50/50 transition">
                        <td class="px-6 py-4 font-medium text-sage-800">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-sage-600">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-sage-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $paymentBadge = match($order->payment_status) {
                                    'paid' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                    default => 'bg-sage-100 text-sage-700'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $paymentBadge }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $deliveryBadge = match($order->delivery_status) {
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'shipped' => 'bg-blue-100 text-blue-700',
                                    'processing' => 'bg-sage-100 text-sage-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $deliveryBadge }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ ucfirst($order->delivery_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sage-500 text-sm">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 text-sage-600 hover:text-sage-800 transition text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sage-500">
                            <svg class="w-12 h-12 mx-auto text-sage-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                            </svg>
                            <p>Belum ada pesanan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>

<!-- Modal Tambah Pesanan -->
<div id="modal-add-order" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 overflow-y-auto p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full my-8">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-serif text-xl">Tambah Pesanan Manual</h3>
            <button type="button" class="close-modal text-sage-400 hover:text-sage-600">✕</button>
        </div>
        <form id="form-add-order" class="p-6 space-y-6">
            @csrf
            <!-- Informasi Pelanggan -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-1">Nama Pelanggan <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" required class="w-full border border-sage-200 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-1">Telepon / WA <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_phone" required class="w-full border border-sage-200 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sage-700 text-sm font-medium mb-1">Email</label>
                    <input type="email" name="customer_email" class="w-full border border-sage-200 rounded-lg px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sage-700 text-sm font-medium mb-1">Alamat Pengiriman <span class="text-red-500">*</span></label>
                    <textarea name="shipping_address" rows="2" required class="w-full border border-sage-200 rounded-lg px-3 py-2"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sage-700 text-sm font-medium mb-1">Catatan (opsional)</label>
                    <textarea name="note" rows="2" class="w-full border border-sage-200 rounded-lg px-3 py-2"></textarea>
                </div>
            </div>

            <!-- Produk Dipesan -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sage-700 text-sm font-medium">Produk Dipesan</label>
                    <button type="button" id="add-product-row" class="text-sage-600 hover:text-sage-800 text-sm inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Produk
                    </button>
                </div>
                <div id="products-container" class="space-y-3">
                    <div class="product-row grid grid-cols-12 gap-3 items-start">
                        <div class="col-span-6">
                            <select name="items[0][product_id]" class="product-select w-full border border-sage-200 rounded-lg px-3 py-2 text-sm" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($allProducts as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->discount_price ?? $product->price }}">{{ $product->name }} - Rp {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="items[0][quantity]" class="qty-input w-full border border-sage-200 rounded-lg px-3 py-2 text-sm" value="1" min="1" required>
                        </div>
                        <div class="col-span-3 text-right">
                            <button type="button" class="remove-product-row text-red-500 hover:text-red-700 p-1">Hapus</button>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <div class="w-80 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-sage-600">Subtotal</span>
                            <span id="subtotal-display" class="font-semibold text-sage-800">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-sage-600">Ongkos Kirim</span>
                            <input type="number" name="shipping_cost" id="shipping-cost" value="0" class="w-32 px-2 py-1 border border-sage-200 rounded text-right">
                        </div>
                        <div class="flex justify-between text-base font-semibold pt-2 border-t">
                            <span class="text-sage-800">Total</span>
                            <span id="total-display" class="text-sage-900">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" class="close-modal btn-outline-sage px-4 py-2 text-sm">Batal</button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Simpan Pesanan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-add-order');
    const openBtn = document.getElementById('btn-tambah-pesanan');
    const closeBtns = document.querySelectorAll('.close-modal');
    const form = document.getElementById('form-add-order');
    const container = document.getElementById('products-container');
    const addProductBtn = document.getElementById('add-product-row');
    const shippingInput = document.getElementById('shipping-cost');
    const subtotalSpan = document.getElementById('subtotal-display');
    const totalSpan = document.getElementById('total-display');

    let productIndex = 1;

    // Buka modal
    openBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    // Tutup modal
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.style.display = 'none';
            form.reset();
            // Reset product rows ke hanya 1 baris
            container.innerHTML = '';
            addNewProductRow();
            productIndex = 1;
            updateTotal();
        });
    });

    // Tutup modal klik di luar
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            form.reset();
        }
    });

    // Tambah baris produk
    function addNewProductRow() {
        const row = document.createElement('div');
        row.className = 'product-row grid grid-cols-12 gap-3 items-start mt-3';
        row.innerHTML = `
            <div class="col-span-6">
                <select name="items[${productIndex}][product_id]" class="product-select w-full border border-sage-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($allProducts as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->discount_price ?? $product->price }}">{{ $product->name }} - Rp {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3">
                <input type="number" name="items[${productIndex}][quantity]" class="qty-input w-full border border-sage-200 rounded-lg px-3 py-2 text-sm" value="1" min="1" required>
            </div>
            <div class="col-span-3 text-right">
                <button type="button" class="remove-product-row text-red-500 hover:text-red-700 p-1">Hapus</button>
            </div>
        `;
        container.appendChild(row);
        attachRowEvents(row);
        productIndex++;
    }

    function attachRowEvents(row) {
        const select = row.querySelector('.product-select');
        const qty = row.querySelector('.qty-input');
        const removeBtn = row.querySelector('.remove-product-row');
        select.addEventListener('change', updateTotal);
        qty.addEventListener('input', updateTotal);
        removeBtn.addEventListener('click', () => {
            if (document.querySelectorAll('.product-row').length > 1) {
                row.remove();
                updateTotal();
            } else {
                Swal.fire('Info', 'Minimal satu produk harus diisi', 'info');
            }
        });
    }

    // Hitung total
    function updateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.product-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const qty = row.querySelector('.qty-input');
            if (select && select.value) {
                const price = parseFloat(select.options[select.selectedIndex]?.dataset?.price || 0);
                const quantity = parseInt(qty.value) || 0;
                subtotal += price * quantity;
            }
        });
        const shipping = parseInt(shippingInput.value) || 0;
        const total = subtotal + shipping;
        subtotalSpan.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        totalSpan.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    shippingInput.addEventListener('input', updateTotal);

    // Inisialisasi row pertama
    document.querySelectorAll('.product-row').forEach(row => attachRowEvents(row));
    addProductBtn.addEventListener('click', addNewProductRow);

    // Submit form via AJAX
form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Tampilkan loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const formData = new FormData(form);

    fetch('{{ route("admin.orders.store-manual") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                confirmButtonColor: '#5f7e5f'
            }).then(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    location.reload();
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: data.message || 'Terjadi kesalahan',
                confirmButtonColor: '#b91c1c'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
            confirmButtonColor: '#b91c1c'
        });
    });
});
});
</script>

@push('scripts')
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#5f7e5f', timer: 3000, showConfirmButton: false });
</script>
@endif
@endpush
@endsection
