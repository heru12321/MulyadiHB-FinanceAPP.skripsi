@extends('layouts.app')
@section('title', 'Pembelian')
@section('page-title', 'Pembelian')
@section('breadcrumb', 'Transaksi / Pembelian')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">🛒 Daftar Pembelian</h2>
        <a href="{{ route('pembelian.create') }}" class="btn-gold-erp">+ Catat Pembelian Baru</a>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr>
                    <th>Faktur</th>
                    <th>Tanggal</th>
                    <th>Suplier</th>
                    <th>Total</th>
                    <th>Terbayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelians as $pb)
                <tr>
                    <td><strong>{{ $pb->kode_faktur }}</strong></td>
                    <td>{{ $pb->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $pb->suplier->nama ?? '-' }}</td>
                    <td class="money">Rp {{ number_format($pb->total_harga, 0, ',', '.') }}</td>
                    <td class="money" style="color:#16a34a;">Rp {{ number_format($pb->total_dibayar, 0, ',', '.') }}</td>
                    <td>
                        @if($pb->is_lunas)
                            <span class="badge-erp badge-success">Lunas</span>
                        @else
                            <span class="badge-erp badge-warning">Hutang (Sisa: Rp {{ number_format($pb->sisa_hutang, 0, ',', '.') }})</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pembelian.show', $pb->id) }}" class="btn-primary-erp btn-sm-erp">Detail</a>
                        <form method="POST" action="{{ route('pembelian.destroy', $pb->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger-erp btn-sm-erp" data-confirm="Hapus pembelian {{ $pb->kode_faktur }}?">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">Belum ada data pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pembelians->hasPages())
    <div style="padding:16px 22px;">{{ $pembelians->links() }}</div>
    @endif
</div>
@endsection
