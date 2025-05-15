<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Jadwal extends Model
{
    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'tempat',
        'jenis_acara',
        'judul_ta',
        'nim',
        'name',
        'prodi',
        'kelas',
        'pembimbing_1',
        'pembimbing_2',
        'penguji_1_id',
        'penguji_2_id',
        'penguji_3_id',
    ];

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


}
