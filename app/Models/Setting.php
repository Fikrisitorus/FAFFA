<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'label',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    // Type constants
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_JSON = 'json';
    const TYPE_TEXT = 'text';

    public static function getTypeOptions()
    {
        return [
            self::TYPE_STRING => 'String',
            self::TYPE_NUMBER => 'Number',
            self::TYPE_BOOLEAN => 'Boolean',
            self::TYPE_JSON => 'JSON',
            self::TYPE_TEXT => 'Text',
        ];
    }

    // Helper method untuk get setting
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            self::TYPE_BOOLEAN => (bool) $setting->value,
            self::TYPE_NUMBER => (float) $setting->value,
            self::TYPE_JSON => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    // Helper method untuk set setting
    public static function set($key, $value, $type = self::TYPE_STRING)
    {
        $processedValue = match($type) {
            self::TYPE_BOOLEAN => $value ? '1' : '0',
            self::TYPE_JSON => json_encode($value),
            default => (string) $value,
        };

        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $processedValue,
                'type' => $type,
            ]
        );
    }
}