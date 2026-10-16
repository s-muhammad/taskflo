<?php

use App\Models\Blog;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {

    #[Computed]
    public function blogs()
    {
        return Blog::all();
    }

};
?>

<div class="w-full max-w-3xl mx-auto space-y-4">

    <div class="bg-white rounded-xl border border-slate-200 p-6 md:p-8 text-center">
        <div class="w-16 h-16 mx-auto rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
            <i class="fas fa-book-open text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold tracking-tight text-slate-900">آموزش و راهنما</h2>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            این بخش به‌زودی راه‌اندازی می‌شود؛ آموزش گام‌به‌گام کار با تسک‌ها، تقویم و گزارش‌ها اینجا قرار می‌گیرد.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center mb-4">
            <div
                class="w-12 h-12 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center ml-4">
                <i class="fas fa-rocket text-lg"></i>
            </div>
            <h3 class="text-lg font-bold tracking-tight text-slate-900">شروع سریع</h3>
        </div>
        <p class="text-slate-500 mb-5 text-sm">آموزش‌های اولیه برای شروع کار با تسک پلنر</p>
        <div class="space-y-3">
            @foreach($this->blogs->where('category_id',1) as $post)
                <a href="{{ route('blog.single',$post->slug) }}"
                   class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                    <span class="text-slate-700 group-hover:text-indigo-600">{{ $post->title }}</span>
                    <i class="fas fa-arrow-left text-slate-400 group-hover:text-indigo-500"></i>
                </a>
            @endforeach
        </div>
    </div>

    <!-- ویجت نکات حرفه‌ای -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center mb-4">
            <div
                class="w-12 h-12 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center ml-4">
                <i class="fas fa-lightbulb text-lg"></i>
            </div>
            <h3 class="text-lg font-bold tracking-tight text-slate-900">آخرین مقالات سایت</h3>
        </div>
        <p class="text-slate-500 mb-5 text-sm">راهکارهای پیشرفته برای افزایش بهره وری</p>
        <div class="space-y-3">
            @foreach($this->blogs->reverse()->take(3) as $blog)
                <a href="{{ route('blog.single',$blog->slug) }}"
                   class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                    <span class="text-slate-700 group-hover:text-indigo-600">{{ $blog->title }}</span>
                    <i class="fas fa-arrow-left text-slate-400 group-hover:text-indigo-500"></i>
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex justify-center pb-2">
        <a href="{{ route('support') }}" wire:navigate
           class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
            <i class="fas fa-shield text-xs"></i>
            به کمک نیاز دارید؟ از پشتیبانی بپرسید
            <i class="fas fa-chevron-left text-[10px]"></i>
        </a>
    </div>

</div>
