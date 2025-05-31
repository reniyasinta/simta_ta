<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Undangan extends Model
{
    protected $table = 'undangan';

    protected $fillable = [
        'jadwal_id',
        'penguji_id',
        'jenis_acara',
        'file_path',
        'uploaded_by',
    ];

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    // Relasi ke penguji
    public function penguji()
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }

    // Relasi ke mahasiswa (uploaded_by)
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
