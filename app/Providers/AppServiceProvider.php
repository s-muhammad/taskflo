<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // دایرکتیو نمایشی برای تبدیل ارقام لاتین به فارسی (فقط نمایش)
        Blade::directive('fa', function ($expression) {
            return "<?php echo e(fa_digits($expression)); ?>";
        });

        // این کد به لاراول میگه در محیط پروداکشن همه چیز رو HTTPS کن
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
