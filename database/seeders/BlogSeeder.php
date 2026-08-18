<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        Blog::updateOrCreate(
            ['title' => 'با تسک فلو آشنا شوید: ابزار هوشمند مدیریت زمان و بهره‌وری'],
            [
                'summary' => 'تسک فلو یک ابزار مدرن و فارسی برای مدیریت تسک‌ها، برنامه‌ریزی روزانه و مشاهده نمودار عملکرد است. در این مقاله با تمام امکانات آن آشنا می‌شوید.',
                'image' => 'uploads/blog/intro.svg',
                'featured' => true,
                'description' => $this->getArticleContent(),
            ]
        );
    }

    private function getArticleContent(): string
    {
        return <<<'HTML'
<div style="line-height:2; font-size:15px; color:#374151;">

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-bottom:12px;">چرا به یک ابزار مدیریت تسک نیاز داریم؟</h2>
<p style="margin-bottom:18px;">هر روز صبح که از خواب بیدار می‌شویم، لیستی از کارها و وظایف در ذهن ما شکل می‌گیرد. از ارسال ایمیل مهم گرفته تا پروژه‌های بزرگ، همه نیاز به مدیریت دقیق دارند. اما وقتی کارها فقط در ذهن ما می‌مانند، خیلی زود فراموش می‌شوند یا اولویت‌بندیشان به هم می‌ریزد.</p>
<p style="margin-bottom:18px;"><strong style="color:#4f46e5;">تسک فلو</strong> دقیقاً برای همین مشکل ساخته شده: یک محیط ساده، زیبا و فارسی که به شما کمک می‌کند تمام کارهایتان را سازماندهی کنید، اولویت‌بندی کنید و عملکردتان را رصد کنید.</p>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:32px; margin-bottom:12px;">معرفی تسک فلو</h2>
<p style="margin-bottom:18px;">تسک فلو یک اپلیکیشن وب فارسی است که با جدیدترین تکنولوژی‌های وب ساخته شده و کاملاً به زبان فارسی و با پشتیبانی کامل از تقویم شمسی طراحی شده است. این ابزار برای فردی، تیم‌های کوچک و هر کسی که می‌خواهد بهره‌وری خود را افزایش دهد مناسب است.</p>

<div style="background:linear-gradient(135deg, #eef2ff, #e0e7ff); border:1px solid #c7d2fe; border-radius:16px; padding:20px 24px; margin:20px 0;">
<p style="font-weight:700; color:#4f46e5; margin-bottom:8px;">ویژگی‌های کلیدی تسک فلو:</p>
<ul style="padding-right:20px; margin:0;">
<li style="margin-bottom:6px;">مدیریت هوشمند تسک‌ها با اولویت‌بندی و دسته‌بندی</li>
<li style="margin-bottom:6px;">تقویم ماهانه شمسی برای برنامه‌ریزی دقیق</li>
<li style="margin-bottom:6px;">نمودارها و گزارش‌های گرافیکی عملکرد</li>
<li style="margin-bottom:6px;">یادآوری هوشمند با نوتیفیکیشن مرورگر</li>
<li style="margin-bottom:6px;">سیستم پشتیبانی و تیکتینگ</li>
<li style="margin-bottom:6px;">طراحی کاملاً ریسپانسیو و واکنش‌گرا</li>
</ul>
</div>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">داشبورد هوشمند</h2>
<p style="margin-bottom:18px;">اولین چیزی که پس از ورود به تسک فلو می‌بینید، یک داشبورد جامع و زیبا است. در این صفحه:</p>
<ul style="padding-right:20px; margin-bottom:18px;">
<li style="margin-bottom:8px;"><strong style="color:#4f46e5;">کارت‌های آماری:</strong> تعداد کل تسک‌ها، تسک‌های انجام شده، تسک‌های باقی‌مانده و درصد بهره‌وری شما به صورت حلقه پیشرفت نمایش داده می‌شود.</li>
<li style="margin-bottom:8px;"><strong style="color:#4f46e5;">برنامه امروز:</strong> تسک‌هایی که امروز مهلت انجام دارند با چک‌باکس تعاملی نمایش داده می‌شوند. می‌توانید هر تسک را مستقیماً از داشبورد تیک بزنید.</li>
<li style="margin-bottom:8px;"><strong style="color:#4f46e5;">نمودار بار هفتگی:</strong> بار کاری ۷ روز آینده خود را به صورت نمودار میله‌ای ببینید و برای روزهای شلوغ از قبل آماده شوید.</li>
</ul>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">مدیریت تسک‌ها</h2>
<p style="margin-bottom:18px;">قلب اصلی تسک فلو، صفحه مدیریت تسک‌هاست. این صفحه با طراحی دو پنلی (نوار کناری فیلترها + لیست اصلی) طراحی شده و امکانات زیر را ارائه می‌دهد:</p>

<h3 style="font-size:18px; font-weight:700; color:#312e81; margin-top:24px; margin-bottom:10px;">فیلترهای هوشمند</h3>
<p style="margin-bottom:18px;">در نوار کناری می‌توانید تسک‌ها را بر اساس <strong>همه</strong>، <strong>امروز</strong>، <strong>این هفته</strong> یا <strong>دسته‌بندی</strong> فیلتر کنید. هر فیلتر تعداد تسک‌های مربوطه را به صورت زنده نمایش می‌دهد.</p>

<h3 style="font-size:18px; font-weight:700; color:#312e81; margin-top:24px; margin-bottom:10px;">دسته‌بندی رنگی</h3>
<p style="margin-bottom:18px;">برای تسک‌هایتان دسته‌بندی بسازید و به هر کدام یکی از ۱۸ رنگ موجود را اختصاص دهید. این دسته‌بندی‌ها در تمام بخش‌های اپلیکیشن از جمله تقویم و نمودارها قابل مشاهده هستند.</p>

<h3 style="font-size:18px; font-weight:700; color:#312e81; margin-top:24px; margin-bottom:10px;">سه بخش اصلی لیست</h3>
<ul style="padding-right:20px; margin-bottom:18px;">
<li style="margin-bottom:8px;"><strong style="color:#ef4444;">تسک‌های سررسید گذشته:</strong> کارهایی که مهلتشان رد شده با رنگ قرمز مشخص می‌شوند تا فوراً توجه شما را جلب کنند.</li>
<li style="margin-bottom:8px;"><strong style="color:#f59e0b;">تسک‌های در انتظار:</strong> کارهای از امروز تا پایان ماه که در انتظار انجام هستند.</li>
<li style="margin-bottom:8px;"><strong style="color:#10b981;">تسک‌های انجام شده:</strong> کارهای تکمیل شده با خط خوردن متن و شفافیت کمتر نمایش داده می‌شوند.</li>
</ul>

<h3 style="font-size:18px; font-weight:700; color:#312e81; margin-top:24px; margin-bottom:10px;">ایجاد تسک سریع</h3>
<p style="margin-bottom:18px;">نوار جستجو در بالای لیست تسک‌ها، علاوه بر فیلتر، امکان اضافه کردن سریع تسک جدید را نیز فراهم می‌کند. کافیست عنوان تسک را تایپ کنید و Enter بزنید.</p>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">فرم جامع ایجاد تسک</h2>
<p style="margin-bottom:18px;">برای تسک‌های پیچیده‌تر، فرم اختصاصی ایجاد تسک شامل موارد زیر است:</p>
<ul style="padding-right:20px; margin-bottom:18px;">
<li style="margin-bottom:8px;">عنوان و توضیحات تسک</li>
<li style="margin-bottom:8px;">انتخاب تاریخ از <strong>تقویم شمسی تعاملی</strong> با قابلیت پیمایش ماه‌ها</li>
<li style="margin-bottom:8px;">تنظیم ساعت شروع و پایان</li>
<li style="margin-bottom:8px;">انتخاب دسته‌بندی رنگی</li>
<li style="margin-bottom:8px;"><strong>تکرار خودکار:</strong> روزانه، روزهای زوج یا روزهای فرد ماه</li>
<li style="margin-bottom:8px;">فعال‌سازی <strong>یادآوری نوتیفیکیشن</strong></li>
</ul>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">تقویم ماهانه شمسی</h2>
<p style="margin-bottom:18px;">یکی از جذاب‌ترین بخش‌های تسک فلو، تقویم ماهانه با پشتیبانی کامل از تقویم شمسی است. در این صفحه:</p>
<ul style="padding-right:20px; margin-bottom:18px;">
<li style="margin-bottom:8px;">روزهای ماه به صورت کارت نمایش داده می‌شوند</li>
<li style="margin-bottom:8px;">تسک‌های هر روز با رنگ دسته‌بندی مشخص هستند</li>
<li style="margin-bottom:8px;">امروز با حاشیه بنفش و نقطه درخشان مشخص شده</li>
<li style="margin-bottom:8px;">می‌توانید مستقیماً از روی تقویم تسک‌ها را تیک بزنید</li>
<li style="margin-bottom:8px;">دکمه "+" روی هر روز برای اضافه کردن تسک جدید</li>
<li style="margin-bottom:8px;">پیمایش بین ماه‌ها با دکمه‌های قبلی/بعدی</li>
</ul>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">گزارش‌ها و نمودارها</h2>
<p style="margin-bottom:18px;">صفحه گزارش‌ها یک داشبورد تحلیلی کامل است که عملکرد شما را از زوایای مختلف نمایش می‌دهد:</p>

<div style="background:linear-gradient(135deg, #f0fdf4, #dcfce7); border:1px solid #bbf7d0; border-radius:16px; padding:20px 24px; margin:20px 0;">
<p style="font-weight:700; color:#16a34a; margin-bottom:10px;">۶ نمودار تعاملی:</p>
<ol style="padding-right:20px; margin:0;">
<li style="margin-bottom:6px;"><strong>روند ۳۰ روز اخیر:</strong> نمودار خطی نشان‌دهنده تعداد تسک‌های تکمیل شده در هر روز</li>
<li style="margin-bottom:6px;"><strong>نمودار دونات وضعیت:</strong> نسبت تسک‌های انجام شده، در انتظار و سررسید گذشته</li>
<li style="margin-bottom:6px;"><strong>عملکرد هفتگی:</strong> نمودار میله‌ای عملکرد روزهای هفته</li>
<li style="margin-bottom:6px;"><strong>توزیع زمانی:</strong> نمودار راداری نشان‌دهنده ساعات اوج فعالیت (صبح، بعدازظهر، شب، نیمه‌شب)</li>
<li style="margin-bottom:6px;"><strong>خط زمانی امروز:</strong> نمودار افقی تسک‌های امروز بر اساس ساعت</li>
<li style="margin-bottom:6px;"><strong>عملکرد دسته‌بندی‌ها:</strong> مقایسه کل تسک‌ها و تسک‌های تکمیل شده در هر دسته‌بندی</li>
</ol>
</div>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">یادآوری هوشمند</h2>
<p style="margin-bottom:18px;">هیچ کار مهمی را فراموش نکنید! تسک فلو با استفاده از نوتیفیکیشن‌های مرورگر، یادآوری‌های هوشمندی برای شما ارسال می‌کند:</p>
<ul style="padding-right:20px; margin-bottom:18px;">
<li style="margin-bottom:8px;">برای تسک‌هایی که ساعت مشخصی دارند، <strong>۵ دقیقه قبل</strong> از موعد یادآوری ارسال می‌شود</li>
<li style="margin-bottom:8px;">برای تسک‌های بدون ساعت، راس ساعت <strong>۸ صبح</strong> یادآوری دریافت می‌کنید</li>
<li style="margin-bottom:8px;">فقط کافیست اعلان‌های مرورگر را فعال کنید</li>
</ul>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">سیستم پشتیبانی</h2>
<p style="margin-bottom:18px;">مرکز پشتیبانی تسک فلو شامل بخش‌های زیر است:</p>
<ul style="padding-right:20px; margin-bottom:18px;">
<li style="margin-bottom:8px;"><strong>آموزش‌های سریع:</strong> راهنمای ایجاد تسک، مدیریت تقویم و استفاده از نمودارها</li>
<li style="margin-bottom:8px;"><strong>نکات حرفه‌ای:</strong> مقالاتی درباره افزایش بهره‌وری، تکنیک پومودورو و گزارش‌گیری پیشرفته</li>
<li style="margin-bottom:8px;"><strong>ویدیوهای آموزشی:</strong> آموزش‌های تصویری قدم به قدم</li>
<li style="margin-bottom:8px;"><strong>سیستم تیکت:</strong> ارسال تیکت پشتیبانی و دریافت پاسخ از تیم پشتیبانی</li>
</ul>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">امنیت و حریم خصوصی</h2>
<p style="margin-bottom:18px;">در تسک فلو امنیت شما جدی گرفته می‌شود:</p>
<ul style="padding-right:20px; margin-bottom:18px;">
<li style="margin-bottom:8px;">احراز هویت با <strong>شماره تلفن و رمز عبور</strong></li>
<li style="margin-bottom:8px;">ارسال <strong>کد تأیید OTP</strong> از طریق پیامک</li>
<li style="margin-bottom:8px;">امکان <strong>بازیابی رمز عبور</strong> از طریق پیامک</li>
<li style="margin-bottom:8px;">حریم خصوصی کامل داده‌های شخصی شما</li>
</ul>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">چطور شروع کنیم؟</h2>
<p style="margin-bottom:18px;">شروع کار با تسک فلو بسیار ساده است:</p>

<div style="background:linear-gradient(135deg, #eef2ff, #e0e7ff); border:1px solid #c7d2fe; border-radius:16px; padding:24px; margin:20px 0;">
<div style="margin-bottom:16px; display:flex; align-items:flex-start; gap:12px;">
<div style="min-width:36px; height:36px; background:#4f46e5; color:white; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px;">۱</div>
<div>
<p style="font-weight:700; color:#1e1b4b; margin-bottom:2px;">ثبت‌نام کنید</p>
<p style="color:#6b7280; font-size:14px; margin:0;">با شماره تلفن و رمز عبور خود ثبت‌نام کنید و کد تأیید پیامکی را وارد نمایید.</p>
</div>
</div>
<div style="margin-bottom:16px; display:flex; align-items:flex-start; gap:12px;">
<div style="min-width:36px; height:36px; background:#4f46e5; color:white; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px;">۲</div>
<div>
<p style="font-weight:700; color:#1e1b4b; margin-bottom:2px;">دسته‌بندی بسازید</p>
<p style="color:#6b7280; font-size:14px; margin:0;">دسته‌بندی‌های مورد نظرتان را با رنگ‌های دلخواه ایجاد کنید (مثلاً کار، شخصی، تحصیلی).</p>
</div>
</div>
<div style="margin-bottom:16px; display:flex; align-items:flex-start; gap:12px;">
<div style="min-width:36px; height:36px; background:#4f46e5; color:white; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px;">۳</div>
<div>
<p style="font-weight:700; color:#1e1b4b; margin-bottom:2px;">تسک اضافه کنید</p>
<p style="color:#6b7280; font-size:14px; margin:0;">تسک‌های خود را با تاریخ، ساعت و دسته‌بندی ثبت کنید.</p>
</div>
</div>
<div style="display:flex; align-items:flex-start; gap:12px;">
<div style="min-width:36px; height:36px; background:#4f46e5; color:white; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px;">۴</div>
<div>
<p style="font-weight:700; color:#1e1b4b; margin-bottom:2px;">عملکردتان را رصد کنید</p>
<p style="color:#6b7280; font-size:14px; margin:0;">از نمودارها و گزارش‌ها برای بررسی پیشرفت خود استفاده کنید.</p>
</div>
</div>
</div>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">قیمت‌گذاری</h2>
<p style="margin-bottom:18px;">تسک فلو با یک مدل ساده و منصفانه قیمت‌گذاری شده:</p>
<div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:20px 24px; margin:20px 0; text-align:center;">
<p style="font-size:16px; color:#6b7280; margin-bottom:8px;">۳۰ روز اول <strong style="color:#16a34a;">کاملاً رایگان</strong></p>
<p style="font-size:28px; font-weight:900; color:#4f46e5; margin-bottom:4px;">۴۹,۰۰۰ تومان</p>
<p style="color:#9ca3af; font-size:14px; margin:0;">ماهانه / پس از پایان دوره آزمایشی</p>
</div>
<p style="margin-bottom:18px;">بدون نیاز به کارت بانکی برای شروع دوره آزمایشی و امکان لغو در هر زمان.</p>

<h2 style="font-size:22px; font-weight:800; color:#1e1b4b; margin-top:36px; margin-bottom:12px;">جمع‌بندی</h2>
<p style="margin-bottom:18px;">تسک فلو ابزاری است که برای کاربر فارسی‌زبان طراحی شده و تمام نیازهای مدیریت زمان و تسک را در یک محیط مدرن و زیبا فراهم می‌کند. از داشبورد هوشمند گرفته تا نمودارهای تحلیلی و یادآوری‌های هوشمند، همه چیز برای افزایش بهره‌وری شما آماده است.</p>
<p style="margin-bottom:18px;">همین الان <a href="/register" style="color:#4f46e5; font-weight:700; text-decoration:underline;">رایگان ثبت‌نام کنید</a> و اولین قدم را برای مدیریت بهتر زمان‌تان بردارید!</p>

</div>
HTML;
    }
}
