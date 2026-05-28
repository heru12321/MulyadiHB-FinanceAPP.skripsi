@extends('layouts.app')
@section('title','Edit Suplier')
@section('page-title','Edit Suplier')

@section('content')
<div style="max-width:500px;">
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">✏️ Edit Suplier</h2>
        <a href="{{ route('suplier.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        <form method="POST" action="{{ route('suplier.update',$suplier->id) }}">
            @csrf @method('PUT')
            <div class="form-group-erp">
                <label class="form-label-erp">Nama Suplier *</label>
                <input type="text" name="nama" class="form-control-erp @error('nama') is-invalid @enderror"
                    value="{{ old('nama',$suplier->nama) }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group-erp">
                <label class="form-label-erp">No. Telepon</label>
                <input type="text" name="no_telp" class="form-control-erp"
                    value="{{ old('no_telp',$suplier->no_telp) }}">
            </div>
            <button type="submit" class="btn-gold-erp" style="width:100%;justify-content:center;">💾 Simpan</button>
        </form>
    </div>
</div>
</div>
@endsection
