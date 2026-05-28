<?php

namespace App\Http\Controllers;

use App\Models\MSuplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuplierController extends Controller
{
    public function index()
    {
        $supliers = MSuplier::where('user_id', Auth::id())->latest()->paginate(15);
        return view('suplier.index', compact('supliers'));
    }

    public function create()
    {
        return view('suplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'no_telp'=> 'nullable|string|max:20',
        ]);

        MSuplier::create([
            'user_id' => Auth::id(),
            'nama'    => $request->nama,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('suplier.index')
            ->with('success', 'Suplier berhasil ditambahkan.');
    }

    public function show(MSuplier $suplier)
    {
        $this->cekAkses($suplier);
        $pembelians = $suplier->pembelians()->latest()->paginate(10);
        return view('suplier.show', compact('suplier', 'pembelians'));
    }

    public function edit(MSuplier $suplier)
    {
        $this->cekAkses($suplier);
        return view('suplier.edit', compact('suplier'));
    }

    public function update(Request $request, MSuplier $suplier)
    {
        $this->cekAkses($suplier);
        $request->validate([
            'nama'    => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
        ]);
        $suplier->update($request->only('nama', 'no_telp'));
        return redirect()->route('suplier.index')
            ->with('success', 'Data suplier berhasil diperbarui.');
    }

    public function destroy(MSuplier $suplier)
    {
        $this->cekAkses($suplier);
        $suplier->delete();
        return redirect()->route('suplier.index')
            ->with('success', 'Suplier berhasil dihapus.');
    }

    private function cekAkses(MSuplier $suplier): void
    {
        if ($suplier->user_id !== Auth::id()) abort(403);
    }
}
