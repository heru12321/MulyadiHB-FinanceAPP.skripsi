@extends('layouts.app')
@section('title', 'Detail Jurnal')
@section('page-title', 'Detail Jurnal')
@section('breadcrumb', 'Akuntansi / Jurnal Umum / Detail')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">📔 Detail Jurnal: {{ $jurnal->kode }}</h2>
        <a href="{{ route('jurnal.index') }}" class="btn-primary-erp btn-sm-erp">← Kembali</a>
    </div>
    <div class="card-erp-body">
        
        <table style="width:100%;font-size:14px;border-spacing:0 8px;margin-bottom:24px;">
            <tr><td style="color:#64748b;width:120px;">Tanggal</td><td>: <strong>{{ $jurnal->tanggal->format('d/m/Y') }}</strong></td></tr>
            <tr><td style="color:#64748b;">Keterangan</td><td>: {{ $jurnal->keterangan }}</td></tr>
        </table>

        <table class="table-erp">
            <thead>
                <tr>
                    <th>Akun (COA)</th>
                    <th>Keterangan Entri</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @php $totalD = 0; $totalK = 0; @endphp
                @foreach($jurnal->coaLogs as $log)
                @php
                    $totalD += (int)$log->debit;
                    $totalK += (int)$log->kredit;
                @endphp
                <tr>
                    <td>
                        <div style="margin-left: {{ $log->kredit ? '20px' : '0' }};">
                            <strong>{{ $log->coa->nomor }}</strong> - {{ $log->coa->nama }}
                        </div>
                    </td>
                    <td>{{ $log->keterangan }}</td>
                    <td class="money" style="color:#16a34a;">{{ $log->debit ? 'Rp '.number_format($log->debit,0,',','.') : '' }}</td>
                    <td class="money" style="color:#dc2626;">{{ $log->kredit ? 'Rp '.number_format($log->kredit,0,',','.') : '' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right;font-weight:bold;">Total:</td>
                    <td class="money" style="font-weight:bold;">Rp {{ number_format($totalD,0,',','.') }}</td>
                    <td class="money" style="font-weight:bold;">Rp {{ number_format($totalK,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($totalD !== $totalK)
        <div class="alert-erp alert-danger" style="margin-top:20px;">
            ⚠️ Peringatan: Jurnal tidak balance! Selisih: Rp {{ number_format(abs($totalD - $totalK), 0, ',', '.') }}
        </div>
        @else
        <div class="alert-erp alert-success" style="margin-top:20px;">
            ✅ Jurnal Balance.
        </div>
        @endif

    </div>
</div>
@endsection
