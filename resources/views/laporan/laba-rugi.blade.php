@extends('layouts.app')
@section('title', 'Laba Rugi')
@section('page-title', 'Laporan Laba Rugi')
@section('breadcrumb', 'Laporan / Laba Rugi')

@section('content')
<div class="card-erp" style="margin-bottom:20px;">
    <div class="card-erp-body">
        <form method="GET" action="{{ route('laporan.laba-rugi') }}" style="display:flex;gap:16px;align-items:flex-end;">
            <div style="flex:1;">
                <label class="form-label-erp">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control-erp" value="{{ $dari }}" required>
            </div>
            <div style="flex:1;">
                <label class="form-label-erp">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control-erp" value="{{ $sampai }}" required>
            </div>
            <div>
                <button type="submit" class="btn-primary-erp" style="padding:10px 20px;">Tampilkan</button>
                <a href="{{ route('laporan.laba-rugi.pdf', request()->all()) }}" target="_blank" class="btn-danger-erp" style="padding:10px 16px;background:#dc2626;color:#fff;">PDF</a>
            </div>
        </form>
    </div>
</div>

<div class="card-erp" style="max-width:800px;margin:0 auto;">
    <div class="card-erp-header" style="flex-direction:column;align-items:center;padding:24px;">
        <h2 style="margin:0 0 8px;font-size:20px;font-weight:800;color:var(--brand-dark);">LAPORAN LABA RUGI</h2>
        <div style="font-size:16px;font-weight:600;">{{ Auth::user()->nama_perusahaan }}</div>
        <div style="font-size:13px;color:#64748b;margin-top:4px;">Periode: {{ date('d/m/Y', strtotime($dari)) }} - {{ date('d/m/Y', strtotime($sampai)) }}</div>
    </div>
    
    <div class="card-erp-body" style="padding:32px;">
        
        {{-- PENDAPATAN --}}
        <h4 style="font-size:15px;color:#16a34a;border-bottom:2px solid #16a34a;padding-bottom:6px;margin-bottom:12px;">PENDAPATAN</h4>
        <table style="width:100%;font-size:14px;margin-bottom:24px;">
            @foreach($pendapatanItems as $item)
            @php $saldo = $item->total_kredit - $item->total_debit; @endphp
            @if($saldo != 0)
            <tr>
                <td style="padding:6px 0;">{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                <td class="money" style="text-align:right;">Rp {{ number_format($saldo,0,',','.') }}</td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td style="padding:8px 0;font-weight:bold;">Total Pendapatan</td>
                <td class="money" style="text-align:right;font-weight:bold;border-top:1px solid #e2e8f0;">
                    Rp {{ number_format($totalPendapatan,0,',','.') }}
                </td>
            </tr>
        </table>

        {{-- HARGA POKOK PENJUALAN --}}
        <h4 style="font-size:15px;color:#a16207;border-bottom:2px solid #a16207;padding-bottom:6px;margin-bottom:12px;">HARGA POKOK PENJUALAN</h4>
        <table style="width:100%;font-size:14px;margin-bottom:24px;">
            @foreach($hppItems as $item)
            @php $saldo = $item->total_debit - $item->total_kredit; @endphp
            @if($saldo != 0)
            <tr>
                <td style="padding:6px 0;">{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                <td class="money" style="text-align:right;">Rp {{ number_format($saldo,0,',','.') }}</td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td style="padding:8px 0;font-weight:bold;">Total HPP</td>
                <td class="money" style="text-align:right;font-weight:bold;border-top:1px solid #e2e8f0;">
                    Rp {{ number_format($totalHPP,0,',','.') }}
                </td>
            </tr>
        </table>

        {{-- LABA KOTOR --}}
        <div style="background:#f8fafc;padding:12px 16px;border-radius:8px;display:flex;justify-content:space-between;margin-bottom:32px;border:1px solid #e2e8f0;">
            <strong style="font-size:15px;">Laba (Rugi) Kotor</strong>
            <strong class="money" style="font-size:16px;color:{{ $labaKotor < 0 ? '#dc2626' : '#1d4ed8' }};">
                Rp {{ number_format($labaKotor,0,',','.') }}
            </strong>
        </div>

        {{-- BEBAN --}}
        <h4 style="font-size:15px;color:#dc2626;border-bottom:2px solid #dc2626;padding-bottom:6px;margin-bottom:12px;">BEBAN OPERASIONAL</h4>
        <table style="width:100%;font-size:14px;margin-bottom:24px;">
            @foreach($bebanItems as $item)
            @php $saldo = $item->total_debit - $item->total_kredit; @endphp
            @if($saldo != 0)
            <tr>
                <td style="padding:6px 0;">{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                <td class="money" style="text-align:right;">Rp {{ number_format($saldo,0,',','.') }}</td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td style="padding:8px 0;font-weight:bold;">Total Beban Operasional</td>
                <td class="money" style="text-align:right;font-weight:bold;border-top:1px solid #e2e8f0;">
                    Rp {{ number_format($totalBeban,0,',','.') }}
                </td>
            </tr>
        </table>

        {{-- LABA BERSIH --}}
        <div style="background:var(--brand-dark);color:#fff;padding:16px;border-radius:10px;display:flex;justify-content:space-between;box-shadow:0 10px 25px rgba(26,60,94,0.3);">
            <strong style="font-size:18px;">Laba (Rugi) Bersih</strong>
            <strong class="money" style="font-size:20px;color:{{ $labaBersih < 0 ? '#fca5a5' : '#86efac' }};">
                Rp {{ number_format($labaBersih,0,',','.') }}
            </strong>
        </div>

    </div>
</div>
@endsection
