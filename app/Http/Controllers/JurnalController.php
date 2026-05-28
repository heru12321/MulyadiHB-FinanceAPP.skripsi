<?php

namespace App\Http\Controllers;

use App\Models\MJurnalUmum;
use App\Models\TCoaLog;
use App\Models\MCoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = MJurnalUmum::with('coaLogs.coa.kategori')
            ->where('user_id', $userId);

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->filled('coa_id')) {
            $query->whereHas('coaLogs', fn($q) => $q->where('coa_id', $request->coa_id));
        }

        $jurnals = $query->latest('tanggal')->paginate(20)->withQueryString();
        $coas    = MCoa::orderBy('nomor')->get();

        return view('jurnal.index', compact('jurnals', 'coas'));
    }

    public function show(int $id)
    {
        $jurnal = MJurnalUmum::with('coaLogs.coa')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('jurnal.show', compact('jurnal'));
    }
}
