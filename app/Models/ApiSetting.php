<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiSetting extends Model
{
    protected $fillable = ['setting_key', 'setting_value', 'setting_group', 'description'];

    /**
     * Get a setting value by key
     */
    public static function getValue(string $key, $default = null): ?string
    {
        $setting = static::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function setValue(string $key, string $value): void
    {
        static::where('setting_key', $key)->update(['setting_value' => $value]);
    }

    /**
     * Get all settings grouped
     */
    public static function getGrouped(): array
    {
        return static::all()->groupBy('setting_group')->toArray();
    }
}
