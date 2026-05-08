<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'kategori',
        'nama_pengeluaran',
        'deskripsi',
        'catatan_pengeluaran',
        'penerima_pengeluaran',
        'lokasi_pengeluaran',
        'jumlah',
        'tanggal_pengeluaran',
        'status',
        'approved_by',
        'approved_at',
        'catatan_approval',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_pengeluaran' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    // Kategori constants
    const KATEGORI_OPERASIONAL = 'operasional';
    const KATEGORI_MAINTENANCE = 'maintenance';
    const KATEGORI_GAJI = 'gaji';
    const KATEGORI_TRANSPORT = 'transport';
    const KATEGORI_LAINNYA = 'lainnya';

    public static function getKategoriOptions()
    {
        return [
            self::KATEGORI_OPERASIONAL => 'Operasional',
            self::KATEGORI_MAINTENANCE => 'Maintenance',
            self::KATEGORI_GAJI => 'Gaji',
            self::KATEGORI_TRANSPORT => 'Transport',
            self::KATEGORI_LAINNYA => 'Lainnya',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    // Lokasi constants (opsional)
    const LOKASI_KANTOR = 'kantor';
    const LOKASI_LAPANGAN = 'lapangan';
    const LOKASI_LAINNYA = 'lainnya';

    public static function getLokasiOptions()
    {
        return [
            self::LOKASI_KANTOR => 'Kantor',
            self::LOKASI_LAPANGAN => 'Lapangan',
            self::LOKASI_LAINNYA => 'Lainnya',
        ];
    }

    // Relasi
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessor
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_PENDING => 'warning',
            self::STATUS_REJECTED => 'danger',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? 'Unknown';
    }

    public function getKategoriLabelAttribute()
    {
        return self::getKategoriOptions()[$this->kategori] ?? 'Unknown';
    }

    public function getLokasiLabelAttribute()
    {
        return self::getLokasiOptions()[$this->lokasi_pengeluaran] ?? 'Unknown';
    }
}