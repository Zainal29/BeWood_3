<?php
namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public static function getAll()
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public static function update(array $data)
    {
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
