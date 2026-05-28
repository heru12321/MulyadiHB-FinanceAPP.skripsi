<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MUser extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'm_users';

    protected $fillable = [
        'nama', 'nama_perusahaan', 'password',
        'no_telp', 'email', 'alamat',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function stoks()
    {
        return $this->hasMany(MStok::class, 'user_id');
    }

    public function supliers()
    {
        return $this->hasMany(MSuplier::class, 'user_id');
    }

    public function pelanggans()
    {
        return $this->hasMany(MPelanggan::class, 'user_id');
    }

    public function pembelians()
    {
        return $this->hasMany(TPembelian::class, 'user_id');
    }

    public function transaksis()
    {
        return $this->hasMany(TTransaksi::class, 'user_id');
    }

    public function jurnalUmums()
    {
        return $this->hasMany(MJurnalUmum::class, 'user_id');
    }
}
