@extends('layouts.app')
@section('title','Daftar Suplier')
@section('page-title','Suplier')
@section('breadcrumb','Master Data / Suplier')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">🏭 Daftar Suplier</h2>
        <a href="{{ route('suplier.create') }}" class="btn-gold-erp">+ Tambah Suplier</a>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr><th>#</th><th>Nama</th><th>No. Telp</th><th>Total Hutang</th><th>Terbayar</th><th>Sisa</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($supliers as $i => $sp)
                <tr>
                    <td>{{ $supliers->firstItem()+$i }}</td>
                    <td><strong>{{ $sp->nama }}</strong></td>
                    <td>{{ $sp->no_telp ?? '-' }}</td>
                    <td class="money">Rp {{ number_format($sp->total_hutang,0,',','.') }}</td>
                    <td class="money" style="color:#16a34a;">Rp {{ number_format($sp->hutang_dibayar,0,',','.') }}</td>
                    <td class="money" style="color:{{ $sp->sisa_hutang>0?'#dc2626':'#16a34a' }};">
                        Rp {{ number_format($sp->sisa_hutang,0,',','.') }}
                    </td>
                    <td>
                        <a href="{{ route('suplier.show',$sp->id) }}" class="btn-primary-erp btn-sm-erp">Detail</a>
                        <a href="{{ route('suplier.edit',$sp->id) }}" class="btn-primary-erp btn-sm-erp">Edit</a>
                        <form method="POST" action="{{ route('suplier.destroy',$sp->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn-danger-erp btn-sm-erp" data-confirm="Hapus suplier {{ $sp->nama }}?">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">Belum ada suplier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($supliers->hasPages())
    <div style="padding:16px 22px;">{{ $supliers->links() }}</div>
    @endif
</div>
@endsection
