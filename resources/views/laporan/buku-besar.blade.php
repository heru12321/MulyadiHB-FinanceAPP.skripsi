@extends('layouts.app')
@section('title', 'Buku Besar')
@section('page-title', 'Buku Besar')
@section('breadcrumb', 'Laporan / Buku Besar')

@section('content')
<div class="card-erp" style="margin-bottom:20px;">
    <div class="card-erp-body">
        <form method="GET" action="{{ route('laporan.buku-besar') }}" style="display:flex;gap:16px;align-items:flex-end;">
            <div style="flex:2;">
                <label class="form-label-erp">Pilih Akun (COA) *</label>
                <select name="coa_id" class="form-control-erp" required>
                    <option value="">-- Pilih Akun --</option>
                    @foreach($coas as $coa)
                        <option value="{{ $coa->id }}" {{ request('coa_id') == $coa->id ? 'selected' : '' }}>
                            {{ $coa->nomor }} - {{ $coa->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;">
                <label class="form-label-erp">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control-erp" value="{{ request('dari') }}">
            </div>
            <div style="flex:1;">
                <label class="form-label-erp">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control-erp" value="{{ request('sampai') }}">
            </div>
            <div>
                <button type="submit" class="btn-primary-erp" style="padding:10px 20px;">Tampilkan</button>
                @if(request('coa_id'))
                    <a href="{{ route('laporan.buku-besar.pdf', request()->all()) }}" target="_blank" class="btn-danger-erp" style="padding:10px 16px;background:#dc2626;color:#fff;">PDF</a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($selectedCoa)
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">📒 Buku Besar: {{ $selectedCoa->nomor }} - {{ $selectedCoa->nama }}</h2>
        <div style="font-size:12px;color:#64748b;">Saldo Normal: <strong>{{ strtoupper($selectedCoa->tipe_saldo) }}</strong></div>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Jurnal</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Saldo Berjalan</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background:#f8fafc;">
                    <td colspan="5" style="text-align:right;font-weight:600;">Saldo Awal</td>
                    <td class="money">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                </tr>
                @php 
                    $totDebit = 0; $totKredit = 0; 
                @endphp
                @forelse($logs as $log)
                @php
                    $totDebit += (int)$log->debit;
                    $totKredit += (int)$log->kredit;
                @endphp
                <tr>
                    <td>{{ $log->tanggal->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('jurnal.show', $log->jurnal->id) }}" style="color:var(--brand-dark);font-weight:600;">
                            {{ $log->jurnal->kode }}
                        </a>
                    </td>
                    <td>{{ $log->keterangan }}</td>
                    <td class="money">{{ $log->debit ? 'Rp '.number_format($log->debit,0,',','.') : '-' }}</td>
                    <td class="money">{{ $log->kredit ? 'Rp '.number_format($log->kredit,0,',','.') : '-' }}</td>
                    <td class="money" style="font-weight:600;">Rp {{ number_format($log->saldo_berjalan,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">Tidak ada transaksi untuk akun ini pada periode yang dipilih.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:#f0f4f8;">
                    <td colspan="3" style="text-align:right;font-weight:bold;">Total Mutasi:</td>
                    <td class="money" style="font-weight:bold;">Rp {{ number_format($totDebit,0,',','.') }}</td>
                    <td class="money" style="font-weight:bold;">Rp {{ number_format($totKredit,0,',','.') }}</td>
                    <td></td>
                </tr>
                <tr style="background:#e8edf2;">
                    <td colspan="5" style="text-align:right;font-weight:bold;font-size:15px;color:var(--brand-dark);">Saldo Akhir:</td>
                    <td class="money" style="font-weight:bold;font-size:16px;color:var(--brand-dark);">
                        Rp {{ number_format($logs->last()->saldo_berjalan ?? $saldoAwal, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif
@endsection
