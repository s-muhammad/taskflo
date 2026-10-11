<?php

use App\Models\Category;
use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

new class extends Component {

    public $colorsMap = [
        'slate'   => '#64748b', 'red'     => '#ef4444', 'orange'  => '#f97316',
        'amber'   => '#f59e0b', 'yellow'  => '#eab308', 'lime'    => '#84cc16',
        'green'   => '#22c55e', 'emerald' => '#10b981', 'teal'    => '#14b8a6',
        'cyan'    => '#06b6d4', 'sky'     => '#0ea5e9', 'blue'    => '#3b82f6',
        'indigo'  => '#6366f1', 'violet'  => '#8b5cf6', 'purple'  => '#a855f7',
        'fuchsia' => '#d946ef', 'pink'    => '#ec4899', 'rose'    => '#f43f5e'
    ];

    public $limit = 6;

    #[Url]
    public $period = 'today';

    #[Url]
    public $status = 'all';

    #[Url]
    public $activeCategoryId = null;

    public function setStatus($status)
    {
        $this->status = $status;
        $this->limit = 6;
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        $this->limit = 6;
    }

    public function setCategory($id)
    {
        $this->activeCategoryId = $this->activeCategoryId == $id ? null : $id;
        $this->limit = 6;
    }

    public function resetFilters()
    {
        $this->status = 'all';
        $this->period = 'today';
        $this->activeCategoryId = null;
        $this->limit = 6;
    }

    public function loadMore()
    {
        $this->limit += 6;
    }

    #[Computed]
    public function tasks()
    {
        $query = Task::where('user_id', auth()->id());

        match ($this->period) {
            'today' => $query->whereBetween('due_date', [
                Jalalian::now()->toCarbon()->startOfDay(),
                Jalalian::now()->toCarbon()->endOfDay(),
            ]),
            'week' => $query->whereBetween('due_date', [
                Jalalian::now()->toCarbon(),
                Jalalian::now()->addDays(6)->toCarbon()->endOfDay(),
            ]),
            default => null,
        };

        if ($this->status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->status === 'completed') {
            $query->where('status', 'completed');
        }

        if ($this->activeCategoryId) {
            $query->where('category_id', $this->activeCategoryId);
        }

        return $query->orderBy('due_date')->orderBy('time')->get();
    }

    #[Computed]
    public function categories()
    {
        return Category::where('user_id', auth()->id())->get();
    }

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
    public function progressPercentage()
    {
        $total = $this->totalTasks;
        return $total > 0 ? round(($this->completedTasks / $total) * 100) : 0;
    }

    public function toggleTask($taskId)
    {
        $task = Task::where('id', $taskId)->where('user_id', auth()->id())->first();
        if ($task) {
            $task->status = $task->status === 'completed' ? 'pending' : 'completed';
            $task->save();
        }
    }

    public function deleteCategory($categoryId)
    {
        Category::where('id', $categoryId)->where('user_id', auth()->id())->delete();

        if ($this->activeCategoryId == $categoryId) {
            $this->activeCategoryId = null;
        }
    }

    #[Computed]
    public function weeklyActivity()
    {
        $userId = auth()->id();
        $days = [];

        for ($i = 0; $i <= 6; $i++) {
            $date = Jalalian::now()->addDays($i);
            $gregorianDate = $date->toCarbon()->format('Y-m-d');

            $count = Task::where('user_id', $userId)
                ->whereDate('due_date', $gregorianDate)
                ->where('status', 'pending')
                ->count();

            $heightClass = match (true) {
                $count == 0 => 'h-1.5',
                $count <= 2 => 'h-8',
                $count <= 4 => 'h-16',
                $count <= 6 => 'h-24',
                $count <= 8 => 'h-32',
                default => 'h-40',
            };

            $isToday = $i === 0;

            $days[] = [
                'day' => $date->format('%A'),
                'count' => $count,
                'height' => $heightClass,
                'isToday' => $isToday,
                'full_date' => $date->format('Y/m/d'),
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

        {{-- نوار آمار — کلیک روی هر کارت، لیست پایین را فیلتر می‌کند --}}
        <div class="lg:col-span-3 grid grid-cols-2 lg:grid-cols-4 gap-3">
            <button type="button" wire:click="setStatus('all')"
                    class="text-right bg-white rounded-lg border p-4 flex items-start justify-between gap-2 transition-all cursor-pointer
                    {{ $status === 'all' ? 'border-indigo-400 ring-1 ring-indigo-400' : 'border-slate-200 hover:border-slate-300' }}">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">کل تسک‌ها</p>
                    <p class="text-xl font-bold tracking-tight text-slate-900 mt-1">{{ $this->totalTasks }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-layer-group text-xs"></i>
                </span>
            </button>
            <button type="button" wire:click="setStatus('completed')"
                    class="text-right bg-white rounded-lg border p-4 flex items-start justify-between gap-2 transition-all cursor-pointer
                    {{ $status === 'completed' ? 'border-indigo-400 ring-1 ring-indigo-400' : 'border-slate-200 hover:border-slate-300' }}">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">انجام شده</p>
                    <p class="text-xl font-bold tracking-tight text-slate-900 mt-1">{{ $this->completedTasks }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-check-circle text-xs"></i>
                </span>
            </button>
            <button type="button" wire:click="setStatus('pending')"
                    class="text-right bg-white rounded-lg border p-4 flex items-start justify-between gap-2 transition-all cursor-pointer
                    {{ $status === 'pending' ? 'border-indigo-400 ring-1 ring-indigo-400' : 'border-slate-200 hover:border-slate-300' }}">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">باقی‌مانده</p>
                    <p class="text-xl font-bold tracking-tight text-slate-900 mt-1">{{ $this->pendingTasks }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-clock text-xs"></i>
                </span>
            </button>
            <button type="button" wire:click="resetFilters"
                    class="text-right bg-white rounded-lg border border-slate-200 p-4 flex items-start justify-between gap-2 transition-all cursor-pointer hover:border-slate-300">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-indigo-500 truncate">بهره‌وری</p>
                    <p class="text-xl font-bold tracking-tight text-indigo-600 mt-1">%{{ $this->progressPercentage }}</p>
                </div>
                <span class="w-7 h-7 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-percentage text-xs"></i>
                </span>
            </button>
        </div>

        {{-- ستون اصلی — افزودن سریع، تب‌ها و لیست تسک‌ها --}}
        <div class="lg:col-span-2 flex flex-col gap-4">

            {{-- لیست تسک‌ها --}}
            <div class="bg-white rounded-xl border border-slate-200 p-5 md:p-6">

                {{-- هدر کارت + دکمهٔ افزودن --}}
                <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                    <h3 class="font-bold tracking-tight text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 bg-slate-900 rounded-full animate-pulse"></span>
                        تسک‌های من
                    </h3>
                    <a href="{{ route('task.form') }}" wire:navigate
                       class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors
                          flex items-center gap-2 active:scale-95 cursor-pointer">
                        <i class="fas fa-plus text-xs"></i>
                        تسک جدید
                    </a>
                </div>

                {{-- تب‌های افقی --}}
                <div class="flex items-center gap-1 border-b border-slate-100 pb-3 mb-4 overflow-x-auto custom-scrollbar">
                    <button type="button" wire:click="setPeriod('all')"
                            class="shrink-0 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors
                            {{ $period === 'all' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        همه
                    </button>
                    <button type="button" wire:click="setPeriod('today')"
                            class="shrink-0 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors
                            {{ $period === 'today' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        امروز
                    </button>
                    <button type="button" wire:click="setPeriod('week')"
                            class="shrink-0 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors
                            {{ $period === 'week' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        هفته جاری
                    </button>

                    <span class="mr-auto shrink-0 text-[11px] text-slate-400 pr-1">{{ $this->tasks->count() }} تسک</span>
                </div>

                {{-- چیپ‌های دسته‌بندی --}}
                @if($this->categories->isNotEmpty())
                    <div class="flex items-center gap-2 flex-wrap mb-4">
                        @foreach($this->categories as $category)
                            <div class="group relative flex items-center gap-1 pl-3 pr-1.5 py-1 rounded-full text-[11px] font-medium border transition-colors
                                    {{ $activeCategoryId == $category->id ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                                <button type="button" wire:click="setCategory({{ $category->id }})"
                                        class="flex items-center gap-1.5 cursor-pointer">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $colorsMap[$category->color] ?? '#cbd5e1' }}"></span>
                                    {{ $category->name }}
                                </button>

                                <span class="flex items-center gap-0.5 mr-0.5 border-r pr-1
                                    {{ $activeCategoryId == $category->id ? 'border-white/25' : 'border-slate-200' }}
                                    md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                    <button type="button" wire:click="$dispatchTo('category-modal','open-modal', { category: {{ $category->id }} })"
                                            class="p-1 rounded-full transition-colors {{ $activeCategoryId == $category->id ? 'hover:bg-white/20' : 'hover:bg-indigo-100 text-slate-400 hover:text-indigo-600' }}"
                                            title="ویرایش دسته‌بندی">
                                        <i class="fas fa-pen text-[9px]"></i>
                                    </button>
                                    <button type="button" wire:click.stop="deleteCategory({{ $category->id }})"
                                            wire:confirm="آیا از حذف این دسته‌بندی مطمئن هستید؟"
                                            class="p-1 rounded-full transition-colors {{ $activeCategoryId == $category->id ? 'hover:bg-white/20' : 'hover:bg-red-100 text-slate-400 hover:text-red-600' }}"
                                            title="حذف دسته‌بندی">
                                        <i class="fas fa-trash text-[9px]"></i>
                                    </button>
                                </span>
                            </div>
                        @endforeach
                        <button type="button" wire:click="$dispatchTo('category-modal','open-modal')"
                                class="flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-medium border border-dashed border-slate-300 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 transition-colors">
                            <i class="fas fa-plus text-[9px]"></i>
                            دسته
                        </button>
                    </div>
                @endif

                {{-- ردیف‌های تسک --}}
                @php($visibleTasks = $this->tasks->take($this->limit))
                <div class="space-y-3">
                    @forelse($visibleTasks as $task)
                        <div wire:key="task-{{ $task->id }}"
                             class="group flex items-center gap-3 p-3.5 border border-slate-100 bg-white hover:border-indigo-200 hover:shadow-md rounded-2xl transition-all duration-200">
                            <button type="button" wire:click="toggleTask({{ $task->id }})"
                                    title="{{ $task->status === 'completed' ? 'بازگردانی به انجام‌نشده' : 'علامت‌گذاری به‌عنوان انجام‌شده' }}"
                                    class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-200 active:scale-90
                                    {{ $task->status === 'completed'
                                        ? 'bg-emerald-500 border-emerald-500 text-white shadow-sm shadow-emerald-500/40'
                                        : 'border-slate-300 text-transparent hover:border-indigo-400 hover:bg-indigo-50' }}">
                                <i class="fas fa-check text-[10px]"></i>
                            </button>

                            <div class="flex-1 min-w-0">
                                <p wire:click="$dispatchTo('task.modal','show', { task: {{ $task->id }} })"
                                   class="cursor-pointer text-sm font-bold truncate ml-2 {{ $task->status === 'completed' ? 'text-slate-400 line-through' : 'text-slate-800' }}">
                                    {{ $task->title }}
                                </p>

                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <div class="flex items-center gap-1.5 text-[10px] px-2 py-1 rounded-md border
                                        {{ $task->due_date < now()->startOfDay() && $task->status === 'pending' ? 'text-red-500 font-bold bg-red-50 border-red-100' : 'text-slate-500 bg-slate-50 border-slate-100' }}">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($task->due_date))->format('%A، %d %B') }}</span>
                                    </div>

                                    @if($task->time)
                                        <div class="flex items-center gap-1.5 text-[10px] text-slate-500 px-2 py-1 rounded-md bg-slate-50 border border-slate-100" dir="ltr">
                                            <span class="font-mono font-bold">{{ \Carbon\Carbon::parse($task->time)->format('H:i') }}</span>
                                            <i class="far fa-clock text-slate-400 text-[9px]"></i>
                                        </div>
                                    @endif

                                    @if($task->category)
                                        <span class="text-[10px] flex items-center gap-1 bg-{{$task->category->color}}-50 text-{{$task->category->color}}-600 px-2 py-1 rounded-full border border-{{$task->category->color}}-100 ml-auto sm:ml-0">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}66"></span>
                                            {{ $task->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mb-3 mx-auto">
                                <i class="fas fa-mug-hot text-slate-400 text-xl"></i>
                            </div>
                            <p class="text-slate-500 text-sm">تسکی با این فیلترها پیدا نشد.</p>
                        </div>
                    @endforelse
                </div>

                @if($this->tasks->count() > $this->limit)
                    <div class="mt-4 text-center">
                        <button type="button" wire:click="loadMore"
                                class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-full transition-colors">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                            نمایش بیشتر ({{ $this->tasks->count() - $this->limit }} مورد دیگر)
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- ستون کناری — نمودار + میان‌برها --}}
        <aside class="lg:col-span-1 flex flex-col gap-4">

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
