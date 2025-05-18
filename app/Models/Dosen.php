<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';

    protected $fillable = [
        'user_id', // ganti dari 'id_users' jadi 'user_id'
        'nip_dosen',
        'nama_dosen',
        'topik',
        'no_telp',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pengujian1()
    {
        return $this->hasMany(Jadwal::class, 'penguji_1_id');
    }

    public function pengujian2()
    {
        return $this->hasMany(Jadwal::class, 'penguji_2_id');
    }

    public function pengujian3()
    {
        return $this->hasMany(Jadwal::class, 'penguji_3_id');
    }

    public function semuaPengujian()
    {
        return Jadwal::where('penguji_1_id', $this->id_dosen)
            ->orWhere('penguji_2_id', $this->id_dosen)
            ->orWhere('penguji_3_id', $this->id_dosen);
    }

    // User.php
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function prodis()
    {
        return $this->belongsToMany(Prodi::class, 'dosen_prodi', 'id_dosen', 'id_prodi');
    }


}
