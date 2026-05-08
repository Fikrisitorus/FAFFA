<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelompok extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kelompok';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'koordinator',
        'lokasi',
        'jadwal_rutin',
        'is_active',
    ];

    protected $casts = [
    'jadwal_rutin' => 'array',
    'is_active' => 'boolean',
    ];


    // Relasi
    public function nasabah()
    {
        return $this->hasMany(Nasabah::class);
    }

    public function penjemputan()
    {
        return $this->hasMany(Penjemputan::class);
    }

    public function sedekahSampah()
    {
        return $this->hasMany(SedekahSampah::class);
    }

    // Accessor
    public function getTotalNasabahAttribute()
    {
        return $this->nasabah()->count();
    }

    public function getTotalSaldoAttribute()
    {
        return $this->nasabah()->sum('saldo');
    }



}
