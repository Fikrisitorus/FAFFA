<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjemputanSampahDetail extends Model
{
    use HasFactory;

    protected $table = 'penjemputan_sampah_details';

    protected $fillable = [
        'penjemputan_id',
        'jenis_sampah_id',
        'berat_nasabah',
        'berat_verifikasi',
        'catatan',
        'gambar',
    ];

    protected function casts(): array
    {
        return [
            'berat_nasabah' => 'decimal:2',
            'berat_verifikasi' => 'decimal:2',
        ];
    }

    // Relasi
    public function penjemputan()
    {
        return $this->belongsTo(Penjemputan::class);
    }

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class);
    }

    // Accessor untuk format harga
    public function getFormattedHargaPerKgAttribute()
    {
        $hargaPerKg = $this->jenisSampah?->harga ?? 0;
        return 'Rp ' . number_format($hargaPerKg, 0, ',', '.');
    }

    public function getFormattedTotalHargaAttribute()
    {
        $totalHarga = $this->berat * ($this->jenisSampah?->harga ?? 0);
        return 'Rp ' . number_format($totalHarga, 0, ',', '.');
    }

    // Method untuk mendapatkan harga per kg dari relasi
    public function getHargaPerKgAttribute()
    {
        return $this->jenisSampah?->harga ?? 0;
    }

    // Method untuk mendapatkan total harga dari perhitungan (menggunakan berat final)
    public function getTotalHargaAttribute()
    {
        return $this->berat * ($this->jenisSampah?->harga ?? 0);
    }

    // Method untuk mendapatkan berat final (berat_verifikasi jika ada, jika tidak berat_nasabah)
    public function getBeratAttribute()
    {
        // Untuk dashboard kelompok, selalu tampilkan berat_nasabah
        if (request()->routeIs('filament.admin.pages.dashboard-kelompok')) {
            return $this->getAttributes()['berat_nasabah'] ?? 0;
        }
        
        // Untuk halaman lain, gunakan berat_verifikasi jika ada
        if (($this->getAttributes()['berat_verifikasi'] ?? 0) > 0) {
            return $this->getAttributes()['berat_verifikasi'];
        }
        
        // Jika tidak, gunakan berat_nasabah
        return $this->getAttributes()['berat_nasabah'] ?? 0;
    }

    // Method khusus untuk mendapatkan berat nasabah (menggunakan getAttributes untuk menghindari konflik)
    public function getBeratNasabahAttribute()
    {
        return $this->getAttributes()['berat_nasabah'] ?? 0;
    }

    // Method khusus untuk mendapatkan berat verifikasi (menggunakan getAttributes untuk menghindari konflik)
    public function getBeratVerifikasiAttribute()
    {
        return $this->getAttributes()['berat_verifikasi'] ?? 0;
    }
}
