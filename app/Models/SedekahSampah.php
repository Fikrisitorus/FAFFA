<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SedekahSampah extends Model
{
    use HasFactory;

    protected $table = 'sedekah_sampah';

    protected $fillable = [
        'transaksi_id',
        'nasabah_id',
        'kelompok_id',
        'jumlah_sedekah',
        'persentase',
        'tanggal_sedekah',
        'bulan_sedekah',
        'tahun_sedekah',
        'keterangan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_sedekah' => 'decimal:2',
            'persentase' => 'decimal:2',
            'tanggal_sedekah' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_USED = 'used';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_USED => 'Sudah Digunakan',
        ];
    }

    // Relasi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    // Accessor
    public function getFormattedJumlahSedekahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_sedekah, 0, ',', '.');
    }

    // Scope untuk bulan sedekah
    public function scopeBulanSedekah($query, $bulan, $tahun)
    {
        return $query->where('bulan_sedekah', $bulan)
                    ->where('tahun_sedekah', $tahun);
    }
}