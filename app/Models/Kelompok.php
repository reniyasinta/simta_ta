<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompok';
    protected $primaryKey = 'id_kelompok';

    protected $fillable = [
        'anggota_1_id',
        'anggota_2_id',
        'anggota_3_id',
    ];

    public function anggota()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelompok', 'id_kelompok');
    }
    
    // Relasi untuk mahasiswa anggota 1
    public function anggota1()
    {
        return $this->belongsTo(Mahasiswa::class, 'anggota_1_id');
    }

    // Relasi untuk mahasiswa anggota 2
    public function anggota2()
    {
        return $this->belongsTo(Mahasiswa::class, 'anggota_2_id');
    }

    // Relasi untuk mahasiswa anggota 3
    public function anggota3()
    {
        return $this->belongsTo(Mahasiswa::class, 'anggota_3_id');
    }

    // Relasi untuk semua mahasiswa yang tergabung dalam kelompok ini
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelompok');
    }

    public function pengajuan()
    {
        return $this->hasOne(PengajuanPembimbing::class, 'id_kelompok');
    }
}
