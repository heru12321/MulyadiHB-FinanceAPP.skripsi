<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MJurnalUmum extends Model
{
    use SoftDeletes;

    protected $table = 'm_jurnal_umums';

    protected $fillable = ['user_id', 'kode', 'keterangan', 'tanggal'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }

    public function coaLogs()
    {
        return $this->hasMany(TCoaLog::class, 'm_jurnal_id');
    }

    // Generate kode jurnal otomatis
    public static function generateKode(): string
    {
        $last = self::withTrashed()->latest('id')->first();
        $num  = $last ? ($last->id + 1) : 1;
        return 'JRN-' . date('Ymd') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
