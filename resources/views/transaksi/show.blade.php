@extends('layouts.app')
@section('title', 'Detail Penjualan')
@section('page-title', 'Detail Penjualan')
@section('breadcrumb', 'Transaksi / Penjualan / Detail')

@section('content')
<div class="card-erp" style="margin-bottom:24px;">
    <div class="card-erp-header">
        <h2 class="card-erp-title">Invoice: {{ $transaksi->kode_inv }}</h2>
        <div>
            <a href="{{ route('transaksi.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
            <button onclick="window.print()" class="btn-primary-erp btn-sm-erp" style="background:#475569;">🖨️ Cetak</button>
        </div>
    </div>
    <div class="card-erp-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
            <div>
                <table style="width:100%;font-size:14px;border-spacing:0 8px;">
                    <tr><td style="color:#64748b;width:120px;">Tanggal</td><td>: <strong>{{ $transaksi->tanggal->format('d/m/Y') }}</strong></td></tr>
                    <tr><td style="color:#64748b;">Customer</td><td>: <strong>{{ $transaksi->pelanggan->nama ?? '-' }}</strong></td></tr>
                    <tr><td style="color:#64748b;">Status</td><td>: 
                        @if($transaksi->is_lunas)
                            <span class="badge-erp badge-success">Lunas</span>
                        @else
                            <span class="badge-erp badge-warning">Kredit</span>
                        @endif
                    </td></tr>
                </table>
            </div>
            <div>
                <table style="width:100%;font-size:14px;border-spacing:0 8px;">
                    <tr><td style="color:#64748b;width:120px;">Total Tagihan</td><td class="money" style="font-size:16px;">: <strong>Rp {{ number_format($transaksi->total_harga,0,',','.') }}</strong></td></tr>
                    <tr><td style="color:#64748b;">Total Dibayar</td><td class="money" style="color:#16a34a;">: Rp {{ number_format($transaksi->total_dibayar,0,',','.') }}</td></tr>
                    <tr><td style="color:#64748b;">Sisa Piutang</td><td class="money" style="color:#dc2626;">: Rp {{ number_format($transaksi->sisa_piutang,0,',','.') }}</td></tr>
                </table>
            </div>
        </div>

        @if($transaksi->keterangan)
        <div style="background:#f8fafc;padding:12px;border-radius:8px;font-size:13px;margin-bottom:24px;">
            <strong>Catatan:</strong> {{ $transaksi->keterangan }}
        </div>
        @endif

        <h3 style="font-size:15px;margin-bottom:12px;color:var(--brand-dark);">Detail Item</h3>
        <table class="table-erp">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $item)
                <tr>
                    <td>{{ $item->stok->nama ?? '-' }}</td>
                    <td class="money">Rp {{ number_format($item->harga_satuan,0,',','.') }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td class="money" style="font-weight:bold;">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;font-weight:bold;">Total:</td>
                    <td class="money" style="font-weight:bold;font-size:16px;">Rp {{ number_format($transaksi->total_harga,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">📔 Jurnal Akuntansi (Otomatis)</h2>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi->coaLogs as $log)
                <tr>
                    <td>{{ $log->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $log->coa->nomor }} - {{ $log->coa->nama }}</td>
                    <td>{{ $log->keterangan }}</td>
                    <td class="money" style="color:#16a34a;">{{ $log->debit ? 'Rp ' . number_format($log->debit,0,',','.') : '-' }}</td>
                    <td class="money" style="color:#dc2626;">{{ $log->kredit ? 'Rp ' . number_format($log->kredit,0,',','.') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:20px;">Tidak ada jurnal terkait.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style type="text/css" media="print">
    body * { visibility: hidden; }
    .card-erp, .card-erp * { visibility: visible; }
    .card-erp { position: absolute; left: 0; top: 0; width: 100%; box-shadow:none; border:none; }
    .btn-primary-erp, .btn-gold-erp, .btn-danger-erp, .erp-sidebar, .erp-navbar { display: none !important; }
</style>
@endsection
