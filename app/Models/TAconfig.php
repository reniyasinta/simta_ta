<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TAconfig extends Model
{
    protected $table = 'ta_config';
    protected $fillable = ['id_prodi', 'nama_konfigurasi', 'config_value'];
}
