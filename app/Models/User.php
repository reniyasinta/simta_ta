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
        'nip',
        'nim',
        'id_prodi',
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
     * Relasi ke mahasiswa (jika user adalah mahasiswa).
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'id');
    }

    /**
     * Relasi ke dosen (jika user adalah dosen).
     */
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id', 'id');
    }

    /**
     * Relasi ke roles.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }


    /**
     * Accessor nama role.
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
     * Redirect otomatis sesuai role.
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

    // 🔽 Relasi sebagai dosen pembimbing (id_dosen)
    public function jadwalsSebagaiPembimbing()
    {
        return $this->hasMany(Jadwal::class, 'id_dosen');
    }

    // 🔽 Relasi sebagai penguji
    public function jadwalsSebagaiPenguji1()
    {
        return $this->hasMany(Jadwal::class, 'penguji_1_id');
    }

    public function jadwalsSebagaiPenguji2()
    {
        return $this->hasMany(Jadwal::class, 'penguji_2_id');
    }

    public function jadwalsSebagaiPenguji3()
    {
        return $this->hasMany(Jadwal::class, 'penguji_3_id');
    }
}

