<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuotaBimbinganDosen extends Model
{
    protected $table = 'kuota_bimbingan_dosen';

    protected $fillable = [
        'id_dosen',
        'id_prodi',
        'kuota_bimbingan',
        'kuota_p2',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }
}
