<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MCoa extends Model
{
    use SoftDeletes;

    protected $table = 'm_coas';

    protected $fillable = ['nomor', 'nama', 'kategori_id', 'tipe_saldo'];

    protected $casts = [
        'tipe_saldo' => 'string',
    ];

    public function kategori()
    {
        return $this->belongsTo(MKategoriCoa::class, 'kategori_id');
    }

    public function coaLogs()
    {
        return $this->hasMany(TCoaLog::class, 'coa_id');
    }

    // Helper: cari COA berdasarkan nomor akun
    public static function cariByNomor(string $nomor): ?self
    {
        return self::where('nomor', $nomor)->first();
    }
}
