<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'nasabah_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    // Type constants
    const TYPE_TRANSAKSI = 'transaksi';
    const TYPE_PENJEMPUTAN = 'penjemputan';
    const TYPE_SEDEKAH = 'sedekah';
    const TYPE_SYSTEM = 'system';

    public static function getTypeOptions()
    {
        return [
            self::TYPE_TRANSAKSI => 'Transaksi',
            self::TYPE_PENJEMPUTAN => 'Penjemputan',
            self::TYPE_SEDEKAH => 'Sedekah Sampah',
            self::TYPE_SYSTEM => 'System',
        ];
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    // Scope
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    // Method untuk mark as read
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}

