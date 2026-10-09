<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Get a setting value with caching and safe casting.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::allCached();

        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        return $default;
    }

    /**
     * Set a setting value, persisting to database and clearing cache.
     */
    public static function set(string $key, mixed $value, ?string $type = null, ?string $group = null): self
    {
        $setting = static::firstOrNew(['key' => $key]);

        if ($type !== null) {
            $setting->type = $type;
        }

        if ($group !== null) {
            $setting->group = $group;
        }

        // Store booleans cleanly as '1' or '0'
        if ($setting->type === 'boolean') {
            $setting->value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        } elseif (is_array($value) || is_object($value)) {
            $setting->type = 'json';
            $setting->value = json_encode($value);
        } else {
            $setting->value = $value !== null ? (string) $value : null;
        }

        $setting->save();
        static::clearCache();

        return $setting;
    }

    /**
     * Retrieve all settings cached as an associative key => cast value array.
     */
    public static function allCached(): array
    {
        return Cache::rememberForever('app_settings_map', function () {
            $settings = static::all();
            $map = [];

            foreach ($settings as $setting) {
                $map[$setting->key] = $setting->castValue();
            }

            return $map;
        });
    }

    /**
     * Get all settings grouped by their group name with cast values and raw models.
     */
    public static function getAllGrouped(): array
    {
        $settings = static::all();
        $grouped = [];

        foreach ($settings as $setting) {
            $grouped[$setting->group][$setting->key] = [
                'key' => $setting->key,
                'value' => $setting->castValue(),
                'raw_value' => $setting->value,
                'type' => $setting->type,
                'group' => $setting->group,
            ];
        }

        return $grouped;
    }

    /**
     * Cast the raw value according to the setting type.
     */
    public function castValue(): mixed
    {
        if ($this->value === null) {
            return null;
        }

        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json', 'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Clear the cached settings map.
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings_map');
    }
}
