<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MSuplier extends Model
{
    use SoftDeletes;

    protected $table = 'm_supliers';

    protected $fillable = [
        'user_id', 'nama', 'no_telp', 'total_hutang', 'hutang_dibayar',
    ];

    protected $casts = [
        'total_hutang'   => 'integer',
        'hutang_dibayar' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }

    public function pembelians()
    {
        return $this->hasMany(TPembelian::class, 'm_suplier_id');
    }

    // Saldo hutang tersisa
    public function getSisaHutangAttribute(): int
    {
        return $this->total_hutang - $this->hutang_dibayar;
    }
}
