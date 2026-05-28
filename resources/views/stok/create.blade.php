@extends('layouts.app')
@section('title', 'Tambah Stok')
@section('page-title', 'Tambah Stok Baru')
@section('breadcrumb', 'Stok / Tambah')

@section('content')
<div style="max-width:700px;">
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">📦 Form Tambah Stok</h2>
        <a href="{{ route('stok.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        <form method="POST" action="{{ route('stok.store') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group-erp">
                    <label class="form-label-erp">Nama Barang *</label>
                    <input type="text" name="nama" class="form-control-erp @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group-erp">
                    <label class="form-label-erp">SKU *</label>
                    <input type="text" name="sku" class="form-control-erp @error('sku') is-invalid @enderror"
                        value="{{ old('sku') }}" placeholder="Contoh: BRG-001" required>
                    @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group-erp">
                <label class="form-label-erp">Deskripsi</label>
                <textarea name="deskripsi" class="form-control-erp" rows="2">{{ old('deskripsi') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group-erp">
                    <label class="form-label-erp">Harga Jual (Rp) *</label>
                    <input type="number" name="harga" class="form-control-erp @error('harga') is-invalid @enderror"
                        value="{{ old('harga',0) }}" min="0" required>
                    @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group-erp">
                    <label class="form-label-erp">Jumlah Stok Awal *</label>
                    <input type="number" name="jumlah_stok" class="form-control-erp @error('jumlah_stok') is-invalid @enderror"
                        value="{{ old('jumlah_stok',0) }}" min="0" required>
                    @error('jumlah_stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr style="border:none;border-top:1px solid #f0f4f8;margin:16px 0;">
            <p style="font-size:13px;color:#64748b;margin:0 0 14px;">
                <strong>ℹ️ Pembelian Otomatis:</strong> Saat stok ditambahkan, jurnal pembelian otomatis dibuat.
            </p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group-erp">
                    <label class="form-label-erp">Suplier *</label>
                    <select name="m_suplier_id" class="form-control-erp @error('m_suplier_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Suplier --</option>
                        @foreach($supliers as $sp)
                            <option value="{{ $sp->id }}" {{ old('m_suplier_id')==$sp->id?'selected':'' }}>{{ $sp->nama }}</option>
                        @endforeach
                    </select>
                    @error('m_suplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group-erp">
                    <label class="form-label-erp">Harga Beli per Unit (Rp) *</label>
                    <input type="number" name="harga_beli" class="form-control-erp @error('harga_beli') is-invalid @enderror"
                        value="{{ old('harga_beli',0) }}" min="0" required>
                    @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group-erp">
                <label class="form-label-erp">Tipe Pembayaran Pembelian *</label>
                <div style="display:flex;gap:20px;margin-top:4px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                        <input type="radio" name="tipe_pembelian" value="tunai" {{ old('tipe_pembelian','tunai')=='tunai'?'checked':'' }}>
                        💵 Tunai (DEBIT Persediaan, KREDIT Kas)
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                        <input type="radio" name="tipe_pembelian" value="kredit" {{ old('tipe_pembelian')=='kredit'?'checked':'' }}>
                        🏦 Kredit (DEBIT Persediaan, KREDIT Hutang Usaha)
                    </label>
                </div>
                @error('tipe_pembelian')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-gold-erp" style="width:100%;justify-content:center;">
                💾 Simpan Stok & Buat Jurnal Otomatis
            </button>
        </form>
    </div>
</div>
</div>
@endsection
