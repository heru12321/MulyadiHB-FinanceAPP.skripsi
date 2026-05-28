@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Ringkasan Keuangan Perusahaan')

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px;">

    {{-- Piutang --}}
    <div class="stat-card">
        <div class="stat-icon blue">📥</div>
        <div class="stat-body">
            <div class="label">Total Piutang</div>
            <div class="value" style="font-size:18px;color:#1d4ed8;">
                Rp {{ number_format($totalPiutang, 0, ',', '.') }}
            </div>
            <div class="sub">Belum tertagih</div>
        </div>
    </div>

    {{-- Hutang --}}
    <div class="stat-card">
        <div class="stat-icon red">📤</div>
        <div class="stat-body">
            <div class="label">Total Hutang</div>
            <div class="value" style="font-size:18px;color:#dc2626;">
                Rp {{ number_format($totalHutang, 0, ',', '.') }}
            </div>
            <div class="sub">Belum dibayar</div>
        </div>
    </div>

    {{-- Penjualan bulan ini --}}
    <div class="stat-card">
        <div class="stat-icon green">📈</div>
        <div class="stat-body">
            <div class="label">Penjualan Bulan Ini</div>
            <div class="value" style="font-size:18px;color:#16a34a;">
                Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}
            </div>
            <div class="sub">{{ now()->isoFormat('MMMM YYYY') }}</div>
        </div>
    </div>

    {{-- Pembelian bulan ini --}}
    <div class="stat-card">
        <div class="stat-icon gold">🛒</div>
        <div class="stat-body">
            <div class="label">Pembelian Bulan Ini</div>
            <div class="value" style="font-size:18px;color:#a16207;">
                Rp {{ number_format($pembelianBulanIni, 0, ',', '.') }}
            </div>
            <div class="sub">{{ now()->isoFormat('MMMM YYYY') }}</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:18px;">
    {{-- Grafik arus kas --}}
    <div class="card-erp">
        <div class="card-erp-header">
            <h2 class="card-erp-title">📊 Grafik Arus Kas — 6 Bulan Terakhir</h2>
        </div>
        <div class="card-erp-body">
            <canvas id="arusKasChart" height="280"></canvas>
        </div>
    </div>

    {{-- 5 Transaksi terbaru --}}
    <div class="card-erp">
        <div class="card-erp-header">
            <h2 class="card-erp-title">🧾 Transaksi Terbaru</h2>
            <a href="{{ route('transaksi.index') }}" class="btn-primary-erp btn-sm-erp">Lihat Semua</a>
        </div>
        <div class="card-erp-body" style="padding:0;">
            <table class="table-erp">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $trx)
                    <tr>
                        <td>
                            <a href="{{ route('transaksi.show', $trx->id) }}"
                               style="color:var(--brand-dark);font-weight:600;font-size:12px;">
                                {{ $trx->kode_inv }}
                            </a>
                            <div style="font-size:11px;color:#94a3b8;">{{ $trx->tanggal->format('d/m/Y') }}</div>
                        </td>
                        <td style="font-size:13px;">{{ $trx->pelanggan->nama ?? '-' }}</td>
                        <td style="font-size:12px;font-weight:700;">
                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($trx->is_lunas)
                                <span class="badge-erp badge-success">Lunas</span>
                            @else
                                <span class="badge-erp badge-warning">Kredit</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:#94a3b8;padding:28px;">
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('arusKasChart').getContext('2d');
const labels = @json($grafikData['labels']);
const dataMasuk  = @json($grafikData['masuk']);
const dataKeluar = @json($grafikData['keluar']);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Kas Masuk (Penjualan)',
                data: dataMasuk,
                backgroundColor: 'rgba(22,163,74,0.15)',
                borderColor: '#16a34a',
                borderWidth: 2,
                borderRadius: 6,
                type: 'bar',
            },
            {
                label: 'Kas Keluar (Pembelian)',
                data: dataKeluar,
                backgroundColor: 'rgba(220,38,38,0.1)',
                borderColor: '#dc2626',
                borderWidth: 2,
                borderRadius: 6,
                type: 'bar',
            },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 12 }, usePointStyle: true, padding: 16 }
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        return ' ' + ctx.dataset.label + ': Rp ' +
                            parseInt(ctx.raw).toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(v) {
                        if (v >= 1000000) return 'Rp ' + (v/1000000).toFixed(0) + 'jt';
                        return 'Rp ' + v.toLocaleString('id-ID');
                    },
                    font: { size: 11 }
                },
                grid: { color: '#f0f4f8' }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
