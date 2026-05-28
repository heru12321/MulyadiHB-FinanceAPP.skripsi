@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('breadcrumb', 'Master Data / Stok')

@section('content')
<div class="card-erp">
    <div class="card-erp-header">
        <h2 class="card-erp-title">📦 Daftar Stok Barang</h2>
        <button class="btn-gold-erp" onclick="openModal('add')">+ Tambah Stok</button>
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
                        <button class="btn-primary-erp btn-sm-erp" onclick="openModal('edit', {{ $s->id }}, '{{ $s->nama }}', '{{ $s->sku }}', '{{ $s->deskripsi }}', '{{ $s->harga }}')">Edit</button>
                        <form method="POST" action="{{ route('stok.destroy',$s->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger-erp btn-sm-erp"
                                data-confirm="Hapus stok {{ $s->nama }}?">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">Belum ada stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stoks->hasPages())
    <div style="padding:16px 22px;border-top:1px solid #f0f4f8;">{{ $stoks->links() }}</div>
    @endif
</div>

<!-- Modal Stok -->
<div class="modal fade" id="modalStok" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content card-erp" style="border:none;">
            <div class="modal-header card-erp-header">
                <h5 class="modal-title card-erp-title" id="modalStokTitle">Tambah Stok</h5>
            </div>
            <form id="formStok" method="POST" action="{{ route('stok.store') }}">
                @csrf
                <input type="hidden" name="_method" id="methodStok" value="POST">
                <div class="modal-body card-erp-body">
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group-erp">
                            <label class="form-label-erp">Nama Barang *</label>
                            <input type="text" name="nama" id="namaStok" class="form-control-erp" required>
                        </div>
                        <div class="form-group-erp">
                            <label class="form-label-erp">SKU *</label>
                            <input type="text" name="sku" id="skuStok" class="form-control-erp" placeholder="Contoh: BRG-001" required>
                        </div>
                    </div>

                    <div class="form-group-erp">
                        <label class="form-label-erp">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsiStok" class="form-control-erp" rows="2"></textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group-erp">
                            <label class="form-label-erp">Harga Jual (Rp) *</label>
                            <input type="number" name="harga" id="hargaStok" class="form-control-erp" min="0" required>
                        </div>
                        <div class="form-group-erp create-only">
                            <label class="form-label-erp">Jumlah Stok Awal *</label>
                            <input type="number" name="jumlah_stok" id="jumlahStok" class="form-control-erp" min="0">
                        </div>
                    </div>

                    <div class="create-only" id="pembelianSection">
                        <hr style="border:none;border-top:1px solid #f0f4f8;margin:16px 0;">
                        <p style="font-size:13px;color:#64748b;margin:0 0 14px;">
                            <strong>ℹ️ Pembelian Otomatis:</strong> Saat stok ditambahkan pertama kali, jurnal pembelian otomatis dibuat.
                        </p>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div class="form-group-erp">
                                <label class="form-label-erp">Suplier *</label>
                                <select name="m_suplier_id" id="suplierStok" class="form-control-erp">
                                    <option value="">-- Pilih Suplier --</option>
                                    @foreach($supliers as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group-erp">
                                <label class="form-label-erp">Harga Beli per Unit (Rp) *</label>
                                <input type="number" name="harga_beli" id="hargaBeliStok" class="form-control-erp" min="0">
                            </div>
                        </div>

                        <div class="form-group-erp">
                            <label class="form-label-erp">Tipe Pembayaran Pembelian *</label>
                            <div style="display:flex;gap:20px;margin-top:4px;">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                                    <input type="radio" name="tipe_pembelian" value="tunai" checked>
                                    💵 Tunai (DEBIT Persediaan, KREDIT Kas)
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                                    <input type="radio" name="tipe_pembelian" value="kredit">
                                    🏦 Kredit (DEBIT Persediaan, KREDIT Hutang Usaha)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="edit-only" id="editWarning" style="display:none;background:#fef9c3;border:1px solid #fcd34d;border-radius:10px;padding:12px 16px;margin-bottom:10px;font-size:13px;color:#92400e;">
                        ⚠️ Untuk menambah kuantitas stok, gunakan menu <strong>Transaksi -> Pembelian</strong>.
                    </div>

                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f4f8;padding:16px;">
                    <button type="button" class="btn-danger-erp" onclick="$('#modalStok').modal('hide')">Batal</button>
                    <button type="submit" class="btn-gold-erp">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModal(action, id = null, nama = '', sku = '', deskripsi = '', harga = '') {
    const form = document.getElementById('formStok');
    const title = document.getElementById('modalStokTitle');
    const method = document.getElementById('methodStok');
    
    document.getElementById('namaStok').value = nama;
    document.getElementById('skuStok').value = sku;
    document.getElementById('deskripsiStok').value = deskripsi;
    document.getElementById('hargaStok').value = harga;
    
    const createOnlyElements = document.querySelectorAll('.create-only');
    const editOnlyElements = document.querySelectorAll('.edit-only');
    
    if (action === 'add') {
        title.innerText = 'Tambah Stok & Pembelian Awal';
        method.value = 'POST';
        form.action = "{{ route('stok.store') }}";
        
        // Show create fields, add required
        createOnlyElements.forEach(el => el.style.display = 'block');
        editOnlyElements.forEach(el => el.style.display = 'none');
        document.getElementById('jumlahStok').setAttribute('required', 'required');
        document.getElementById('suplierStok').setAttribute('required', 'required');
        document.getElementById('hargaBeliStok').setAttribute('required', 'required');
        
    } else {
        title.innerText = 'Edit Data Stok';
        method.value = 'PUT';
        form.action = `/stok/${id}`;
        
        // Hide create fields, remove required
        createOnlyElements.forEach(el => el.style.display = 'none');
        editOnlyElements.forEach(el => el.style.display = 'block');
        document.getElementById('jumlahStok').removeAttribute('required');
        document.getElementById('suplierStok').removeAttribute('required');
        document.getElementById('hargaBeliStok').removeAttribute('required');
    }
    
    $('#modalStok').modal('show');
}
</script>
@endpush
@endsection
