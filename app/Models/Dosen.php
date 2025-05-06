<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang sesuai dengan database
    protected $table = 'dosen';

    // Menentukan kolom mana yang dapat diisi (mass assignable)
    protected $fillable = [
        'id_users',
        'nip_dosen',
        'nama_dosen',
        'topik',
        'no_telp',
    ];

    // Jika kolom ID menggunakan nama selain 'id', kamu bisa menetapkan seperti berikut:
    protected $primaryKey = 'id_dosen';

    // Jika kamu tidak ingin menggunakan timestamp (created_at dan updated_at)
    public $timestamps = true;

    // Mendefinisikan relasi dengan tabel 'users' (jika ada relasi)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id');
    }
}
