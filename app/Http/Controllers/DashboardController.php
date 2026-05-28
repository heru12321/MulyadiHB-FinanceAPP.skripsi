<?php

namespace App\Http\Controllers;

use App\Models\MPelanggan;
use App\Models\MSuplier;
use App\Models\TTransaksi;
use App\Models\TPembelian;
use App\Models\TCoaLog;
use App\Models\MCoa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;

        // Total piutang belum lunas
        $totalPiutang = TTransaksi::where('user_id', $userId)
            ->where('is_lunas', false)
            ->selectRaw('SUM(total_harga - total_dibayar) as sisa')
            ->value('sisa') ?? 0;

        // Total hutang belum lunas
        $totalHutang = TPembelian::where('user_id', $userId)
            ->where('is_lunas', false)
            ->selectRaw('SUM(total_harga - total_dibayar) as sisa')
            ->value('sisa') ?? 0;

        // Penjualan bulan ini
        $penjualanBulanIni = TTransaksi::where('user_id', $userId)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');

        // Pembelian bulan ini
        $pembelianBulanIni = TPembelian::where('user_id', $userId)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');

        // 5 transaksi terbaru (penjualan)
        $transaksiTerbaru = TTransaksi::with('pelanggan')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Data grafik arus kas 6 bulan
        $grafikData = $this->getGrafikArusKas($userId);

        return view('dashboard.index', compact(
            'totalPiutang', 'totalHutang',
            'penjualanBulanIni', 'pembelianBulanIni',
            'transaksiTerbaru', 'grafikData'
        ));
    }

    private function getGrafikArusKas(int $userId): array
    {
        $labels   = [];
        $masuk    = [];
        $keluar   = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $labels[] = $bulan->isoFormat('MMM YYYY');

            $masuk[] = TTransaksi::where('user_id', $userId)
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('total_dibayar');

            $keluar[] = TPembelian::where('user_id', $userId)
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('total_dibayar');
        }

        return compact('labels', 'masuk', 'keluar');
    }
}
