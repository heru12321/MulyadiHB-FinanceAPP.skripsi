@extends('layouts.app')
@section('title', 'Penjualan')
@section('page-title', 'Penjualan')
@section('breadcrumb', 'Transaksi / Penjualan')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">🧾 Daftar Penjualan</h2>
        <a href="{{ route('transaksi.create') }}" class="btn-gold-erp">+ Catat Penjualan Baru</a>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Terbayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $trx)
                <tr>
                    <td><strong>{{ $trx->kode_inv }}</strong></td>
                    <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
                    <td class="money">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td class="money" style="color:#16a34a;">Rp {{ number_format($trx->total_dibayar, 0, ',', '.') }}</td>
                    <td>
                        @if($trx->is_lunas)
                            <span class="badge-erp badge-success">Lunas</span>
                        @else
                            <span class="badge-erp badge-warning">Kredit (Sisa: Rp {{ number_format($trx->sisa_piutang, 0, ',', '.') }})</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('transaksi.show', $trx->id) }}" class="btn-primary-erp btn-sm-erp">Detail</a>
                        <form method="POST" action="{{ route('transaksi.destroy', $trx->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger-erp btn-sm-erp" data-confirm="Hapus transaksi {{ $trx->kode_inv }}?">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">Belum ada data penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksis->hasPages())
    <div style="padding:16px 22px;">{{ $transaksis->links() }}</div>
    @endif
</div>
@endsection
