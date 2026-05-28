<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MPelanggan extends Model
{
    use SoftDeletes;

    protected $table = 'm_pelanggans';

    protected $fillable = [
        'user_id', 'nama', 'no_telp', 'total_piutang', 'piutang_dibayar',
    ];

    protected $casts = [
        'total_piutang'   => 'integer',
        'piutang_dibayar' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }

    public function transaksis()
    {
        return $this->hasMany(TTransaksi::class, 'm_pelanggan_id');
    }

    // Saldo piutang tersisa
    public function getSisaPiutangAttribute(): int
    {
        return $this->total_piutang - $this->piutang_dibayar;
    }
}
