@extends('layouts.app')
@section('title', 'Terima Piutang')
@section('page-title', 'Form Penerimaan Piutang')
@section('breadcrumb', 'Transaksi / Pembayaran / Piutang')

@section('content')
<div style="max-width:600px;">
<div class="card-erp" style="border-top:3px solid #1d4ed8;">
    <div class="card-erp-header">
        <h2 class="card-erp-title" style="color:#1d4ed8;">📥 Terima Piutang - {{ $transaksi->kode_inv }}</h2>
        <a href="{{ route('pembayaran.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        
        <div style="background:#f8fafc;padding:16px;border-radius:10px;margin-bottom:20px;">
            <table style="width:100%;font-size:14px;border-spacing:0 6px;">
                <tr><td style="color:#64748b;">Customer</td><td>: <strong>{{ $transaksi->pelanggan->nama ?? '-' }}</strong></td></tr>
                <tr><td style="color:#64748b;">Tanggal Invoice</td><td>: {{ $transaksi->tanggal->format('d/m/Y') }}</td></tr>
                <tr><td style="color:#64748b;">Total Tagihan</td><td class="money">: Rp {{ number_format($transaksi->total_harga,0,',','.') }}</td></tr>
                <tr><td style="color:#64748b;">Sudah Dibayar</td><td class="money" style="color:#16a34a;">: Rp {{ number_format($transaksi->total_dibayar,0,',','.') }}</td></tr>
                <tr><td style="color:#64748b;font-weight:bold;">Sisa Piutang</td><td class="money" style="color:#dc2626;font-size:16px;font-weight:bold;">: Rp {{ number_format($transaksi->sisa_piutang,0,',','.') }}</td></tr>
            </table>
        </div>

        <form method="POST" action="{{ route('pembayaran.piutang.post', $transaksi->id) }}">
            @csrf
            <div class="form-group-erp">
                <label class="form-label-erp">Nominal Penerimaan (Rp) *</label>
                <input type="number" name="nominal" class="form-control-erp @error('nominal') is-invalid @enderror" 
                    value="{{ old('nominal', $transaksi->sisa_piutang) }}" min="1" max="{{ $transaksi->sisa_piutang }}" required
                    style="font-size:18px;font-weight:bold;color:#16a34a;">
                <div style="font-size:11px;color:#64748b;margin-top:4px;">*Maksimal sesuai sisa piutang</div>
                @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div style="background:#f0fdf4;border:1px solid #86efac;padding:12px;border-radius:8px;font-size:13px;color:#15803d;margin-bottom:20px;">
                💡 <strong>Info Jurnal:</strong> Saat disimpan, sistem akan otomatis mencatat jurnal: <br>
                DEBIT: Kas & Bank <br>
                KREDIT: Piutang Usaha
            </div>

            <button type="submit" class="btn-primary-erp" style="width:100%;justify-content:center;background:#1d4ed8;border-color:#1d4ed8;font-size:15px;padding:12px;">
                💵 Proses Penerimaan Piutang
            </button>
        </form>
    </div>
</div>
</div>
@endsection
