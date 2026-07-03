<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    protected $fillable =['key','value','type','group'];
    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget('setting_' . $setting->key);
        });

        static::deleted(function ($setting) {
            Cache::forget('setting_' . $setting->key);
        });
    }
}
