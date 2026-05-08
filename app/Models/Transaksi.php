<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'nasabah_id',
        'pengepul_id',
        'penjemputan_id',
        'jenis_sampah_id',
        'berat',
        'total_harga',
        'tanggal_transaksi',
        'catatan',
        'sistem',
        'nasabah',
        'gambar_bukti_nasabah',
        'gambar_bukti_sistem',
        'bukti_pembayaran',
        'verified_by_nasabah',
        'verified_by_admin',
        'verified_at_nasabah',
        'verified_at_admin',
        'status',
        'alasan_penolakan',
    ];

    protected function casts(): array
    {
        return [
            'berat' => 'decimal:2',
            'total_harga' => 'decimal:2',
            'tanggal_transaksi' => 'datetime',
            'sistem' => 'boolean',
            'nasabah' => 'boolean',
            'verified_by_nasabah' => 'integer',
            'verified_by_admin' => 'integer',
            'verified_at_nasabah' => 'datetime',
            'verified_at_admin' => 'datetime',
        ];
    }

    // Relasi
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function pengepul()
    {
        return $this->belongsTo(User::class, 'pengepul_id');
    }

    public function penjemputan()
    {
        return $this->belongsTo(Penjemputan::class);
    }

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class);
    }


    // Accessor
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

    // Method untuk mendapatkan total harga dari database (jika ada) atau perhitungan
    public function getTotalHargaAttribute()
    {
        // Jika ada nilai di database, gunakan itu (untuk proporsi)
        if (isset($this->attributes['total_harga']) && $this->attributes['total_harga'] > 0) {
            return $this->attributes['total_harga'];
        }
        
        // Jika tidak ada, hitung dari berat * harga per kg
        return $this->berat * ($this->jenisSampah?->harga ?? 0);
    }

    // Scope untuk query berdasarkan tujuan transaksi
    public function scopeToNasabah($query)
    {
        return $query->where('nasabah', true);
    }

    public function scopeToSistem($query)
    {
        return $query->where('sistem', true);
    }

    // Method untuk mendapatkan tipe transaksi
    public function getTipeTransaksiAttribute()
    {
        if ($this->sistem && $this->nasabah) {
            return 'split';
        } elseif ($this->sistem) {
            return 'sistem';
        } elseif ($this->nasabah) {
            return 'nasabah';
        }
        return 'unknown';
    }

    // Method untuk mendapatkan label tipe transaksi
    public function getTipeTransaksiLabelAttribute()
    {
        return match($this->tipe_transaksi) {
            'split' => 'Split (Nasabah + Sistem)',
            'sistem' => 'Sistem (Sedekah)',
            'nasabah' => 'Nasabah',
            default => 'Unknown'
        };
    }

    // Method untuk verifikasi transaksi sistem (menggunakan verified_by_admin)
    public function verify($userId)
    {
        $this->update([
            'verified_by_admin' => 1,
            'verified_at_admin' => now(),
            'status' => 1, // 1 = verified
        ]);
    }
    
    // Accessor untuk status verifikasi sistem (cek dari verified_by_admin)
    public function getStatusVerifikasiAttribute()
    {
        return $this->attributes['verified_by_admin'] ? 'Verified' : 'Pending';
    }

    // Method untuk verifikasi nasabah
    public function verifyByNasabah($nasabahId)
    {
        $this->update([
            'verified_by_nasabah' => 1,
            'verified_at_nasabah' => now(),
        ]);
    }

    // Method untuk verifikasi admin
    public function verifyByAdmin($adminId)
    {
        $this->update([
            'verified_by_admin' => 1,
            'verified_at_admin' => now(),
            'status' => 1, // 1 = verified
        ]);
    }

    // Method untuk menolak transaksi
    public function reject($adminId, $alasanPenolakan)
    {
        $this->update([
            'status' => 99, // 99 = rejected
            'alasan_penolakan' => $alasanPenolakan,
        ]);
    }

    // Accessor untuk status verifikasi nasabah
    public function getStatusVerifikasiNasabahAttribute()
    {
        return $this->attributes['verified_by_nasabah'] ? 'Verified' : 'Pending';
    }

    // Accessor untuk status verifikasi admin
    public function getStatusVerifikasiAdminAttribute()
    {
        return $this->attributes['verified_by_admin'] ? 'Verified' : 'Pending';
    }
}