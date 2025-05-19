<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mhs';

    protected $fillable = ['nim_mhs', 'nama_mhs', 'user_id', 'id_kelompok'];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_mhs', 'id_mhs');
    }

    // User.php
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

}
