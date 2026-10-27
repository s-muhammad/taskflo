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

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 text-center shadow-soft">
        <div class="w-12 h-12 mx-auto rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4">
            <i class="fas fa-book-open text-xl"></i>
        </div>
        <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">آموزش و راهنما</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed max-w-lg mx-auto">
            این بخش به‌زودی راه‌اندازی می‌شود؛ آموزش گام‌به‌گام کار با تسک‌ها، تقویم و گزارش‌ها اینجا قرار می‌گیرد.
        </p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-soft">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl flex items-center justify-center ml-3">
                <i class="fas fa-rocket text-base"></i>
            </div>
            <h3 class="text-base font-bold tracking-tight text-slate-900 dark:text-white">شروع سریع</h3>
        </div>
        <div class="space-y-2">
            @foreach($this->blogs->where('category_id',1) as $post)
                <a href="{{ route('blog.single',$post->slug) }}"
                   class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all group">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $post->title }}</span>
                    <i class="fas fa-arrow-left text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-soft">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl flex items-center justify-center ml-3">
                <i class="fas fa-lightbulb text-base"></i>
            </div>
            <h3 class="text-base font-bold tracking-tight text-slate-900 dark:text-white">آخرین مقالات سایت</h3>
        </div>
        <div class="space-y-2">
            @foreach($this->blogs->reverse()->take(3) as $blog)
                <a href="{{ route('blog.single',$blog->slug) }}"
                   class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all group">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $blog->title }}</span>
                    <i class="fas fa-arrow-left text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex justify-center pb-2">
        <a href="{{ route('support') }}" wire:navigate
           class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition">
            <i class="fas fa-shield text-xs"></i>
            به کمک نیاز دارید؟ از پشتیبانی بپرسید
            <i class="fas fa-chevron-left text-[10px]"></i>
        </a>
    </div>

</div>
