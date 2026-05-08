<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalPengepul extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pengepul';

    protected $fillable = [
        'pengepul_id', 'hari', 'waktu_mulai', 'waktu_selesai', 'lokasi', 'catatan',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime', 'waktu_selesai' => 'datetime',
    ];

    public function pengepul()
    {
        return $this->belongsTo(User::class, 'pengepul_id');
    }

    public function penjemputan()
    {
        return $this->hasMany(Penjemputan::class, 'jadwal_pengepul_id');
    }

    public function getStatusColorAttribute()
    {
        return 'success'; // Selalu tersedia karena tidak ada kapasitas limit
    }

    public function getStatusLabelAttribute()
    {
        return 'Tersedia'; // Selalu tersedia karena tidak ada kapasitas limit
    }

    public function getHariLabelAttribute()
    {
        return match($this->hari) {
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
            default => ucfirst($this->hari),
        };
    }

    public function isAvailable()
    {
        return true; // Selalu tersedia karena tidak ada kapasitas limit
    }

    public function incrementKapasitas()
    {
        // Method ini tidak digunakan lagi karena tidak ada kapasitas limit
        return true;
    }

    public function decrementKapasitas()
    {
        // Method ini tidak digunakan lagi karena tidak ada kapasitas limit
        return true;
    }

    // Helper method untuk mendapatkan tanggal berdasarkan hari
    public function getTanggalForHari($targetHari = null)
    {
        $hari = $targetHari ?? $this->hari;
        $hariMap = [
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
            'minggu' => 0,
        ];

        $targetDayOfWeek = $hariMap[$hari];
        $today = Carbon::today();
        $currentDayOfWeek = $today->dayOfWeek;

        // Hitung berapa hari ke depan untuk mencapai hari target
        $daysToAdd = ($targetDayOfWeek - $currentDayOfWeek + 7) % 7;
        
        // Jika hari ini adalah hari target, gunakan hari ini
        if ($daysToAdd === 0) {
            return $today;
        }

        return $today->addDays($daysToAdd);
    }
} 