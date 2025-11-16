<?php
// app/Models/Setting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key', 'value', 'type', 'group', 'description'
    ];

    protected static $cache = null;

    public static function getValue($key, $default = null)
    {
        if (self::$cache === null) {
            self::$cache = Cache::remember('app_settings', 3600, function () {
                return self::all()->pluck('value', 'key')->toArray();
            });
        }
        
        return self::$cache[$key] ?? $default;
    }

    public static function setValue($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        // Clear cache
        self::clearCache();
        
        return $setting;
    }

    public static function getAllSettings()
    {
        return Cache::remember('app_settings', 3600, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function clearCache()
    {
        Cache::forget('app_settings');
        self::$cache = null;
    }
}