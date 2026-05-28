<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TCoaLog extends Model
{
    use SoftDeletes;

    protected $table = 't_coa_logs';

    protected $fillable = [
        'user_id', 'coa_id', 'debit', 'kredit', 'keterangan',
        'm_jurnal_id', 't_pembelian_id', 't_transaksi_id', 'tanggal',
    ];

    protected $casts = [
        'debit'   => 'integer',
        'kredit'  => 'integer',
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }

    public function coa()
    {
        return $this->belongsTo(MCoa::class, 'coa_id');
    }

    public function jurnal()
    {
        return $this->belongsTo(MJurnalUmum::class, 'm_jurnal_id');
    }

    public function pembelian()
    {
        return $this->belongsTo(TPembelian::class, 't_pembelian_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(TTransaksi::class, 't_transaksi_id');
    }
}
