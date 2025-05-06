<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke tabel mahasiswa (jika user adalah mahasiswa).
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    /**
     * Relasi ke tabel roles.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Accessor untuk mendapatkan nama role dari role_id.
     * Bisa dipanggil via $user->role_name
     */
    public function getRoleNameAttribute()
    {
        return match ($this->role_id) {
            1 => 'admin',
            2 => 'dosen',
            3 => 'panitia',
            4 => 'mahasiswa',
            default => 'unknown',
        };
    }

    /**
     * Method untuk redirect otomatis sesuai role saat login.
     */
    public function redirectTo()
    {
        return match ($this->role_id) {
            1 => route('admin.dashboard'),
            2 => route('dosen.dashboard'),
            3 => route('panitia.dashboard'),
            4 => route('mahasiswa.dashboard'),
            default => '/',
        };
    }
}
