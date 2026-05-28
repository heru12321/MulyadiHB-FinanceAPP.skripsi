@extends('layouts.app')
@section('title', 'Neraca')
@section('page-title', 'Laporan Neraca')
@section('breadcrumb', 'Laporan / Neraca')

@section('content')
<div class="card-erp" style="margin-bottom:20px;">
    <div class="card-erp-body">
        <form method="GET" action="{{ route('laporan.neraca') }}" style="display:flex;gap:16px;align-items:flex-end;">
            <div style="flex:1;">
                <label class="form-label-erp">Per Tanggal</label>
                <input type="date" name="sampai" class="form-control-erp" value="{{ $sampai }}" required>
            </div>
            <div>
                <button type="submit" class="btn-primary-erp" style="padding:10px 20px;">Tampilkan</button>
                <a href="{{ route('laporan.neraca.pdf', request()->all()) }}" target="_blank" class="btn-danger-erp" style="padding:10px 16px;background:#dc2626;color:#fff;">PDF</a>
            </div>
        </form>
    </div>
</div>

<div class="card-erp" style="max-width:1000px;margin:0 auto;">
    <div class="card-erp-header" style="flex-direction:column;align-items:center;padding:24px;">
        <h2 style="margin:0 0 8px;font-size:20px;font-weight:800;color:var(--brand-dark);">LAPORAN NERACA</h2>
        <div style="font-size:16px;font-weight:600;">{{ Auth::user()->nama_perusahaan }}</div>
        <div style="font-size:13px;color:#64748b;margin-top:4px;">Per Tanggal: {{ date('d/m/Y', strtotime($sampai)) }}</div>
    </div>
    
    <div class="card-erp-body" style="padding:32px;">
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;">
            
            {{-- BAGIAN KIRI: ASET --}}
            <div>
                <h3 style="font-size:16px;color:var(--brand-dark);border-bottom:2px solid var(--brand-dark);padding-bottom:8px;margin-bottom:16px;">AKTIVA (ASET)</h3>
                
                <table style="width:100%;font-size:14px;margin-bottom:24px;">
                    @foreach($asetItems as $item)
                    @php $saldo = $item->total_debit - $item->total_kredit; @endphp
                    @if($saldo != 0)
                    <tr>
                        <td style="padding:6px 0;">{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                        <td class="money" style="text-align:right;">Rp {{ number_format($saldo,0,',','.') }}</td>
                    </tr>
                    @endif
                    @endforeach
                </table>
            </div>

            {{-- BAGIAN KANAN: KEWAJIBAN & EKUITAS --}}
            <div>
                <h3 style="font-size:16px;color:var(--brand-dark);border-bottom:2px solid var(--brand-dark);padding-bottom:8px;margin-bottom:16px;">PASIVA (KEWAJIBAN & EKUITAS)</h3>
                
                <h4 style="font-size:14px;color:#dc2626;margin:0 0 8px;">Kewajiban</h4>
                <table style="width:100%;font-size:14px;margin-bottom:24px;">
                    @foreach($kewajibanItems as $item)
                    @php $saldo = $item->total_kredit - $item->total_debit; @endphp
                    @if($saldo != 0)
                    <tr>
                        <td style="padding:6px 0;">{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                        <td class="money" style="text-align:right;">Rp {{ number_format($saldo,0,',','.') }}</td>
                    </tr>
                    @endif
                    @endforeach
                    <tr>
                        <td style="padding:8px 0;font-weight:bold;">Total Kewajiban</td>
                        <td class="money" style="text-align:right;font-weight:bold;border-top:1px solid #e2e8f0;">
                            Rp {{ number_format($totalKewajiban,0,',','.') }}
                        </td>
                    </tr>
                </table>

                <h4 style="font-size:14px;color:#1d4ed8;margin:0 0 8px;">Ekuitas</h4>
                <table style="width:100%;font-size:14px;margin-bottom:24px;">
                    @foreach($ekuitasItems as $item)
                    @php $saldo = $item->total_kredit - $item->total_debit; @endphp
                    @if($saldo != 0)
                    <tr>
                        <td style="padding:6px 0;">{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                        <td class="money" style="text-align:right;">Rp {{ number_format($saldo,0,',','.') }}</td>
                    </tr>
                    @endif
                    @endforeach
                    <tr>
                        <td style="padding:8px 0;font-weight:bold;">Total Ekuitas</td>
                        <td class="money" style="text-align:right;font-weight:bold;border-top:1px solid #e2e8f0;">
                            Rp {{ number_format($totalEkuitas,0,',','.') }}
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:20px;border-top:3px solid var(--brand-dark);padding-top:16px;">
            <div style="display:flex;justify-content:space-between;">
                <strong style="font-size:16px;">TOTAL AKTIVA</strong>
                <strong class="money" style="font-size:18px;">Rp {{ number_format($totalAset,0,',','.') }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <strong style="font-size:16px;">TOTAL PASIVA</strong>
                <strong class="money" style="font-size:18px;">Rp {{ number_format($totalKewajiban + $totalEkuitas,0,',','.') }}</strong>
            </div>
        </div>

        @if($totalAset !== ($totalKewajiban + $totalEkuitas))
            <div class="alert-erp alert-danger" style="margin-top:24px;">
                ⚠️ Neraca tidak seimbang! (Kemungkinan ada Laba/Rugi berjalan yang belum ditutup ke Ekuitas).
                Selisih: Rp {{ number_format(abs($totalAset - ($totalKewajiban + $totalEkuitas)),0,',','.') }}
            </div>
        @endif

    </div>
</div>
@endsection
