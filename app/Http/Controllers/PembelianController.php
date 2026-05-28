<?php

namespace App\Http\Controllers;

use App\Models\TPembelian;
use App\Models\TPembelianDetail;
use App\Models\MSuplier;
use App\Models\MStok;
use App\Services\JurnalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    protected JurnalService $jurnalService;

    public function __construct(JurnalService $jurnalService)
    {
        $this->jurnalService = $jurnalService;
    }

    public function index()
    {
        $pembelians = TPembelian::with('suplier')
            ->where('user_id', Auth::id())
            ->latest()->paginate(15);
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $supliers = MSuplier::where('user_id', Auth::id())->get();
        $stoks    = MStok::where('user_id', Auth::id())->get();
        return view('pembelian.create', compact('supliers', 'stoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'm_suplier_id' => 'required|exists:m_supliers,id',
            'tanggal'      => 'required|date',
            'keterangan'   => 'nullable|string',
            'is_lunas'     => 'required|boolean',
            'items'        => 'required|array|min:1',
            'items.*.m_stok_id'  => 'required|exists:m_stoks,id',
            'items.*.jumlah'     => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $userId  = Auth::id();
            $isLunas = (bool) $request->is_lunas;
            $tanggal = $request->tanggal;

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $totalHarga += (int)$item['jumlah'] * (int)$item['harga_beli'];
            }

            $pembelian = TPembelian::create([
                'user_id'       => $userId,
                'kode_faktur'   => TPembelian::generateKode(),
                'is_lunas'      => $isLunas,
                'm_suplier_id'  => $request->m_suplier_id,
                'total_harga'   => $totalHarga,
                'total_dibayar' => $isLunas ? $totalHarga : 0,
                'tanggal'       => $tanggal,
                'keterangan'    => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                $stok     = MStok::findOrFail($item['m_stok_id']);
                $jumlah   = (int)$item['jumlah'];
                $hargaBeli = (int)$item['harga_beli'];
                $subtotal  = $jumlah * $hargaBeli;

                TPembelianDetail::create([
                    't_pembelian_id' => $pembelian->id,
                    'm_stok_id'      => $stok->id,
                    'harga_beli'     => $hargaBeli,
                    'jumlah'         => $jumlah,
                    'subtotal'       => $subtotal,
                ]);

                // Update stok & harga beli terakhir
                $stok->increment('jumlah_stok', $jumlah);
                $stok->update(['harga' => $hargaBeli]);
            }

            // Update hutang suplier
            if (!$isLunas) {
                $pembelian->suplier()->increment('total_hutang', $totalHarga);
            }

            // Jurnal pembelian
            if ($isLunas) {
                $entries = [
                    ['coa_nomor' => '1-10200', 'debit' => $totalHarga, 'kredit' => null,        'keterangan' => 'Persediaan masuk ' . $pembelian->kode_faktur],
                    ['coa_nomor' => '1-10001', 'debit' => null,        'kredit' => $totalHarga,  'keterangan' => 'Pembayaran tunai ' . $pembelian->kode_faktur],
                ];
            } else {
                $entries = [
                    ['coa_nomor' => '1-10200', 'debit' => $totalHarga, 'kredit' => null,        'keterangan' => 'Persediaan masuk ' . $pembelian->kode_faktur],
                    ['coa_nomor' => '2-20100', 'debit' => null,        'kredit' => $totalHarga,  'keterangan' => 'Hutang pembelian ' . $pembelian->kode_faktur],
                ];
            }

            $this->jurnalService->buatJurnal(
                $userId,
                'Pembelian ' . $pembelian->kode_faktur,
                $tanggal,
                $entries,
                $pembelian->id,
                null
            );
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dicatat beserta jurnal otomatis.');
    }

    public function show(TPembelian $pembelian)
    {
        $this->cekAkses($pembelian);
        $pembelian->load('details.stok', 'suplier', 'coaLogs.coa');
        return view('pembelian.show', compact('pembelian'));
    }

    public function edit(TPembelian $pembelian) { abort(403, 'Pembelian tidak dapat diedit.'); }
    public function update(Request $request, TPembelian $pembelian) { abort(403); }

    public function destroy(TPembelian $pembelian)
    {
        $this->cekAkses($pembelian);
        $pembelian->delete();
        return redirect()->route('pembelian.index')->with('success', 'Pembelian dihapus.');
    }

    private function cekAkses(TPembelian $p): void
    {
        if ($p->user_id !== Auth::id()) abort(403);
    }
}
