<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidangUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sidang',
        'jenis_upload',
        'file_path',
        'file_path_2',
        'uploaded_at'
    ];

    public $timestamps = false;

    protected $dates = ['uploaded_at'];

    public function sidang()
    {
        return $this->belongsTo(Sidang::class, 'id_sidang');
    }
}
