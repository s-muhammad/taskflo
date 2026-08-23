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

<main class="flex-1 overflow-y-auto bg-slate-50 p-4 lg:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- کارت خوش‌آمدگویی — فقط دسکتاپ (در موبایل هدر و FAB نوار پایین این نقش را دارند) --}}
        <div class="hidden lg:flex lg:col-span-3 bg-white rounded-xl border border-slate-200 p-6
                    items-center justify-between gap-4">
            <div>
                <h2 class="text-lg md:text-xl font-bold tracking-tight text-slate-900">{{ auth()->user()->name }} عزیز، خوش‌آمدی!</h2>
                <p class="text-xs md:text-sm text-slate-500 mt-1.5 flex items-center gap-2">
                    <i class="fas fa-calendar-day text-slate-400"></i>
                    {{ \Morilog\Jalali\Jalalian::now()->format('%A، d %B Y') }}
                </p>
            </div>
            <a href="{{ route('task.form') }}" wire:navigate
               class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg shadow-sm
                  transition-colors flex items-center gap-2 font-medium justify-center active:scale-95 cursor-pointer">
                <i class="fas fa-plus"></i>
                تسک جدید
            </a>
        </div>

        {{-- ویجت‌های آماری کوچک — سبک شادکن، جمع‌وجور --}}
        <div class="lg:col-span-3 grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white rounded-lg border border-slate-200 p-4 flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">کل تسک‌ها</p>
                    <p class="text-xl font-bold tracking-tight text-slate-900 mt-1">{{ $this->totalTasks }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-layer-group text-xs"></i>
                </span>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">انجام شده</p>
                    <p class="text-xl font-bold tracking-tight text-slate-900 mt-1">{{ $this->completedTasks }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-check-circle text-xs"></i>
                </span>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">باقی‌مانده</p>
                    <p class="text-xl font-bold tracking-tight text-slate-900 mt-1">{{ $this->pendingTasks }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-clock text-xs"></i>
                </span>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-indigo-500 truncate">بهره‌وری</p>
                    <p class="text-xl font-bold tracking-tight text-indigo-600 mt-1">%{{ $this->progressPercentage }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-percentage text-xs"></i>
                </span>
            </div>
        </div>

        {{-- ستون اصلی — کارهای امروز --}}
        <div class="lg:col-span-2 flex flex-col gap-4">

        {{-- کارت برنامه امروز — اصلی‌ترین بخش صفحه --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5 md:p-6 flex-1 flex flex-col">
            <h3 class="font-bold tracking-tight text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-slate-900 rounded-full animate-pulse"></span>
                برنامه امروز
            </h3>

            @if($this->todayTasks->count() > 0)
                <div class="flex-1 space-y-3 overflow-y-auto max-h-[500px] custom-scrollbar pr-1">
                    @foreach($this->todayTasks as $task)
                        <div wire:key="today-{{ $task->id }}"
                             class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-colors group">
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
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center py-10">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-mug-hot text-slate-400 text-2xl"></i>
                    </div>
                    <p class="text-slate-900 font-medium text-sm">امروز تسکی نداری!</p>
                    <p class="text-slate-500 text-xs mt-1">می‌تونی استراحت کنی یا تسک جدید بسازی.</p>
                </div>
            @endif
        </div>
        </div>

        {{-- ستون کناری — نمودار کوچک + میان‌برهای گزارش و آموزش --}}
        <aside class="lg:col-span-1 flex flex-col gap-4">

            {{-- نمودار هفته — کوچک و کم‌اهمیت --}}
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="flex items-center justify-between mb-2 gap-2">
                    <h3 class="font-bold tracking-tight text-slate-900 text-sm flex items-center gap-2">
                        <i class="fas fa-chart-bar text-slate-500"></i>
                        حجم کار هفته پیش‌رو
                    </h3>
                    <span class="text-[10px] font-medium bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full shrink-0">
                        بر اساس سررسید
                    </span>
                </div>

                <div class="h-32 flex items-end justify-between gap-1.5 sm:gap-2 px-1 pt-6">
                    @foreach($this->weeklyActivity as $day)
                        <div class="flex flex-col items-center gap-2 group w-full relative">
                            <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white text-[10px] py-1.5 px-2.5 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20 whitespace-nowrap pointer-events-none">
                                {{ $day['count'] }} تسک
                                <div class="text-[9px] text-slate-400 mt-0.5 text-center">{{ $day['full_date'] ?? '' }}</div>
                                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-slate-900 rotate-45"></div>
                            </div>
                            <div class="relative w-full {{ $day['height'] }} origin-bottom scale-y-75 rounded-t-md transition-colors duration-300 overflow-hidden cursor-pointer
                                {{ $day['isToday'] ? 'bg-slate-300' : 'bg-slate-300 group-hover:bg-slate-300' }}">
                                <div class="absolute bottom-0 left-0 right-0 h-[70%] rounded-t-md transition-colors opacity-100
                                    {{ $day['isToday'] ? 'bg-slate-500' : 'bg-slate-500 group-hover:bg-slate-400' }}">
                                </div>
                            </div>
                            <span class="text-[11px] font-bold transition {{ $day['isToday'] ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-600' }}">
                                {{ $day['day'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- میان‌بر گزارش — سبک outline --}}
            <a href="{{ route('reports') }}" wire:navigate
               class="rounded-lg border border-slate-200 hover:bg-slate-100/60 transition-colors p-3
                  flex items-center justify-between gap-2 group cursor-pointer">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="w-8 h-8 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                        <i class="fas fa-chart-line text-xs"></i>
                    </span>
                    <p class="text-sm font-medium text-slate-700 truncate">مشاهده گزارش کامل</p>
                </div>
                <i class="fas fa-chevron-left text-slate-400 text-sm group-hover:text-slate-700 group-hover:-translate-x-0.5 transition-all shrink-0"></i>
            </a>

            {{-- میان‌بر آموزش — سبک outline --}}
            <a href="{{ route('help') }}" wire:navigate
               class="rounded-lg border border-slate-200 hover:bg-slate-100/60 transition-colors p-3
                  flex items-center justify-between gap-2 group cursor-pointer">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="w-8 h-8 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                        <i class="fas fa-book-open text-xs"></i>
                    </span>
                    <p class="text-sm font-medium text-slate-700 truncate">آموزش و راهنما</p>
                </div>
                <i class="fas fa-chevron-left text-slate-400 text-sm group-hover:text-slate-700 group-hover:-translate-x-0.5 transition-all shrink-0"></i>
            </a>

        </aside>

    </div>
</main>
