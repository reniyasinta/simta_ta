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

    // Relasi ke mahasiswa yang masuk ke kelompok ini
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelompok', 'id_kelompok');
    }

    // Relasi ke user sebagai anggota 1
    public function anggota1()
    {
        return $this->belongsTo(User::class, 'anggota_1_id');
    }

    // Relasi ke user sebagai anggota 2
    public function anggota2()
    {
        return $this->belongsTo(User::class, 'anggota_2_id');
    }

    // Relasi ke user sebagai anggota 3
    public function anggota3()
    {
        return $this->belongsTo(User::class, 'anggota_3_id');
    }

    public function pengajuan()
    {
        return $this->hasOne(PengajuanPembimbing::class, 'id_kelompok');
    }

    public function anggota()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelompok', 'id_kelompok');
    }

    public function getAnggotaAttribute()
    {
        $anggota = [];

        if ($this->anggota1 && $this->anggota1->mahasiswa) {
            $anggota[] = $this->anggota1->mahasiswa;
        }
        if ($this->anggota2 && $this->anggota2->mahasiswa) {
            $anggota[] = $this->anggota2->mahasiswa;
        }
        if ($this->anggota3 && $this->anggota3->mahasiswa) {
            $anggota[] = $this->anggota3->mahasiswa;
        }

        return collect($anggota)->filter(); // Buang null
    }
    public function getJumlahAnggotaAttribute()
    {
        return $this->anggota->count(); // anggota adalah relasi ke Mahasiswa
    }


    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }



}
