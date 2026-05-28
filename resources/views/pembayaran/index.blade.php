@extends('layouts.app')
@section('title', 'Pembayaran Hutang & Piutang')
@section('page-title', 'Pembayaran Hutang & Piutang')
@section('breadcrumb', 'Transaksi / Pembayaran')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

    {{-- Kolom Piutang --}}
    <div class="card-erp" style="border-top:3px solid #1d4ed8;">
        <div class="card-erp-header" style="background:#f8fafc;">
            <h2 class="card-erp-title" style="color:#1d4ed8;">📥 Daftar Piutang (Customer)</h2>
        </div>
        <div class="card-erp-body" style="padding:0;">
            <table class="table-erp">
                <thead>
                    <tr>
                        <th>Customer / Inv</th>
                        <th>Sisa Piutang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($piutangs as $p)
                    <tr>
                        <td>
                            <strong>{{ $p->pelanggan->nama ?? '-' }}</strong>
                            <div style="font-size:11px;color:#64748b;">{{ $p->kode_inv }} ({{ $p->tanggal->format('d/m/Y') }})</div>
                        </td>
                        <td class="money" style="color:#dc2626;font-weight:bold;">
                            Rp {{ number_format($p->sisa_piutang, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('pembayaran.piutang.form', $p->id) }}" class="btn-primary-erp btn-sm-erp">Terima Pembayaran</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:#94a3b8;">Tidak ada piutang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($piutangs->hasPages())
        <div style="padding:16px 22px;">{{ $piutangs->appends(['hutang_page' => request('hutang_page')])->links() }}</div>
        @endif
    </div>

    {{-- Kolom Hutang --}}
    <div class="card-erp" style="border-top:3px solid #dc2626;">
        <div class="card-erp-header" style="background:#f8fafc;">
            <h2 class="card-erp-title" style="color:#dc2626;">📤 Daftar Hutang (Suplier)</h2>
        </div>
        <div class="card-erp-body" style="padding:0;">
            <table class="table-erp">
                <thead>
                    <tr>
                        <th>Suplier / Faktur</th>
                        <th>Sisa Hutang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hutangs as $h)
                    <tr>
                        <td>
                            <strong>{{ $h->suplier->nama ?? '-' }}</strong>
                            <div style="font-size:11px;color:#64748b;">{{ $h->kode_faktur }} ({{ $h->tanggal->format('d/m/Y') }})</div>
                        </td>
                        <td class="money" style="color:#dc2626;font-weight:bold;">
                            Rp {{ number_format($h->sisa_hutang, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('pembayaran.hutang.form', $h->id) }}" class="btn-primary-erp btn-sm-erp" style="background:#dc2626;">Bayar Hutang</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:#94a3b8;">Tidak ada hutang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($hutangs->hasPages())
        <div style="padding:16px 22px;">{{ $hutangs->appends(['piutang_page' => request('piutang_page')])->links() }}</div>
        @endif
    </div>

</div>
@endsection
