<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berkas extends Model
{
    protected $table = 'berkas';
    protected $primaryKey = 'id_berkas';

    protected $fillable = [
        'nama_berkas', 'kategori', 'file_path',
    ];
}
