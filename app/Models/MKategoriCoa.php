<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MKategoriCoa extends Model
{
    use SoftDeletes;

    protected $table = 'm_kategori_coas';

    protected $fillable = ['nama'];

    public function coas()
    {
        return $this->hasMany(MCoa::class, 'kategori_id');
    }
}
