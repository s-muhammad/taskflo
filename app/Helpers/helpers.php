<?php

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * گرفتن مقدار تنظیمات بر اساس کلید با کش کردن خودکار
     *
     * @param string $key
     * @param mixed $default
     * @param int $ttl (زمان کش بر حسب ثانیه، پیش‌فرض ۱ ساعت)
     * @return mixed
     */
    function setting(string $key, $default = null, int $ttl = 3600)
    {
        // کلید یکتا برای کش
        $cacheKey = 'setting_' . $key;

        // اگر کش وجود داشت برگردون، وگرنه از دیتابیس بخون و کش کن
        return Cache::remember($cacheKey, $ttl, function () use ($key, $default) {
            $setting = Settings::where('key', $key)->first();
            return $setting->value ?? $default;
        });
    }
}

if (!function_exists('fa_digits')) {
    /**
     * تبدیل ارقام لاتین به ارقام فارسی فقط برای نمایش.
     * روی مقدار فیلدهای فرم، کیوری‌استرینگ، دیتای JS و محاسبات اثری ندارد.
     */
    function fa_digits($value): string
    {
        if ($value === null) {
            return '';
        }

        return str_replace(
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            (string) $value
        );
    }
}
