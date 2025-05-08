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
        'nip', // ← tambahan
        'nim', // ← tambahan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role_id' => 'integer',
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
     */
    public function getRoleNameAttribute()
    {
        return match ($this->role_id) {
            1 => 'admin',
            2 => 'panitia',
            3 => 'dosen',
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
            2 => route('panitia.dashboard'),
            3 => route('dosen.dashboard'),
            4 => route('mahasiswa.dashboard'),
            default => '/',
        };
    }
}
