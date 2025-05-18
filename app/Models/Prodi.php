<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = ['nama_prodi'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_prodi', 'id_prodi', 'id_dosen');
    }

}
