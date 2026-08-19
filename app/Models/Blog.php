<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title','description','image','summary','featured','category_id'];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function getReadingTimeAttribute()
    {
        // ۱. اگر متن خالی بود، 1 دقیقه برگردان
        if (!$this->description) {
            return 1;
        }

        // ۲. حذف کدهای HTML از متن (تا تگ‌ها به عنوان کلمه شمرده نشوند)
        $cleanText = strip_tags($this->description);

        // ۳. شمارش دقیق کلمات با پشتیبانی کامل از زبان‌های فارسی، عربی و انگلیسی (UTF-8)
        $wordCount = count(preg_split('~[^\p{L}\p{N}\']+~u', $cleanText));

        // ۴. سرعت متوسط خواندن (200 کلمه در دقیقه)
        $wordsPerMinute = 200;

        // ۵. محاسبه زمان و گرد کردن آن به سمت بالا (مثلا 1.2 دقیقه می‌شود 2 دقیقه)
        $minutes = ceil($wordCount / $wordsPerMinute);

        // حداقل زمان مطالعه را 1 دقیقه در نظر می‌گیریم
        return $minutes > 0 ? $minutes : 1;
    }
}
