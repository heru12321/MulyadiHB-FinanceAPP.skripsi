<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransaksi extends Model
{
    use SoftDeletes;

    protected $table = 't_transaksis';

    protected $fillable = [
        'user_id', 'kode_inv', 'is_lunas', 'm_pelanggan_id',
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

    public function pelanggan()
    {
        return $this->belongsTo(MPelanggan::class, 'm_pelanggan_id');
    }

    public function details()
    {
        return $this->hasMany(TTransaksiDetail::class, 't_transaksi_id');
    }

    public function coaLogs()
    {
        return $this->hasMany(TCoaLog::class, 't_transaksi_id');
    }

    public function getSisaPiutangAttribute(): int
    {
        return $this->total_harga - $this->total_dibayar;
    }

    // Generate kode invoice otomatis
    public static function generateKode(): string
    {
        $last = self::withTrashed()->latest('id')->first();
        $num  = $last ? ($last->id + 1) : 1;
        return 'INV-' . date('Ymd') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
