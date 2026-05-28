@extends('layouts.app')
@section('title', 'Bayar Hutang')
@section('page-title', 'Form Pembayaran Hutang')
@section('breadcrumb', 'Transaksi / Pembayaran / Hutang')

@section('content')
<div style="max-width:600px;">
<div class="card-erp" style="border-top:3px solid #dc2626;">
    <div class="card-erp-header">
        <h2 class="card-erp-title" style="color:#dc2626;">📤 Bayar Hutang - {{ $pembelian->kode_faktur }}</h2>
        <a href="{{ route('pembayaran.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        
        <div style="background:#f8fafc;padding:16px;border-radius:10px;margin-bottom:20px;">
            <table style="width:100%;font-size:14px;border-spacing:0 6px;">
                <tr><td style="color:#64748b;">Suplier</td><td>: <strong>{{ $pembelian->suplier->nama ?? '-' }}</strong></td></tr>
                <tr><td style="color:#64748b;">Tanggal Faktur</td><td>: {{ $pembelian->tanggal->format('d/m/Y') }}</td></tr>
                <tr><td style="color:#64748b;">Total Tagihan</td><td class="money">: Rp {{ number_format($pembelian->total_harga,0,',','.') }}</td></tr>
                <tr><td style="color:#64748b;">Sudah Dibayar</td><td class="money" style="color:#16a34a;">: Rp {{ number_format($pembelian->total_dibayar,0,',','.') }}</td></tr>
                <tr><td style="color:#64748b;font-weight:bold;">Sisa Hutang</td><td class="money" style="color:#dc2626;font-size:16px;font-weight:bold;">: Rp {{ number_format($pembelian->sisa_hutang,0,',','.') }}</td></tr>
            </table>
        </div>

        <form method="POST" action="{{ route('pembayaran.hutang.post', $pembelian->id) }}">
            @csrf
            <div class="form-group-erp">
                <label class="form-label-erp">Nominal Pembayaran (Rp) *</label>
                <input type="number" name="nominal" class="form-control-erp @error('nominal') is-invalid @enderror" 
                    value="{{ old('nominal', $pembelian->sisa_hutang) }}" min="1" max="{{ $pembelian->sisa_hutang }}" required
                    style="font-size:18px;font-weight:bold;color:#16a34a;">
                <div style="font-size:11px;color:#64748b;margin-top:4px;">*Maksimal sesuai sisa hutang</div>
                @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div style="background:#f0fdf4;border:1px solid #86efac;padding:12px;border-radius:8px;font-size:13px;color:#15803d;margin-bottom:20px;">
                💡 <strong>Info Jurnal:</strong> Saat disimpan, sistem akan otomatis mencatat jurnal: <br>
                DEBIT: Hutang Usaha <br>
                KREDIT: Kas & Bank
            </div>

            <button type="submit" class="btn-primary-erp" style="width:100%;justify-content:center;background:#dc2626;border-color:#dc2626;font-size:15px;padding:12px;">
                💸 Proses Pembayaran Hutang
            </button>
        </form>
    </div>
</div>
</div>
@endsection
