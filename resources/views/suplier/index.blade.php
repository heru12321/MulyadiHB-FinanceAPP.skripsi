@extends('layouts.app')
@section('title','Daftar Suplier')
@section('page-title','Suplier')
@section('breadcrumb','Master Data / Suplier')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">🏭 Daftar Suplier</h2>
        <button class="btn-gold-erp" onclick="openModal('add')">+ Tambah Suplier</button>
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
                        <button class="btn-primary-erp btn-sm-erp" onclick="openModal('edit', {{ $sp->id }}, '{{ $sp->nama }}', '{{ $sp->no_telp }}')">Edit</button>
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

<!-- Modal Suplier -->
<div class="modal fade" id="modalSuplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content card-erp" style="border:none;">
            <div class="modal-header card-erp-header">
                <h5 class="modal-title card-erp-title" id="modalSuplierTitle">Tambah Suplier</h5>
            </div>
            <form id="formSuplier" method="POST" action="{{ route('suplier.store') }}">
                @csrf
                <input type="hidden" name="_method" id="methodSuplier" value="POST">
                <div class="modal-body card-erp-body">
                    <div class="form-group-erp">
                        <label class="form-label-erp">Nama Suplier *</label>
                        <input type="text" name="nama" id="namaSuplier" class="form-control-erp" required>
                    </div>
                    <div class="form-group-erp">
                        <label class="form-label-erp">No. Telepon</label>
                        <input type="text" name="no_telp" id="telpSuplier" class="form-control-erp">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f4f8;padding:16px;">
                    <button type="button" class="btn-danger-erp" onclick="$('#modalSuplier').modal('hide')">Batal</button>
                    <button type="submit" class="btn-gold-erp">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModal(action, id = null, nama = '', no_telp = '') {
    const form = document.getElementById('formSuplier');
    const title = document.getElementById('modalSuplierTitle');
    const method = document.getElementById('methodSuplier');
    
    document.getElementById('namaSuplier').value = nama;
    document.getElementById('telpSuplier').value = no_telp;
    
    if (action === 'add') {
        title.innerText = 'Tambah Suplier';
        method.value = 'POST';
        form.action = "{{ route('suplier.store') }}";
    } else {
        title.innerText = 'Edit Suplier';
        method.value = 'PUT';
        form.action = `/suplier/${id}`;
    }
    
    $('#modalSuplier').modal('show');
}
</script>
@endpush
@endsection
