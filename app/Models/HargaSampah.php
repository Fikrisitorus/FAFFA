<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSampah extends Model
{
    use HasFactory;

    protected $table = 'harga_sampah';

    protected $fillable = [
        'jenis_sampah_id',
        'harga',
        'tanggal_berlaku',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'tanggal_berlaku' => 'date',
            'is_active' => 'boolean',
        ];
    }

     // Relasi
    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class);
    }

    // Accessor
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}