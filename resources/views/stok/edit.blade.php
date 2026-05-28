@extends('layouts.app')
@section('title', 'Edit Stok')
@section('page-title', 'Edit Stok')
@section('breadcrumb', 'Stok / Edit')

@section('content')
<div style="max-width:600px;">
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">✏️ Edit Data Stok</h2>
        <a href="{{ route('stok.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        <form method="POST" action="{{ route('stok.update',$stok->id) }}">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group-erp">
                    <label class="form-label-erp">Nama Barang *</label>
                    <input type="text" name="nama" class="form-control-erp @error('nama') is-invalid @enderror"
                        value="{{ old('nama',$stok->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group-erp">
                    <label class="form-label-erp">SKU *</label>
                    <input type="text" name="sku" class="form-control-erp @error('sku') is-invalid @enderror"
                        value="{{ old('sku',$stok->sku) }}" required>
                    @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group-erp">
                <label class="form-label-erp">Deskripsi</label>
                <textarea name="deskripsi" class="form-control-erp" rows="2">{{ old('deskripsi',$stok->deskripsi) }}</textarea>
            </div>

            <div class="form-group-erp">
                <label class="form-label-erp">Harga Jual (Rp) *</label>
                <input type="number" name="harga" class="form-control-erp @error('harga') is-invalid @enderror"
                    value="{{ old('harga',$stok->harga) }}" min="0" required>
                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div style="background:#fef9c3;border:1px solid #fcd34d;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#92400e;">
                ⚠️ Stok saat ini: <strong>{{ $stok->jumlah_stok }} unit</strong>. Untuk menambah stok, gunakan menu <strong>Pembelian</strong>.
            </div>

            <button type="submit" class="btn-gold-erp" style="width:100%;justify-content:center;">
                💾 Simpan Perubahan
            </button>
        </form>
    </div>
</div>
</div>
@endsection
