<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjemputan extends Model
{
    use HasFactory;

    protected $table = 'penjemputan';

    protected $fillable = [
        'nasabah_id',
        'kelompok_id',
        'pengepul_id',
        'jadwal_pengepul_id',
        'tanggal_penjemputan',
        'waktu_penjemputan',
        'jadwal_penjemputan',
        'alamat_penjemputan',
        'catatan',
        'gambar',
        'status',
        'is_sorted',
        'is_request_khusus',
        'waktu_diambil',
        'waktu_mulai',
        'waktu_selesai',
        'payment_option',
        'donation_amount',
        'nasabah_amount',
        'waktu_dibatalkan',
        'estimasi_berat',
        'berat_final',
        'weight_status',
        'self_weighted',
        'berat_difference',
        'weight_notes',
        'midtrans_order_id',
        'payment_status',
        'payment_method',
        'payment_time',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penjemputan' => 'date',
            'waktu_penjemputan' => 'datetime',
            'jadwal_penjemputan' => 'datetime',
            'waktu_diambil' => 'datetime',
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'waktu_dibatalkan' => 'datetime',
            'is_sorted' => 'boolean',
            'is_request_khusus' => 'boolean',
            'self_weighted' => 'boolean',
            'payment_time' => 'datetime',
            'total_amount' => 'decimal:2',
            'estimasi_berat' => 'decimal:2',
            'berat_final' => 'decimal:2',
            'berat_difference' => 'decimal:2',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_ON_PROGRESS = 'on_progress';
    const STATUS_WEIGHT_VERIFIED = 'weight_verified';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ASSIGNED => 'Diambil',
            self::STATUS_ON_PROGRESS => 'Sedang Diproses',
            self::STATUS_WEIGHT_VERIFIED => 'Berat Terverifikasi',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    // Relasi
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function pengepul()
    {
        return $this->belongsTo(User::class, 'pengepul_id');
    }

    public function jadwalPengepul()
    {
        return $this->belongsTo(JadwalPengepul::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function sampahDetails()
    {
        return $this->hasMany(PenjemputanSampahDetail::class);
    }

    // Scope
    public function scopeRequestKhusus($query)
    {
        return $query->where('is_request_khusus', true);
    }

    public function scopeJadwalRutin($query)
    {
        return $query->where('is_request_khusus', false);
    }
}