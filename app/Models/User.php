<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'address',
        'is_active',
        'is_verified',
        'kelompok_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Izinkan admin, pengepul, kelompok_nasabah, dan nasabah (user yang punya relasi nasabah)
        return $this->hasAnyRole(['admin', 'pengepul', 'kelompok_nasabah', 'nasabah']) || $this->nasabah;
    }

    // Relasi
    public function nasabah()
    {
        return $this->hasOne(Nasabah::class);
    }

    public function penjemputanAsPengepul()
    {
        return $this->hasMany(Penjemputan::class, 'pengepul_id');
    }

    public function transaksiAsPengepul()
    {
        return $this->hasMany(Transaksi::class, 'pengepul_id');
    }

    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'author_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}