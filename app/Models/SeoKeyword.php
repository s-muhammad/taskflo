<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeoKeyword extends Model
{
    protected $fillable = [
        'keyword', 'search_volume', 'difficulty', 'intent',
        'status', 'target_url', 'current_rank', 'notes',
    ];

    protected $casts = [
        'search_volume' => 'integer',
        'current_rank' => 'integer',
    ];

    public const DIFFICULTIES = ['low', 'medium', 'high'];

    public const DIFFICULTY_LABELS = [
        'low' => 'کم',
        'medium' => 'متوسط',
        'high' => 'زیاد',
    ];

    public const INTENTS = ['informational', 'commercial', 'transactional', 'navigational'];

    public const INTENT_LABELS = [
        'informational' => 'اطلاعاتی',
        'commercial' => 'تجاری',
        'transactional' => 'تراکنشی',
        'navigational' => 'ناوبری',
    ];

    public const STATUSES = ['idea', 'planned', 'writing', 'published', 'needs_improvement'];

    public const STATUS_LABELS = [
        'idea' => 'ایده',
        'planned' => 'برنامه‌ریزی شده',
        'writing' => 'در حال نگارش',
        'published' => 'منتشر شده',
        'needs_improvement' => 'نیاز به بهبود',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(SeoTask::class);
    }
}
