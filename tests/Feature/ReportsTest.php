<?php

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Morilog\Jalali\Jalalian;

uses(RefreshDatabase::class);

function makeUser(string $phone = '09120000000'): User
{
    return User::factory()->create([
        'phone' => $phone,
    ]);
}

function jalaliToday(): string
{
    return Jalalian::now()->format('Y/m/d');
}

test('reports page loads with only a single task', function () {
    $user = makeUser();

    // آبزرور کاربر یک تسک (امروز) و یک دسته‌بندی می‌سازد؛ همین یک تسک کافی است.
    expect(Task::where('user_id', $user->id)->count())->toBe(1);

    $response = $this->actingAs($user)->get('/reports');

    $response->assertStatus(200);
    $response->assertSee('گزارش عملکرد');
    $response->assertSee('کل تسک‌ها');
    $response->assertSee('تقویم فعالیت ماه');
    $response->assertSee('عملکرد هفتگی');
    $response->assertSee('عملکرد بر اساس دسته‌بندی');
    $response->assertSee('ساعات تمرکز');

    // با کمتر از ۳ تسک، به‌جای دونات راهنمای سه‌تایی نمایش داده می‌شود.
    $response->assertSee('برای نمایش نمودار، حداقل ۳ تسک لازم است.');
    $response->assertDontSee('conic-gradient', false);
    $response->assertSee('هنوز داده‌ی کافی نیست');

    // عدد فارسی
    $response->assertSee('۱');
});

test('reports page loads with many tasks and renders all sections', function () {
    $user = makeUser('09120000001');
    $category = Category::where('user_id', $user->id)->first();

    $today = jalaliToday();

    for ($i = 0; $i < 9; $i++) {
        Task::create([
            'user_id' => $user->id,
            'category_id' => $category?->id,
            'title' => 'تسک شماره '.$i,
            'due_date' => $today,
            'status' => $i < 6 ? 'completed' : 'pending',
        ]);
    }

    $response = $this->actingAs($user)->get('/reports');

    $response->assertStatus(200);
    $response->assertSee('گزارش عملکرد');
    $response->assertSee('کل تسک‌ها');
    $response->assertSee('تقویم فعالیت ماه');
    $response->assertSee('عملکرد هفتگی');
    $response->assertSee('عملکرد بر اساس دسته‌بندی');
    $response->assertSee('ساعات تمرکز');

    // با ۳ تسک یا بیشتر، دونات CSS ساخته می‌شود.
    $response->assertSee('conic-gradient', false);

    // تقویم حرارتی
    $response->assertSee('bg-slate-100');

    // کارت دسته‌بندی (دسته‌ی پیش‌فرض «مطالعه» توسط آبزرور ساخته می‌شود)
    $response->assertSee('مطالعه');

    // pill اوج ساعات تمرکز
    $response->assertSee('اوج:');

    // درصد فارسی
    $response->assertSee('٪');

    // نوار بینش در حالت داده‌ی کافی
    $response->assertSee('بیشترین فعالیتت');
    $response->assertSee('پربازده‌ترین روزهایت هستند.');
});

test('dashboard displays persian digits', function () {
    $user = makeUser('09120000002');

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('٪');
    $response->assertSee('تسک');
});
