<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggunaanDana extends Model
{
    protected $fillable = [
        'tanggal_penggunaan',
        'kategori',
        'deskripsi',
        'jumlah_pengeluaran',
        'bukti_pengeluaran',
        'created_by',
    ];

    protected $casts = [
        'tanggal_penggunaan' => 'date',
        'jumlah_pengeluaran' => 'decimal:2',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getKategoriOptions(): array
    {
        return [
            'operasional' => 'Operasional',
            'maintenance' => 'Maintenance',
            'gaji' => 'Gaji',
            'infrastruktur' => 'Infrastruktur',
            'lainnya' => 'Lainnya',
        ];
    }
}
