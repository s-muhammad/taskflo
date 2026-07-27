<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Task extends Model
{
    protected $fillable =['user_id','category_id','title','due_date','status','time','description','reminder','reminder_sent_at',];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected function dueDate() : Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (!$value) return null;

                return Jalalian::fromFormat('Y/m/d', $value)->toCarbon();
            },
//            get: fn ($value) => $value,
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
