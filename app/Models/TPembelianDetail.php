<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TPembelianDetail extends Model
{
    use SoftDeletes;

    protected $table = 't_pembelian_details';

    protected $fillable = [
        't_pembelian_id', 'm_stok_id', 'harga_beli', 'jumlah', 'subtotal',
    ];

    protected $casts = [
        'harga_beli' => 'integer',
        'jumlah'     => 'integer',
        'subtotal'   => 'integer',
    ];

    public function pembelian()
    {
        return $this->belongsTo(TPembelian::class, 't_pembelian_id');
    }

    public function stok()
    {
        return $this->belongsTo(MStok::class, 'm_stok_id');
    }
}
