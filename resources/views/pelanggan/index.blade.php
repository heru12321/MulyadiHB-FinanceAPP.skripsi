@extends('layouts.app')
@section('title','Daftar Customer')
@section('page-title','Customer')
@section('breadcrumb','Master Data / Customer')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">👥 Daftar Customer</h2>
        <a href="{{ route('pelanggan.create') }}" class="btn-gold-erp">+ Tambah Customer</a>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr><th>#</th><th>Nama</th><th>No. Telp</th><th>Total Piutang</th><th>Terbayar</th><th>Sisa</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $i => $p)
                <tr>
                    <td>{{ $pelanggans->firstItem()+$i }}</td>
                    <td><strong>{{ $p->nama }}</strong></td>
                    <td>{{ $p->no_telp ?? '-' }}</td>
                    <td class="money">Rp {{ number_format($p->total_piutang,0,',','.') }}</td>
                    <td class="money" style="color:#16a34a;">Rp {{ number_format($p->piutang_dibayar,0,',','.') }}</td>
                    <td class="money" style="color:{{ $p->sisa_piutang>0?'#1d4ed8':'#16a34a' }};">
                        Rp {{ number_format($p->sisa_piutang,0,',','.') }}
                    </td>
                    <td>
                        <a href="{{ route('pelanggan.show',$p->id) }}" class="btn-primary-erp btn-sm-erp">Detail</a>
                        <a href="{{ route('pelanggan.edit',$p->id) }}" class="btn-primary-erp btn-sm-erp">Edit</a>
                        <form method="POST" action="{{ route('pelanggan.destroy',$p->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn-danger-erp btn-sm-erp" data-confirm="Hapus customer {{ $p->nama }}?">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">Belum ada customer.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pelanggans->hasPages())
    <div style="padding:16px 22px;">{{ $pelanggans->links() }}</div>
    @endif
</div>
@endsection
