<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompok';
    protected $primaryKey = 'id_kelompok';

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelompok');
    }

    public function pengajuan()
    {
        return $this->hasOne(PengajuanPembimbing::class, 'id_kelompok');
    }



}

