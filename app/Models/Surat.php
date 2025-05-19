<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';
    protected $primaryKey = 'id_surat';
    protected $fillable = ['id_mhs', 'tujuan', 'judul_ta', 'dosen_pembimbing', 'status', 'file_surat'];

    public function mahasiswa()
    {
    return $this->belongsTo(Mahasiswa::class, 'id_mhs', 'id_mhs');
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }
}
