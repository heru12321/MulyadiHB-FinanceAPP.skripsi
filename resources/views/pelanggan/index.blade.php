@extends('layouts.app')
@section('title','Daftar Customer')
@section('page-title','Customer')
@section('breadcrumb','Master Data / Customer')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">👥 Daftar Customer</h2>
        <button class="btn-gold-erp" onclick="openModal('add')">+ Tambah Customer</button>
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
                        <button class="btn-primary-erp btn-sm-erp" onclick="openModal('edit', {{ $p->id }}, '{{ $p->nama }}', '{{ $p->no_telp }}')">Edit</button>
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

<!-- Modal Customer -->
<div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content card-erp" style="border:none;">
            <div class="modal-header card-erp-header">
                <h5 class="modal-title card-erp-title" id="modalCustomerTitle">Tambah Customer</h5>
            </div>
            <form id="formCustomer" method="POST" action="{{ route('pelanggan.store') }}">
                @csrf
                <input type="hidden" name="_method" id="methodCustomer" value="POST">
                <div class="modal-body card-erp-body">
                    <div class="form-group-erp">
                        <label class="form-label-erp">Nama Customer *</label>
                        <input type="text" name="nama" id="namaCustomer" class="form-control-erp" required>
                    </div>
                    <div class="form-group-erp">
                        <label class="form-label-erp">No. Telepon</label>
                        <input type="text" name="no_telp" id="telpCustomer" class="form-control-erp">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f4f8;padding:16px;">
                    <button type="button" class="btn-danger-erp" onclick="$('#modalCustomer').modal('hide')">Batal</button>
                    <button type="submit" class="btn-gold-erp">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModal(action, id = null, nama = '', no_telp = '') {
    const form = document.getElementById('formCustomer');
    const title = document.getElementById('modalCustomerTitle');
    const method = document.getElementById('methodCustomer');
    
    document.getElementById('namaCustomer').value = nama;
    document.getElementById('telpCustomer').value = no_telp;
    
    if (action === 'add') {
        title.innerText = 'Tambah Customer';
        method.value = 'POST';
        form.action = "{{ route('pelanggan.store') }}";
    } else {
        title.innerText = 'Edit Customer';
        method.value = 'PUT';
        form.action = `/pelanggan/${id}`;
    }
    
    $('#modalCustomer').modal('show');
}
</script>
@endpush
@endsection
