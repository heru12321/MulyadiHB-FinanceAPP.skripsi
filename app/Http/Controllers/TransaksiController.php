<?php

namespace App\Http\Controllers;

use App\Models\TTransaksi;
use App\Models\TTransaksiDetail;
use App\Models\MPelanggan;
use App\Models\MStok;
use App\Services\JurnalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    protected JurnalService $jurnalService;

    public function __construct(JurnalService $jurnalService)
    {
        $this->jurnalService = $jurnalService;
    }

    public function index()
    {
        $transaksis = TTransaksi::with('pelanggan')
            ->where('user_id', Auth::id())
            ->latest()->paginate(15);
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $pelanggans = MPelanggan::where('user_id', Auth::id())->get();
        $stoks      = MStok::where('user_id', Auth::id())->where('jumlah_stok', '>', 0)->get();
        return view('transaksi.create', compact('pelanggans', 'stoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'm_pelanggan_id' => 'required|exists:m_pelanggans,id',
            'tanggal'        => 'required|date',
            'keterangan'     => 'nullable|string',
            'is_lunas'       => 'required|boolean',
            'items'          => 'required|array|min:1',
            'items.*.m_stok_id'    => 'required|exists:m_stoks,id',
            'items.*.jumlah'       => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $userId  = Auth::id();
            $isLunas = (bool) $request->is_lunas;
            $tanggal = $request->tanggal;

            // Hitung total
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $totalHarga += (int)$item['jumlah'] * (int)$item['harga_satuan'];
            }

            // Validasi stok cukup
            foreach ($request->items as $item) {
                $stok = MStok::findOrFail($item['m_stok_id']);
                if ($stok->jumlah_stok < (int)$item['jumlah']) {
                    throw new \Exception("Stok {$stok->nama} tidak mencukupi. Tersisa: {$stok->jumlah_stok}");
                }
            }

            // Buat transaksi
            $trx = TTransaksi::create([
                'user_id'        => $userId,
                'kode_inv'       => TTransaksi::generateKode(),
                'is_lunas'       => $isLunas,
                'm_pelanggan_id' => $request->m_pelanggan_id,
                'total_harga'    => $totalHarga,
                'total_dibayar'  => $isLunas ? $totalHarga : 0,
                'tanggal'        => $tanggal,
                'keterangan'     => $request->keterangan,
            ]);

            // Detail + update stok
            $totalHPP = 0;
            foreach ($request->items as $item) {
                $stok      = MStok::findOrFail($item['m_stok_id']);
                $subtotal  = (int)$item['jumlah'] * (int)$item['harga_satuan'];
                $hppItem   = $stok->harga * (int)$item['jumlah']; // HPP = harga beli * qty

                TTransaksiDetail::create([
                    't_transaksi_id' => $trx->id,
                    'm_stok_id'      => $stok->id,
                    'harga_satuan'   => $item['harga_satuan'],
                    'jumlah'         => $item['jumlah'],
                    'subtotal'       => $subtotal,
                ]);

                $stok->decrement('jumlah_stok', (int)$item['jumlah']);
                $totalHPP += $hppItem;
            }

            // Update piutang pelanggan jika kredit
            if (!$isLunas) {
                $trx->pelanggan()->increment('total_piutang', $totalHarga);
            }

            // Jurnal penjualan
            if ($isLunas) {
                $entries = [
                    ['coa_nomor' => '1-10001', 'debit' => $totalHarga, 'kredit' => null,        'keterangan' => 'Penerimaan kas penjualan ' . $trx->kode_inv],
                    ['coa_nomor' => '4-40000', 'debit' => null,        'kredit' => $totalHarga,  'keterangan' => 'Pendapatan penjualan ' . $trx->kode_inv],
                ];
            } else {
                $entries = [
                    ['coa_nomor' => '1-10100', 'debit' => $totalHarga, 'kredit' => null,        'keterangan' => 'Piutang penjualan ' . $trx->kode_inv],
                    ['coa_nomor' => '4-40000', 'debit' => null,        'kredit' => $totalHarga,  'keterangan' => 'Pendapatan penjualan ' . $trx->kode_inv],
                ];
            }

            // Jurnal HPP
            $entries[] = ['coa_nomor' => '5-50000', 'debit' => $totalHPP, 'kredit' => null,      'keterangan' => 'HPP penjualan ' . $trx->kode_inv];
            $entries[] = ['coa_nomor' => '1-10200', 'debit' => null,      'kredit' => $totalHPP,  'keterangan' => 'Keluar persediaan ' . $trx->kode_inv];

            $this->jurnalService->buatJurnal(
                $userId,
                'Penjualan ' . $trx->kode_inv,
                $tanggal,
                $entries,
                null,
                $trx->id
            );
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Penjualan berhasil dicatat beserta jurnal otomatis.');
    }

    public function show(TTransaksi $transaksi)
    {
        $this->cekAkses($transaksi);
        $transaksi->load('details.stok', 'pelanggan', 'coaLogs.coa');
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(TTransaksi $transaksi) { abort(403, 'Transaksi tidak dapat diedit.'); }
    public function update(Request $request, TTransaksi $transaksi) { abort(403); }

    public function destroy(TTransaksi $transaksi)
    {
        $this->cekAkses($transaksi);
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi dihapus.');
    }

    private function cekAkses(TTransaksi $trx): void
    {
        if ($trx->user_id !== Auth::id()) abort(403);
    }
}
