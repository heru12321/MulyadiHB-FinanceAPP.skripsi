<?php

namespace App\Http\Controllers;

use App\Models\TPembelian;
use App\Models\TTransaksi;
use App\Services\JurnalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    protected JurnalService $jurnalService;

    public function __construct(JurnalService $jurnalService)
    {
        $this->jurnalService = $jurnalService;
    }

    public function index()
    {
        $userId = Auth::id();

        $hutangs = TPembelian::with('suplier')
            ->where('user_id', $userId)
            ->where('is_lunas', false)
            ->latest()->paginate(10, ['*'], 'hutang_page');

        $piutangs = TTransaksi::with('pelanggan')
            ->where('user_id', $userId)
            ->where('is_lunas', false)
            ->latest()->paginate(10, ['*'], 'piutang_page');

        return view('pembayaran.index', compact('hutangs', 'piutangs'));
    }

    /* ─── HUTANG ──────────────────────────── */

    public function formHutang(int $id)
    {
        $pembelian = TPembelian::with('suplier')->where('user_id', Auth::id())->findOrFail($id);
        return view('pembayaran.hutang', compact('pembelian'));
    }

    public function bayarHutang(Request $request, int $id)
    {
        $pembelian = TPembelian::where('user_id', Auth::id())->findOrFail($id);

        $sisa = $pembelian->total_harga - $pembelian->total_dibayar;
        $request->validate([
            'nominal' => "required|integer|min:1|max:{$sisa}",
        ], ['nominal.max' => "Nominal melebihi sisa hutang (Rp " . number_format($sisa, 0, ',', '.') . ")"]);

        DB::transaction(function () use ($request, $pembelian) {
            $nominal = (int) $request->nominal;
            $pembelian->increment('total_dibayar', $nominal);

            if ($pembelian->total_dibayar >= $pembelian->total_harga) {
                $pembelian->update(['is_lunas' => true]);
            }

            // Update hutang suplier
            $pembelian->suplier()->increment('hutang_dibayar', $nominal);

            // Jurnal pembayaran hutang
            $this->jurnalService->buatJurnal(
                Auth::id(),
                'Pembayaran hutang ' . $pembelian->kode_faktur,
                now()->format('Y-m-d'),
                [
                    ['coa_nomor' => '2-20100', 'debit' => $nominal, 'kredit' => null,    'keterangan' => 'Bayar hutang ' . $pembelian->kode_faktur],
                    ['coa_nomor' => '1-10001', 'debit' => null,     'kredit' => $nominal, 'keterangan' => 'Kas keluar bayar hutang ' . $pembelian->kode_faktur],
                ],
                $pembelian->id,
                null
            );
        });

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran hutang berhasil dicatat.');
    }

    /* ─── PIUTANG ──────────────────────────── */

    public function formPiutang(int $id)
    {
        $transaksi = TTransaksi::with('pelanggan')->where('user_id', Auth::id())->findOrFail($id);
        return view('pembayaran.piutang', compact('transaksi'));
    }

    public function bayarPiutang(Request $request, int $id)
    {
        $transaksi = TTransaksi::where('user_id', Auth::id())->findOrFail($id);

        $sisa = $transaksi->total_harga - $transaksi->total_dibayar;
        $request->validate([
            'nominal' => "required|integer|min:1|max:{$sisa}",
        ], ['nominal.max' => "Nominal melebihi sisa piutang (Rp " . number_format($sisa, 0, ',', '.') . ")"]);

        DB::transaction(function () use ($request, $transaksi) {
            $nominal = (int) $request->nominal;
            $transaksi->increment('total_dibayar', $nominal);

            if ($transaksi->total_dibayar >= $transaksi->total_harga) {
                $transaksi->update(['is_lunas' => true]);
            }

            // Update piutang pelanggan
            $transaksi->pelanggan()->increment('piutang_dibayar', $nominal);

            // Jurnal penerimaan piutang
            $this->jurnalService->buatJurnal(
                Auth::id(),
                'Penerimaan piutang ' . $transaksi->kode_inv,
                now()->format('Y-m-d'),
                [
                    ['coa_nomor' => '1-10001', 'debit' => $nominal, 'kredit' => null,    'keterangan' => 'Kas masuk piutang ' . $transaksi->kode_inv],
                    ['coa_nomor' => '1-10100', 'debit' => null,     'kredit' => $nominal, 'keterangan' => 'Pelunasan piutang ' . $transaksi->kode_inv],
                ],
                null,
                $transaksi->id
            );
        });

        return redirect()->route('pembayaran.index')
            ->with('success', 'Penerimaan piutang berhasil dicatat.');
    }
}
