<?php

use App\Models\Settings;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'sidebar_ad_active', 'value' => '0', 'type' => 'boolean', 'group' => 'ads'],
            ['key' => 'sidebar_ad_title', 'value' => 'محل تبلیغات شما', 'type' => 'text', 'group' => 'ads'],
            ['key' => 'sidebar_ad_image', 'value' => null, 'type' => 'image', 'group' => 'ads'],
            ['key' => 'sidebar_ad_link', 'value' => null, 'type' => 'text', 'group' => 'ads'],
        ];

        foreach ($settings as $setting) {
            Settings::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

    public function down(): void
    {
        Settings::whereIn('key', [
            'sidebar_ad_active',
            'sidebar_ad_title',
            'sidebar_ad_image',
            'sidebar_ad_link',
        ])->delete();
    }
};
