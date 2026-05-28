@extends('layouts.app')
@section('title','Tambah Suplier')
@section('page-title','Tambah Suplier')

@section('content')
<div style="max-width:500px;">
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">🏭 Form Suplier</h2>
        <a href="{{ route('suplier.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        <form method="POST" action="{{ route('suplier.store') }}">
            @csrf
            <div class="form-group-erp">
                <label class="form-label-erp">Nama Suplier *</label>
                <input type="text" name="nama" class="form-control-erp @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group-erp">
                <label class="form-label-erp">No. Telepon</label>
                <input type="text" name="no_telp" class="form-control-erp"
                    value="{{ old('no_telp') }}" placeholder="08xx-xxxx-xxxx">
            </div>
            <button type="submit" class="btn-gold-erp" style="width:100%;justify-content:center;">💾 Simpan</button>
        </form>
    </div>
</div>
</div>
@endsection
