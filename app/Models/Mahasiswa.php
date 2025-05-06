<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['nim_mhs', 'nama_mhs', 'user_id', 'id_kelompok'];
    protected $table = 'mahasiswa';

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
