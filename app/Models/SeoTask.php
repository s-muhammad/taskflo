<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoTask extends Model
{
    protected $fillable = [
        'title', 'description', 'priority', 'status',
        'due_date', 'seo_keyword_id', 'related_url', 'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public const PRIORITIES = ['low', 'medium', 'high'];

    public const PRIORITY_LABELS = [
        'low' => 'کم',
        'medium' => 'متوسط',
        'high' => 'زیاد',
    ];

    public const STATUSES = ['todo', 'in_progress', 'done'];

    public const STATUS_LABELS = [
        'todo' => 'انجام نشده',
        'in_progress' => 'در حال انجام',
        'done' => 'انجام شده',
    ];

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(SeoKeyword::class, 'seo_keyword_id');
    }
}
