<?php

use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

new class extends Component {

    #[Computed]
    public function totalTasks()
    {
        return Task::where('user_id', auth()->id())->count();
    }

    #[Computed]
    public function completedTasks()
    {
        return Task::where('user_id', auth()->id())->where('status', 'completed')->count();
    }

    #[Computed]
    public function pendingTasks()
    {
        return Task::where('user_id', auth()->id())->where('status', 'pending')->count();
    }

    #[Computed]
    public function todayDate()
    {
        return Jalalian::now()->format('%A، d %B Y');
    }

    #[Computed]
    public function progressPercentage()
    {
        $total = $this->totalTasks;
        return $total > 0 ? round(($this->completedTasks / $total) * 100) : 0;
    }

    #[Computed]
    public function todayTasks()
    {
        $todayStart = Jalalian::now()->toCarbon()->startOfDay();
        $todayEnd = Jalalian::now()->toCarbon()->endOfDay();

        return Task::where('user_id', auth()->id())
            ->whereBetween('due_date', [$todayStart, $todayEnd])
            ->where('status', 'pending')
            ->get();
    }

    public function toggleTask($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update([
                'status' => $task->status === 'completed' ? 'pending' : 'completed'
            ]);
        }
    }

    #[Computed]
    public function weeklyActivity()
    {
        $userId = auth()->id();
        $days = [];

        // تغییر ۱: حلقه از ۰ تا ۶ (یعنی از امروز تا ۶ روز آینده)
        for ($i = 0; $i <= 6; $i++) {
            $date = Jalalian::now()->addDays($i); // استفاده از addDays بجای subDays

            // دریافت تاریخ میلادی برای دیتابیس
            $gregorianDate = $date->toCarbon()->format('Y-m-d');

            // تغییر ۲: کوئری بر اساس due_date و تسک‌های انجام نشده (Pending)
            // چون معمولاً می‌خواهیم بدانیم "چه کارهایی مانده است"
            $count = Task::where('user_id', $userId)
                ->whereDate('due_date', $gregorianDate)
                ->where('status', 'pending')
                ->count();

            // تنظیم ارتفاع میله نمودار
            $heightClass = match (true) {
                $count == 0 => 'h-1.5', // کمی ارتفاع برای زیبایی روزهای خالی
                $count <= 2 => 'h-8',
                $count <= 4 => 'h-16',
                $count <= 6 => 'h-24',
                $count <= 8 => 'h-32',
                default => 'h-40',
            };

            // استایل خاص برای "امروز"
            $isToday = $i === 0;

            $days[] = [
                'day' => $date->format('%A'),
                'count' => $count,
                'height' => $heightClass,
                'isToday' => $isToday, // برای تغییر رنگ در ویو
                'full_date' => $date->format('Y/m/d'), // برای تولتیپ
            ];
        }

        return $days;
    }

    #[On('refresh')]
    public function refresh(){}
};
?>

<main class="flex-1 overflow-y-auto p-4 lg:p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 auto-rows-[minmax(140px,auto)] gap-4">

        {{-- کارت خوش‌آمدگویی — تمام عرض --}}
        <div class="md:col-span-2 lg:col-span-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5 md:p-6
                    flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative overflow-hidden">
            <div class="absolute -top-12 -left-12 w-44 h-44 bg-indigo-50 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative">
                <h2 class="text-lg md:text-xl font-bold text-slate-800">{{ auth()->user()->name }} عزیز، خوش‌آمدی!</h2>
                <p class="text-xs md:text-sm text-slate-500 mt-1.5 flex items-center gap-2">
                    <i class="fas fa-calendar-day text-indigo-400"></i>
                    {{ \Morilog\Jalali\Jalalian::now()->format('%A، d %B Y') }}
                </p>
            </div>
            <a href="{{ route('task.form') }}" wire:navigate
               class="relative shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl shadow-lg shadow-indigo-200
                  transition-all flex items-center gap-2 font-medium justify-center active:scale-95 cursor-pointer">
                <i class="fas fa-plus"></i>
                تسک جدید
            </a>
        </div>

        {{-- چهار کارت آمار --}}
        <div
            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-slate-500 text-xs font-bold mb-1 uppercase tracking-wider">کل تسک‌ها</p>
                <h3 class="text-2xl font-bold text-slate-800">{{$this->totalTasks}}</h3>
            </div>
            <div
                class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
        </div>
        <div
            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-slate-500 text-xs font-bold mb-1 uppercase tracking-wider">انجام شده</p>
                <h3 class="text-2xl font-bold text-emerald-600">{{ $this->completedTasks }}</h3>
            </div>
            <div
                class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
        <div
            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-slate-500 text-xs font-bold mb-1 uppercase tracking-wider">باقی‌مانده</p>
                <h3 class="text-2xl font-bold text-amber-600">{{ $this->pendingTasks }}</h3>
            </div>
            <div
                class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
        <div
            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition">
            <div>
                <p class="text-slate-500 text-xs font-bold mb-1 uppercase tracking-wider">بهره‌وری</p>
                <h3 class="text-2xl font-bold text-indigo-600">%{{$this->progressPercentage}} </h3>
            </div>
            <div class="relative w-12 h-12 flex items-center justify-center">
                <svg class="transform -rotate-90 w-12 h-12">
                    <circle cx="24" cy="24" r="18" stroke="currentColor" stroke-width="4" fill="transparent"
                            class="text-slate-100"/>
                    <circle cx="24" cy="24" r="18" stroke="currentColor" stroke-width="4" fill="transparent"
                            stroke-dasharray="113"
                            stroke-dashoffset="{{ 113 - (113 * $this->progressPercentage / 100) }}"
                            class="text-indigo-500 transition-all duration-1000 ease-out"/>
                </svg>
            </div>
        </div>

        {{-- کارت برنامه امروز — هم‌قد نمودار --}}
        <div class="md:col-span-2 lg:col-span-2 lg:row-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition p-5 md:p-6 flex flex-col">
            <h3 class="font-bold text-slate-700 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
                برنامه امروز
            </h3>
            <div class="flex-1 space-y-3 overflow-y-auto max-h-[250px] custom-scrollbar pr-1">
                @if($this->todayTasks->count() > 0)
                    <div class="space-y-3 overflow-y-auto max-h-[250px] custom-scrollbar pr-1">
                        @foreach($this->todayTasks->take(3) as $task)
                            <div wire:key="today-{{ $task->id }}"
                                 class="flex items-start gap-3 p-3 rounded-xl border border-slate-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition group">
                                <input type="checkbox"
                                       wire:click="toggleTask({{ $task->id }})"
                                       @checked($task->status === 'completed')
                                       class="mt-1 w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-700 {{ $task->status === 'completed' ? 'line-through opacity-50' : '' }}">
                                        {{ $task->title }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center pt-2 mt-4 w-full py-2 border-2 border-dashed border-slate-200 rounded-xl text-slate-400
                    text-sm font-bold hover:border-indigo-400 hover:text-indigo-600 transition">
                        <a href="{{ route('monthly.calendar') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                            مشاهده همه
                        </a>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center py-6">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-mug-hot text-slate-300 text-2xl"></i>
                        </div>
                        <p class="text-slate-600 font-medium text-sm">امروز تسکی نداری!</p>
                        <p class="text-slate-400 text-xs mt-1">می‌تونی استراحت کنی یا تسک جدید بسازی.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- کارت نمودار هفته — بزرگ‌ترین کارت بصری --}}
        <div class="md:col-span-2 lg:col-span-2 lg:row-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition p-5 md:p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-indigo-500"></i>
                    حجم کار هفته پیش‌رو
                </h3>
                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded-lg">
                    بر اساس سررسید
                </span>
            </div>

            <div class="flex-1 min-h-[12rem] flex items-end justify-between gap-2 sm:gap-4 px-2 pt-10"> @foreach($this->weeklyActivity as $day)
                    <div class="flex flex-col items-center gap-2 group w-full relative">
                        <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1.5 px-2.5 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20 whitespace-nowrap shadow-xl pointer-events-none">
                            {{ $day['count'] }} تسک
                            <div class="text-[9px] text-slate-400 mt-0.5 text-center">{{ $day['full_date'] ?? '' }}</div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-slate-800 rotate-45"></div>
                        </div>
                        <div class="relative w-full {{ $day['height'] }} rounded-t-xl transition-all duration-300 overflow-hidden cursor-pointer
                            {{ $day['isToday'] ? 'bg-indigo-100 ring-2 ring-indigo-200 ring-offset-2' : 'bg-slate-100 hover:bg-indigo-50' }}">
                            <div class="absolute bottom-0 left-0 right-0 h-[70%] rounded-t-xl transition-all opacity-80 group-hover:opacity-100
                                {{ $day['isToday'] ? 'bg-indigo-600' : 'bg-slate-400 group-hover:bg-indigo-500' }}">
                            </div>
                        </div>
                        <span class="text-xs font-bold transition {{ $day['isToday'] ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-600' }}">
                            {{ $day['day'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- کارت بهره‌وری کلی + گزارش کامل --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5
                    flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="relative w-12 h-12 shrink-0">
                    <svg class="transform -rotate-90 w-12 h-12">
                        <circle cx="24" cy="24" r="18" stroke="currentColor" stroke-width="4" fill="transparent"
                                class="text-slate-100"/>
                        <circle cx="24" cy="24" r="18" stroke="currentColor" stroke-width="4" fill="transparent"
                                stroke-dasharray="113"
                                stroke-dashoffset="{{ 113 - (113 * $this->progressPercentage / 100) }}"
                                class="text-indigo-500 transition-all duration-1000 ease-out"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-[10px] font-black text-indigo-600">%{{ $this->progressPercentage }}</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-700">بهره‌وری کلی شما</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $this->completedTasks }} از {{ $this->totalTasks }} تسک انجام شده</p>
                </div>
            </div>
            <a href="{{ route('reports') }}" wire:navigate
               class="shrink-0 inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold
                  px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200 transition-all active:scale-95 cursor-pointer">
                <i class="fas fa-chart-line text-xs"></i>
                مشاهده گزارش کامل
            </a>
        </div>

        {{-- کارت لینک سریع آموزش --}}
        <a href="{{ route('help') }}" wire:navigate
           class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5
              flex items-center justify-between gap-3 group cursor-pointer">
            <div class="flex items-center gap-3">
                <span class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm
                         group-hover:bg-indigo-600 group-hover:text-white transition-colors shrink-0">
                    <i class="fas fa-book-open"></i>
                </span>
                <div>
                    <p class="text-sm font-bold text-slate-700">آموزش و راهنما</p>
                    <p class="text-xs text-slate-400 mt-0.5">شروع سریع با تسک‌فلو</p>
                </div>
            </div>
            <i class="fas fa-chevron-left text-slate-300 group-hover:text-indigo-500 group-hover:-translate-x-0.5 transition-all"></i>
        </a>

    </div>
</main>
