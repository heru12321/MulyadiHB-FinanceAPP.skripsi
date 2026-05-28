<?php

namespace App\Http\Controllers;

use App\Models\MStok;
use App\Models\MSuplier;
use App\Models\TPembelian;
use App\Models\TPembelianDetail;
use App\Services\JurnalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    protected JurnalService $jurnalService;

    public function __construct(JurnalService $jurnalService)
    {
        $this->jurnalService = $jurnalService;
    }

    public function index()
    {
        $stoks = MStok::where('user_id', Auth::id())
            ->latest()->paginate(15);
        return view('stok.index', compact('stoks'));
    }

    public function create()
    {
        $supliers = MSuplier::where('user_id', Auth::id())->get();
        return view('stok.create', compact('supliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'sku'            => 'required|string|unique:m_stoks,sku',
            'deskripsi'      => 'nullable|string',
            'harga'          => 'required|integer|min:0',
            'jumlah_stok'    => 'required|integer|min:0',
            'tipe_pembelian' => 'required|in:kredit,tunai',
            'm_suplier_id'   => 'required|exists:m_supliers,id',
            'harga_beli'     => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $userId   = Auth::id();
            $jumlah   = (int) $request->jumlah_stok;
            $hargaBeli = (int) $request->harga_beli;
            $subtotal  = $jumlah * $hargaBeli;
            $tanggal   = now()->format('Y-m-d');
            $isLunas   = $request->tipe_pembelian === 'tunai';

            // Buat stok
            $stok = MStok::create([
                'user_id'     => $userId,
                'nama'        => $request->nama,
                'sku'         => $request->sku,
                'deskripsi'   => $request->deskripsi,
                'harga'       => $request->harga,
                'jumlah_stok' => $jumlah,
            ]);

            // Buat pembelian otomatis
            $pembelian = TPembelian::create([
                'user_id'      => $userId,
                'kode_faktur'  => TPembelian::generateKode(),
                'is_lunas'     => $isLunas,
                'm_suplier_id' => $request->m_suplier_id,
                'total_harga'  => $subtotal,
                'total_dibayar'=> $isLunas ? $subtotal : 0,
                'tanggal'      => $tanggal,
                'keterangan'   => 'Pembelian stok awal: ' . $stok->nama,
            ]);

            TPembelianDetail::create([
                't_pembelian_id' => $pembelian->id,
                'm_stok_id'      => $stok->id,
                'harga_beli'     => $hargaBeli,
                'jumlah'         => $jumlah,
                'subtotal'       => $subtotal,
            ]);

            // Update hutang suplier jika kredit
            if (!$isLunas) {
                $pembelian->suplier()->increment('total_hutang', $subtotal);
            }

            // Jurnal otomatis
            if ($isLunas) {
                // DEBIT Persediaan, KREDIT Kas/Bank
                $entries = [
                    ['coa_nomor' => '1-10200', 'debit'  => $subtotal, 'kredit' => null,     'keterangan' => 'Pembelian stok: ' . $stok->nama],
                    ['coa_nomor' => '1-10001', 'debit'  => null,      'kredit' => $subtotal, 'keterangan' => 'Pembayaran tunai stok: ' . $stok->nama],
                ];
            } else {
                // DEBIT Persediaan, KREDIT Hutang Usaha
                $entries = [
                    ['coa_nomor' => '1-10200', 'debit'  => $subtotal, 'kredit' => null,     'keterangan' => 'Pembelian stok: ' . $stok->nama],
                    ['coa_nomor' => '2-20100', 'debit'  => null,      'kredit' => $subtotal, 'keterangan' => 'Hutang pembelian stok: ' . $stok->nama],
                ];
            }

            $this->jurnalService->buatJurnal(
                $userId,
                'Pembelian stok awal: ' . $stok->nama,
                $tanggal,
                $entries,
                $pembelian->id,
                null
            );
        });

        return redirect()->route('stok.index')
            ->with('success', 'Stok berhasil ditambahkan beserta jurnal otomatis.');
    }

    public function show(MStok $stok)
    {
        $this->authorize_user($stok);
        return view('stok.show', compact('stok'));
    }

    public function edit(MStok $stok)
    {
        $this->authorize_user($stok);
        return view('stok.edit', compact('stok'));
    }

    public function update(Request $request, MStok $stok)
    {
        $this->authorize_user($stok);

        $request->validate([
            'nama'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:m_stoks,sku,' . $stok->id,
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|integer|min:0',
        ]);

        $stok->update($request->only('nama', 'sku', 'deskripsi', 'harga'));

        return redirect()->route('stok.index')
            ->with('success', 'Data stok berhasil diperbarui.');
    }

    public function destroy(MStok $stok)
    {
        $this->authorize_user($stok);
        $stok->delete();
        return redirect()->route('stok.index')
            ->with('success', 'Stok berhasil dihapus.');
    }

    private function authorize_user(MStok $stok): void
    {
        if ($stok->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
