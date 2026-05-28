@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('breadcrumb', 'Master Data / Stok')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">📦 Daftar Stok Barang</h2>
        <a href="{{ route('stok.create') }}" class="btn-gold-erp">+ Tambah Stok</a>
    </div>
    <div class="card-erp-body" style="padding:0;">
        <table class="table-erp">
            <thead>
                <tr>
                    <th>#</th><th>SKU</th><th>Nama Barang</th>
                    <th>Harga Jual</th><th>Stok</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stoks as $i => $s)
                <tr>
                    <td>{{ $stoks->firstItem() + $i }}</td>
                    <td><code style="font-size:12px;background:#f0f4f8;padding:2px 6px;border-radius:4px;">{{ $s->sku }}</code></td>
                    <td><strong>{{ $s->nama }}</strong><div style="font-size:11px;color:#94a3b8;">{{ Str::limit($s->deskripsi,50) }}</div></td>
                    <td class="money">Rp {{ number_format($s->harga,0,',','.') }}</td>
                    <td>
                        @if($s->jumlah_stok <= 5)
                            <span class="badge-erp badge-danger">{{ $s->jumlah_stok }} unit</span>
                        @elseif($s->jumlah_stok <= 20)
                            <span class="badge-erp badge-warning">{{ $s->jumlah_stok }} unit</span>
                        @else
                            <span class="badge-erp badge-success">{{ $s->jumlah_stok }} unit</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('stok.edit',$s->id) }}" class="btn-primary-erp btn-sm-erp">Edit</a>
                        <form method="POST" action="{{ route('stok.destroy',$s->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger-erp btn-sm-erp"
                                data-confirm="Hapus stok {{ $s->nama }}?">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">Belum ada stok. <a href="{{ route('stok.create') }}">Tambah sekarang</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stoks->hasPages())
    <div style="padding:16px 22px;border-top:1px solid #f0f4f8;">{{ $stoks->links() }}</div>
    @endif
</div>
@endsection
