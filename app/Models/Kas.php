<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';

    protected $fillable = [
        'nasabah_id',
        'transaksi_id',
        'sedekah_sampah_id',
        'tipe',
        'jumlah',
        'deskripsi',
        'tanggal',
        'saldo_sebelum',
        'saldo_sesudah',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'saldo_sebelum' => 'decimal:2',
            'saldo_sesudah' => 'decimal:2',
            'tanggal' => 'datetime',
        ];
    }

    // Tipe constants
    const TIPE_MASUK = 'masuk';
    const TIPE_KELUAR = 'keluar';

    public static function getTipeOptions()
    {
        return [
            self::TIPE_MASUK => 'Kas Masuk',
            self::TIPE_KELUAR => 'Kas Keluar',
        ];
    }

    // Relasi
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function sedekahSampah()
    {
        return $this->belongsTo(SedekahSampah::class);
    }

    // Accessor
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    // Method untuk mendapatkan saldo terkini
    public static function getCurrentSaldo()
    {
        $latestKas = self::latest()->first();
        return $latestKas ? $latestKas->saldo_sesudah : 0;
    }
}