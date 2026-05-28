<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TPembelian extends Model
{
    use SoftDeletes;

    protected $table = 't_pembelians';

    protected $fillable = [
        'user_id', 'kode_faktur', 'is_lunas', 'm_suplier_id',
        'total_harga', 'total_dibayar', 'tanggal', 'keterangan',
    ];

    protected $casts = [
        'is_lunas'      => 'boolean',
        'total_harga'   => 'integer',
        'total_dibayar' => 'integer',
        'tanggal'       => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }

    public function suplier()
    {
        return $this->belongsTo(MSuplier::class, 'm_suplier_id');
    }

    public function details()
    {
        return $this->hasMany(TPembelianDetail::class, 't_pembelian_id');
    }

    public function coaLogs()
    {
        return $this->hasMany(TCoaLog::class, 't_pembelian_id');
    }

    public function jurnal()
    {
        return $this->hasOne(MJurnalUmum::class, 'id', 'id');
    }

    public function getSisaHutangAttribute(): int
    {
        return $this->total_harga - $this->total_dibayar;
    }

    // Generate kode faktur otomatis
    public static function generateKode(): string
    {
        $last = self::withTrashed()->latest('id')->first();
        $num  = $last ? ($last->id + 1) : 1;
        return 'PB-' . date('Ymd') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
