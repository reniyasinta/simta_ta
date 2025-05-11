<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPembimbing extends Model
{
    protected $table = 'pengajuan_pembimbing';
    protected $primaryKey = 'id_ajuan';

    protected $fillable = [
        'id_kelompok',
        'id_dosen1',
        'judul_ta',
        'proposal',
        'status',
        'keterangan',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }

    public function dosen1()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen1', 'id_dosen');
    }
}
