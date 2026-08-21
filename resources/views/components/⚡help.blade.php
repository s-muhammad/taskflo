<?php

use Livewire\Component;

new class extends Component {};
?>

<div class="w-full max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 md:p-8 text-center">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm mb-4">
            <i class="fas fa-book-open text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-800">آموزش و راهنما</h2>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            این بخش به‌زودی راه‌اندازی می‌شود؛ آموزش گام‌به‌گام کار با تسک‌ها، تقویم و گزارش‌ها اینجا قرار می‌گیرد.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([
                ['icon' => 'fa-check-square', 'title' => 'مدیریت تسک‌ها', 'desc' => 'ساخت، ویرایش و تکمیل تسک‌ها'],
                ['icon' => 'fa-calendar-alt', 'title' => 'برنامه ماهانه', 'desc' => 'دید کلی از برنامه هر ماه'],
                ['icon' => 'fa-chart-line',   'title' => 'گزارش عملکرد',  'desc' => 'تحلیل بهره‌وری شما'],
            ] as $item)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center opacity-70">
                <div class="w-10 h-10 mx-auto rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center mb-3">
                    <i class="fas {{ $item['icon'] }}"></i>
                </div>
                <p class="text-sm font-bold text-slate-700">{{ $item['title'] }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $item['desc'] }}</p>
                <span class="inline-block mt-3 text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-lg">به‌زودی</span>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center">
        <a href="{{ route('support') }}" wire:navigate
           class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
            <i class="fas fa-shield text-xs"></i>
            سوالی دارید؟ از پشتیبانی بپرسید
            <i class="fas fa-chevron-left text-[10px]"></i>
        </a>
    </div>

</div>
