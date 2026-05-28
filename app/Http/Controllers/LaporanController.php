<?php

namespace App\Http\Controllers;

use App\Models\TCoaLog;
use App\Models\MCoa;
use App\Models\MKategoriCoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /* ─────────────────────────────────────────
     |  BUKU BESAR
     ──────────────────────────────────────── */
    public function bukuBesar(Request $request)
    {
        $userId = Auth::id();
        $coas   = MCoa::orderBy('nomor')->get();
        $logs   = collect();
        $selectedCoa = null;
        $saldoAwal   = 0;

        if ($request->filled('coa_id')) {
            $selectedCoa = MCoa::findOrFail($request->coa_id);

            $query = TCoaLog::with('jurnal')
                ->where('user_id', $userId)
                ->where('coa_id', $request->coa_id)
                ->orderBy('tanggal')
                ->orderBy('id');

            if ($request->filled('dari')) {
                $query->whereDate('tanggal', '>=', $request->dari);
            }
            if ($request->filled('sampai')) {
                $query->whereDate('tanggal', '<=', $request->sampai);
            }

            $logs = $query->get();

            // Hitung saldo berjalan
            $saldo = $saldoAwal;
            $isDebitNormal = $selectedCoa->tipe_saldo === 'debit';
            $logs = $logs->map(function ($log) use (&$saldo, $isDebitNormal) {
                $debit  = (int)($log->debit  ?? 0);
                $kredit = (int)($log->kredit ?? 0);
                if ($isDebitNormal) {
                    $saldo += $debit - $kredit;
                } else {
                    $saldo += $kredit - $debit;
                }
                $log->saldo_berjalan = $saldo;
                return $log;
            });
        }

        return view('laporan.buku-besar', compact('coas', 'logs', 'selectedCoa', 'saldoAwal'));
    }

    public function bukuBesarPdf(Request $request)
    {
        [$coas, $logs, $selectedCoa, $saldoAwal] = $this->dataBukuBesar($request);
        $pdf = Pdf::loadView('laporan.pdf.buku-besar', compact('logs', 'selectedCoa', 'saldoAwal'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('buku-besar.pdf');
    }

    private function dataBukuBesar(Request $request): array
    {
        $coas = MCoa::orderBy('nomor')->get();
        $logs = collect();
        $selectedCoa = null;
        $saldoAwal   = 0;

        if ($request->filled('coa_id')) {
            $selectedCoa = MCoa::findOrFail($request->coa_id);
            $query = TCoaLog::with('jurnal')
                ->where('user_id', Auth::id())
                ->where('coa_id', $request->coa_id)
                ->orderBy('tanggal')->orderBy('id');
            if ($request->filled('dari'))   $query->whereDate('tanggal', '>=', $request->dari);
            if ($request->filled('sampai')) $query->whereDate('tanggal', '<=', $request->sampai);
            $logs = $query->get();
            $saldo = 0;
            $isDebitNormal = $selectedCoa->tipe_saldo === 'debit';
            $logs = $logs->map(function ($log) use (&$saldo, $isDebitNormal) {
                $debit  = (int)($log->debit  ?? 0);
                $kredit = (int)($log->kredit ?? 0);
                $saldo += $isDebitNormal ? ($debit - $kredit) : ($kredit - $debit);
                $log->saldo_berjalan = $saldo;
                return $log;
            });
        }
        return [$coas, $logs, $selectedCoa, $saldoAwal];
    }

    /* ─────────────────────────────────────────
     |  LABA RUGI
     ──────────────────────────────────────── */
    public function labaRugi(Request $request)
    {
        $userId  = Auth::id();
        $dari    = $request->dari    ?? now()->startOfMonth()->format('Y-m-d');
        $sampai  = $request->sampai ?? now()->format('Y-m-d');

        $data = $this->hitungLabaRugi($userId, $dari, $sampai);

        return view('laporan.laba-rugi', array_merge($data, compact('dari', 'sampai')));
    }

    public function labaRugiPdf(Request $request)
    {
        $userId = Auth::id();
        $dari   = $request->dari   ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');
        $data   = $this->hitungLabaRugi($userId, $dari, $sampai);
        $pdf    = Pdf::loadView('laporan.pdf.laba-rugi', array_merge($data, compact('dari', 'sampai')))
                     ->setPaper('a4');
        return $pdf->download('laba-rugi.pdf');
    }

    private function hitungLabaRugi(int $userId, string $dari, string $sampai): array
    {
        $getTotal = function (array $kategoriNames) use ($userId, $dari, $sampai) {
            return TCoaLog::where('user_id', $userId)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->whereHas('coa.kategori', fn($q) => $q->whereIn('nama', $kategoriNames))
                ->selectRaw('SUM(COALESCE(debit,0)) - SUM(COALESCE(kredit,0)) as total')
                ->value('total') ?? 0;
        };

        $getByKategori = function (array $kategoriNames) use ($userId, $dari, $sampai) {
            return TCoaLog::with('coa.kategori')
                ->where('user_id', $userId)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->whereHas('coa.kategori', fn($q) => $q->whereIn('nama', $kategoriNames))
                ->selectRaw('coa_id, SUM(COALESCE(debit,0)) as total_debit, SUM(COALESCE(kredit,0)) as total_kredit')
                ->groupBy('coa_id')
                ->with('coa')
                ->get();
        };

        $pendapatanItems = $getByKategori(['Pendapatan', 'Pendapatan Lainnya']);
        $hppItems        = $getByKategori(['Harga Pokok Penjualan']);
        $bebanItems      = $getByKategori(['Beban', 'Beban Lainnya']);

        $totalPendapatan = $pendapatanItems->sum(fn($i) => (int)$i->total_kredit - (int)$i->total_debit);
        $totalHPP        = $hppItems->sum(fn($i) => (int)$i->total_debit - (int)$i->total_kredit);
        $labaKotor       = $totalPendapatan - $totalHPP;
        $totalBeban      = $bebanItems->sum(fn($i) => (int)$i->total_debit - (int)$i->total_kredit);
        $labaBersih      = $labaKotor - $totalBeban;

        return compact(
            'pendapatanItems', 'hppItems', 'bebanItems',
            'totalPendapatan', 'totalHPP', 'labaKotor', 'totalBeban', 'labaBersih'
        );
    }

    /* ─────────────────────────────────────────
     |  NERACA
     ──────────────────────────────────────── */
    public function neraca(Request $request)
    {
        $userId = Auth::id();
        $sampai = $request->sampai ?? now()->format('Y-m-d');
        $data   = $this->hitungNeraca($userId, $sampai);
        return view('laporan.neraca', array_merge($data, compact('sampai')));
    }

    public function neracaPdf(Request $request)
    {
        $userId = Auth::id();
        $sampai = $request->sampai ?? now()->format('Y-m-d');
        $data   = $this->hitungNeraca($userId, $sampai);
        $pdf    = Pdf::loadView('laporan.pdf.neraca', array_merge($data, compact('sampai')))
                     ->setPaper('a4');
        return $pdf->download('neraca.pdf');
    }

    private function hitungNeraca(int $userId, string $sampai): array
    {
        $getGroup = function (array $kategoriNames) use ($userId, $sampai) {
            return TCoaLog::with('coa.kategori')
                ->where('user_id', $userId)
                ->whereDate('tanggal', '<=', $sampai)
                ->whereHas('coa.kategori', fn($q) => $q->whereIn('nama', $kategoriNames))
                ->selectRaw('coa_id, SUM(COALESCE(debit,0)) as total_debit, SUM(COALESCE(kredit,0)) as total_kredit')
                ->groupBy('coa_id')
                ->with('coa')
                ->get();
        };

        $asetKategori      = ['Kas & Bank','Akun Piutang','Persediaan','Aktiva Lancar Lainnya','Aktiva Tetap','Depresiasi & Amortisasi','Aktiva Lainnya'];
        $kewajibanKategori = ['Akun Hutang','Kewajiban Lancar Lainnya','Kewajiban Jangka Panjang'];
        $ekuitasKategori   = ['Ekuitas'];

        $asetItems      = $getGroup($asetKategori);
        $kewajibanItems = $getGroup($kewajibanKategori);
        $ekuitasItems   = $getGroup($ekuitasKategori);

        $totalAset      = $asetItems->sum(fn($i) => (int)$i->total_debit - (int)$i->total_kredit);
        $totalKewajiban = $kewajibanItems->sum(fn($i) => (int)$i->total_kredit - (int)$i->total_debit);
        $totalEkuitas   = $ekuitasItems->sum(fn($i) => (int)$i->total_kredit - (int)$i->total_debit);

        return compact(
            'asetItems', 'kewajibanItems', 'ekuitasItems',
            'totalAset', 'totalKewajiban', 'totalEkuitas'
        );
    }
}
