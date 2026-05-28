<?php

namespace App\Http\Controllers;

use App\Models\MPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = MPelanggan::where('user_id', Auth::id())->latest()->paginate(15);
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
        ]);

        MPelanggan::create([
            'user_id' => Auth::id(),
            'nama'    => $request->nama,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(MPelanggan $pelanggan)
    {
        $this->cekAkses($pelanggan);
        $transaksis = $pelanggan->transaksis()->latest()->paginate(10);
        return view('pelanggan.show', compact('pelanggan', 'transaksis'));
    }

    public function edit(MPelanggan $pelanggan)
    {
        $this->cekAkses($pelanggan);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, MPelanggan $pelanggan)
    {
        $this->cekAkses($pelanggan);
        $request->validate([
            'nama'    => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
        ]);
        $pelanggan->update($request->only('nama', 'no_telp'));
        return redirect()->route('pelanggan.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroy(MPelanggan $pelanggan)
    {
        $this->cekAkses($pelanggan);
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')
            ->with('success', 'Customer berhasil dihapus.');
    }

    private function cekAkses(MPelanggan $pelanggan): void
    {
        if ($pelanggan->user_id !== Auth::id()) abort(403);
    }
}
