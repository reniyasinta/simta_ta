<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sempro extends Model
{
    use HasFactory;

    protected $table = 'sempro';
    protected $primaryKey = 'id_sempro';

    protected $fillable = [
        'id_ajuan',
        'laporan_sempro',
        'form_persetujuan_sempro',
        'berita_acara_sempro',
        'status_dospem1',
        'status_dospem2',
        'catatan_dospem1',
        'catatan_dospem2',
    ];

    // Relasi ke pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPembimbing::class, 'id_ajuan');
    }
}
