<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sempro extends Model
{
    use HasFactory;

    protected $table = 'sempro';
    protected $primaryKey = 'id_Sempro';

    protected $fillable = [
        'id_ajuan',
        'form_persetujuan_sempro',
        'hasil_sempro',
    ];

    // Relasi ke pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPembimbing::class, 'id_ajuan');
    }
}
