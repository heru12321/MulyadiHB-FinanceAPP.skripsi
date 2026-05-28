@extends('layouts.app')
@section('title', 'Catat Pembelian')
@section('page-title', 'Catat Pembelian Baru')
@section('breadcrumb', 'Transaksi / Pembelian / Catat')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">🛒 Form Pembelian</h2>
        <a href="{{ route('pembelian.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        <form method="POST" action="{{ route('pembelian.store') }}" id="formPembelian">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group-erp">
                    <label class="form-label-erp">Suplier *</label>
                    <select name="m_suplier_id" class="form-control-erp @error('m_suplier_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Suplier --</option>
                        @foreach($supliers as $sp)
                            <option value="{{ $sp->id }}" {{ old('m_suplier_id') == $sp->id ? 'selected' : '' }}>{{ $sp->nama }}</option>
                        @endforeach
                    </select>
                    @error('m_suplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group-erp">
                    <label class="form-label-erp">Tanggal Pembelian *</label>
                    <input type="date" name="tanggal" class="form-control-erp @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group-erp">
                <label class="form-label-erp">Status Pembayaran *</label>
                <div style="display:flex;gap:20px;margin-top:8px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="radio" name="is_lunas" value="1" {{ old('is_lunas', '1') == '1' ? 'checked' : '' }}>
                        💵 Lunas (Tunai)
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="radio" name="is_lunas" value="0" {{ old('is_lunas') == '0' ? 'checked' : '' }}>
                        🏦 Kredit (Hutang Usaha)
                    </label>
                </div>
                @error('is_lunas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group-erp">
                <label class="form-label-erp">Keterangan</label>
                <input type="text" name="keterangan" class="form-control-erp" value="{{ old('keterangan') }}" placeholder="Catatan opsional">
            </div>

            <hr style="border:none;border-top:1px solid #e2e8f0;margin:24px 0;">

            <h3 style="font-size:15px;color:var(--brand-dark);margin-bottom:16px;">Daftar Item Masuk</h3>
            
            <table class="table-erp" style="margin-bottom:16px;">
                <thead>
                    <tr>
                        <th style="width:40%;">Barang</th>
                        <th style="width:20%;">Harga Beli (Rp)</th>
                        <th style="width:15%;">Jumlah</th>
                        <th style="width:20%;">Subtotal (Rp)</th>
                        <th style="width:5%;"></th>
                    </tr>
                </thead>
                <tbody id="items-container">
                    <tr class="item-row">
                        <td>
                            <select name="items[0][m_stok_id]" class="form-control-erp item-select" required>
                                <option value="" data-harga="0">-- Pilih Barang --</option>
                                @foreach($stoks as $s)
                                    <option value="{{ $s->id }}" data-harga="{{ $s->harga }}">
                                        {{ $s->nama }} (SKU: {{ $s->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][harga_beli]" class="form-control-erp item-harga" value="0" min="0" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][jumlah]" class="form-control-erp item-jumlah" value="1" min="1" required>
                        </td>
                        <td>
                            <input type="text" class="form-control-erp item-subtotal" value="0" readonly style="background:#f8fafc;font-weight:bold;">
                        </td>
                        <td>
                            <button type="button" class="btn-danger-erp btn-sm-erp btn-hapus-item" style="padding:6px 10px;">X</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;font-weight:700;">Grand Total</td>
                        <td>
                            <input type="text" id="grand-total" class="form-control-erp" value="0" readonly style="background:#f8fafc;font-weight:bold;color:#16a34a;font-size:16px;">
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
            <button type="button" id="btn-tambah-item" class="btn-primary-erp btn-sm-erp" style="margin-bottom:24px;">+ Tambah Item</button>

            <button type="submit" class="btn-gold-erp" style="width:100%;justify-content:center;font-size:16px;padding:12px;">💾 Simpan Pembelian & Buat Jurnal Otomatis</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = 1;
    const itemsContainer = document.getElementById('items-container');
    const btnTambah = document.getElementById('btn-tambah-item');
    const grandTotalEl = document.getElementById('grand-total');

    const getRowHtml = (index) => `
        <tr class="item-row">
            <td>
                <select name="items[${index}][m_stok_id]" class="form-control-erp item-select" required>
                    <option value="" data-harga="0">-- Pilih Barang --</option>
                    @foreach($stoks as $s)
                        <option value="{{ $s->id }}" data-harga="{{ $s->harga }}">
                            {{ $s->nama }} (SKU: {{ $s->sku }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="items[${index}][harga_beli]" class="form-control-erp item-harga" value="0" min="0" required>
            </td>
            <td>
                <input type="number" name="items[${index}][jumlah]" class="form-control-erp item-jumlah" value="1" min="1" required>
            </td>
            <td>
                <input type="text" class="form-control-erp item-subtotal" value="0" readonly style="background:#f8fafc;font-weight:bold;">
            </td>
            <td>
                <button type="button" class="btn-danger-erp btn-sm-erp btn-hapus-item" style="padding:6px 10px;">X</button>
            </td>
        </tr>
    `;

    btnTambah.addEventListener('click', () => {
        itemsContainer.insertAdjacentHTML('beforeend', getRowHtml(itemIndex));
        itemIndex++;
        calculateTotal();
    });

    itemsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-hapus-item')) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                calculateTotal();
            } else {
                alert('Minimal harus ada 1 item barang.');
            }
        }
    });

    itemsContainer.addEventListener('change', (e) => {
        if (e.target.classList.contains('item-select')) {
            calculateTotal();
        }
    });

    itemsContainer.addEventListener('input', (e) => {
        if (e.target.classList.contains('item-harga') || e.target.classList.contains('item-jumlah')) {
            calculateTotal();
        }
    });

    function calculateTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const harga = parseInt(row.querySelector('.item-harga').value) || 0;
            const qty = parseInt(row.querySelector('.item-jumlah').value) || 0;
            const subtotal = harga * qty;
            
            row.querySelector('.item-subtotal').value = formatRupiah(subtotal);
            grandTotal += subtotal;
        });
        grandTotalEl.value = formatRupiah(grandTotal);
    }
    
    calculateTotal();
</script>
@endpush
@endsection
