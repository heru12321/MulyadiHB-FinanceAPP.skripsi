@extends('layouts.app')
@section('title', 'Jurnal Umum')
@section('page-title', 'Jurnal Umum')
@section('breadcrumb', 'Akuntansi / Jurnal Umum')

@section('content')
<div class="card-erp" style="margin-bottom:20px;">
    <div class="card-erp-body">
        <form method="GET" action="{{ route('jurnal.index') }}" style="display:flex;gap:16px;align-items:flex-end;">
            <div style="flex:1;">
                <label class="form-label-erp">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control-erp" value="{{ request('dari') }}">
            </div>
            <div style="flex:1;">
                <label class="form-label-erp">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control-erp" value="{{ request('sampai') }}">
            </div>
            <div style="flex:2;">
                <label class="form-label-erp">Filter Akun (COA)</label>
                <select name="coa_id" class="form-control-erp">
                    <option value="">-- Semua Akun --</option>
                    @foreach($coas as $coa)
                        <option value="{{ $coa->id }}" {{ request('coa_id') == $coa->id ? 'selected' : '' }}>
                            {{ $coa->nomor }} - {{ $coa->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn-primary-erp" style="padding:10px 20px;">🔍 Filter</button>
                @if(request()->anyFilled(['dari','sampai','coa_id']))
                    <a href="{{ route('jurnal.index') }}" class="btn-danger-erp" style="padding:10px 16px;">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">📔 Riwayat Jurnal Umum</h2>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode / Keterangan</th>
                    <th>Akun (COA)</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnals as $j)
                    @foreach($j->coaLogs as $index => $log)
                    <tr style="{{ $index === $j->coaLogs->count() - 1 ? 'border-bottom:2px solid #e8edf2;' : 'border-bottom:none;' }}">
                        @if($index === 0)
                            <td rowspan="{{ $j->coaLogs->count() }}" style="vertical-align:top;border-bottom:2px solid #e8edf2;">
                                <strong>{{ $j->tanggal->format('d/m/Y') }}</strong>
                            </td>
                            <td rowspan="{{ $j->coaLogs->count() }}" style="vertical-align:top;border-bottom:2px solid #e8edf2;">
                                <div style="font-weight:700;color:var(--brand-dark);">{{ $j->kode }}</div>
                                <div style="font-size:12px;color:#64748b;margin-top:4px;">{{ $j->keterangan }}</div>
                            </td>
                        @endif
                        <td>
                            <div style="margin-left: {{ $log->kredit ? '20px' : '0' }};">
                                {{ $log->coa->nomor }} - {{ $log->coa->nama }}
                                <div style="font-size:11px;color:#94a3b8;">{{ $log->keterangan }}</div>
                            </div>
                        </td>
                        <td class="money" style="color:#16a34a;">{{ $log->debit ? 'Rp '.number_format($log->debit,0,',','.') : '' }}</td>
                        <td class="money" style="color:#dc2626;">{{ $log->kredit ? 'Rp '.number_format($log->kredit,0,',','.') : '' }}</td>
                        @if($index === 0)
                            <td rowspan="{{ $j->coaLogs->count() }}" style="vertical-align:top;border-bottom:2px solid #e8edf2;">
                                <a href="{{ route('jurnal.show', $j->id) }}" class="btn-primary-erp btn-sm-erp">Detail</a>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">Tidak ada data jurnal pada periode/filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jurnals->hasPages())
    <div style="padding:16px 22px;">{{ $jurnals->links() }}</div>
    @endif
</div>
@endsection
