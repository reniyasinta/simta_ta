<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    protected $fillable = [
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'jenis_acara',
        'judul_ta',
        'nim',
        'nama',
        'prodi',
        'kelas',
        'pembimbing_1',
        'pembimbing_2',
        'penguji_1_id',
        'penguji_2_id',
        'penguji_3_id',
        'id_kelompok',
        'id_dosen',
        'id_ajuan',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id_kelompok');
    }
    // Relasi ke mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs', 'id_mhs');
    }

    // Relasi ke dosen user (optional)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    // Relasi ke penguji
    public function penguji1()
    {
        return $this->belongsTo(User::class, 'penguji_1_id');
    }

    public function penguji2()
    {
        return $this->belongsTo(User::class, 'penguji_2_id');
    }

    public function penguji3()
    {
        return $this->belongsTo(User::class, 'penguji_3_id');
    }

    // Relasi ke pengajuan pembimbing
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPembimbing::class, 'id_ajuan', 'id_ajuan');
    }
}

