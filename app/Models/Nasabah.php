<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nasabah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nasabah';

    protected $fillable = [
        'user_id',
        'kelompok_id',
        'kode_nasabah',
        'nama',
        'email',
        'phone',
        'address',
        'tanggal_bergabung',
        'saldo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bergabung' => 'date',
            'saldo' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function penjemputan()
    {
        return $this->hasMany(Penjemputan::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function sedekahSampah()
    {
        return $this->hasMany(SedekahSampah::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Accessors
    public function getFormattedSaldoAttribute()
    {
        return 'Rp ' . number_format($this->saldo, 0, ',', '.');
    }

    public function getTotalSedekahAttribute()
    {
        return $this->sedekahSampah()->sum('jumlah_sedekah');
    }
}