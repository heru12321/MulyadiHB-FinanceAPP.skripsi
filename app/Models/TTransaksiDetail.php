<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransaksiDetail extends Model
{
    use SoftDeletes;

    protected $table = 't_transaksi_details';

    protected $fillable = [
        't_transaksi_id', 'm_stok_id', 'harga_satuan', 'jumlah', 'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'integer',
        'jumlah'       => 'integer',
        'subtotal'     => 'integer',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TTransaksi::class, 't_transaksi_id');
    }

    public function stok()
    {
        return $this->belongsTo(MStok::class, 'm_stok_id');
    }
}
