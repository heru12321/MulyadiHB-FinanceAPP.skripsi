<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MStok extends Model
{
    use SoftDeletes;

    protected $table = 'm_stoks';

    protected $fillable = [
        'user_id', 'nama', 'sku', 'deskripsi', 'harga', 'jumlah_stok',
    ];

    protected $casts = [
        'harga'       => 'integer',
        'jumlah_stok' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }

    public function pembelianDetails()
    {
        return $this->hasMany(TPembelianDetail::class, 'm_stok_id');
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TTransaksiDetail::class, 'm_stok_id');
    }
}
