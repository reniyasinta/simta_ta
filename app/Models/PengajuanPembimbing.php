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
        return $this->belongsTo(User::class, 'id_dosen1'); // GANTI ke User karena foreign key ke users.id
    }

    public function dosen2()
    {
        return $this->belongsTo(User::class, 'id_dosen2');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs', 'id_mhs');
    }
    public function sempro()
    {
        return $this->hasOne(\App\Models\Sempro::class, 'id_ajuan', 'id_ajuan');
    }

    public function sidang()
    {
        return $this->hasOneThrough(
            Sidang::class,       // Target model
            Sempro::class,       // Perantara model
            'id_ajuan',          // Foreign key di Sempro (yang menunjuk ke pengajuan)
            'id_sempro',         // Foreign key di Sidang (yang menunjuk ke sempro)
            'id_ajuan',          // Local key di PengajuanPembimbing
            'id_sempro'          // Local key di Sempro
        );
    }

    public function jadwals() {
        return $this->hasMany(Jadwal::class, 'id_ajuan', 'id_ajuan');
    }

}
