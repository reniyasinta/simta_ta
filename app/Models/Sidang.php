<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Kelompok;

class Sidang extends Model
{
    use HasFactory;

    protected $table = 'sidang';
    protected $primaryKey = 'id_sidang';

    protected $fillable = [
        'id_kelompok',
        'id_dosen1',
        'id_dosen2',
        'id_sempro',
        'laporan_TA',
        'lembar_konsultasi',
        'revisi_laporan',
        'laporan_akhir',
        'hasil_sidang',
        'status',
        'catatan_dosen',
        'penguji_1_id',
        'penguji_2_id',
        'penguji_3_id',
        'status_draft_dosen1',
        'status_draft_dosen2',
        'status_revisi_penguji_1',
        'status_revisi_penguji_2',
        'status_revisi_penguji_3',
        'catatan_penguji_1',
        'catatan_penguji_2',
        'catatan_penguji_3',
        'laporan_akhir_pdf',
        
        'laporan_akhir_word',
        'berita_acara',
        'buku_manual',
        'halaman_pengesahan',
    ];

    // Relasi ke mahasiswa (via kelompok)
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_kelompok', 'id_kelompok');
    }

    // Relasi ke dosen pembimbing
    public function dosen1()
    {
        return $this->belongsTo(User::class, 'id_dosen1');
    }

    public function dosen2()
    {
        return $this->belongsTo(User::class, 'id_dosen2');
    }

    // Relasi ke kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id_kelompok');
    }

    // Relasi ke dosen penguji
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
