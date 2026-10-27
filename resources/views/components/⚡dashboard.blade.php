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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- نوار آمار — در موبایل مخفی است --}}
    <div class="hidden lg:grid lg:col-span-3 lg:grid-cols-4 gap-3">
        @php
            $stats = [
                ['status' => 'all', 'label' => 'کل تسک‌ها', 'val' => $this->totalTasks, 'icon' => 'fa-layer-group'],
                ['status' => 'completed', 'label' => 'انجام شده', 'val' => $this->completedTasks, 'icon' => 'fa-check-circle'],
                ['status' => 'pending', 'label' => 'باقی‌مانده', 'val' => $this->pendingTasks, 'icon' => 'fa-clock'],
            ];
        @endphp
        @foreach($stats as $stat)
            <button type="button" wire:click="setStatus('{{ $stat['status'] }}')"
                    class="text-start bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 flex items-start justify-between gap-2 transition-all cursor-pointer shadow-soft hover:border-indigo-300 dark:hover:border-indigo-700
                    {{ $status === $stat['status'] ? 'ring-2 ring-indigo-500/40 border-indigo-500' : '' }}">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 truncate">{{ $stat['label'] }}</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white mt-1">@fa($stat['val'])</p>
                </div>
                <span class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <i class="fas {{ $stat['icon'] }} text-sm"></i>
                </span>
            </button>
        @endforeach
        <div class="text-start bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 flex items-start justify-between gap-2 shadow-soft">
            <div class="min-w-0">
                <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400 truncate">بهره‌وری</p>
                <p class="text-xl font-bold text-slate-900 dark:text-white mt-1">@fa($this->progressPercentage)٪</p>
            </div>
            <span class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                <i class="fas fa-percentage text-sm"></i>
            </span>
        </div>
    </div>

    {{-- ستون اصلی --}}
    <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-soft">

                <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                        تسک‌های من
                    </h3>
                    <a href="{{ route('task.form') }}" wire:navigate
                       class="hidden lg:flex shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-soft transition-all
                          items-center gap-2 active:scale-95 cursor-pointer">
                        <i class="fas fa-plus text-xs"></i>
                        تسک جدید
                    </a>
                </div>

            {{-- تب‌ها --}}
            <div class="flex items-center gap-1 border-b border-slate-100 dark:border-slate-800 pb-4 mb-4 overflow-x-auto custom-scrollbar">
                @foreach(['all' => 'همه', 'today' => 'امروز', 'week' => 'هفته جاری'] as $key => $label)
                    <button type="button" wire:click="setPeriod('{{ $key }}')"
                            class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition-all
                            {{ $period === $key ? 'bg-indigo-600 text-white shadow-soft' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        {{ $label }}
                    </button>
                @endforeach
                <span class="mr-auto shrink-0 text-xs text-slate-400 dark:text-slate-500 pr-1">@fa($this->tasks->count()) تسک</span>
            </div>

            {{-- چیپ‌های دسته‌بندی --}}
            @if($this->categories->isNotEmpty())
                <div class="flex items-center gap-2 flex-wrap mb-4">
                    @foreach($this->categories as $category)
                        <div class="group/chip relative flex items-center gap-1 rounded-full text-[11px] font-bold border transition-all
                                {{ $activeCategoryId == $category->id
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-soft'
                                    : 'bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600' }}">
                            <button type="button" wire:click="setCategory({{ $category->id }})"
                                    class="flex items-center gap-1.5 ps-3 pe-2 py-1.5 cursor-pointer">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $colorsMap[$category->color] ?? '#cbd5e1' }}"></span>
                                {{ $category->name }}
                            </button>

                            <span class="flex items-center gap-0.5 pe-1.5 ps-1 border-e
                                {{ $activeCategoryId == $category->id ? 'border-white/25' : 'border-slate-200 dark:border-slate-700' }}
                                opacity-100 md:opacity-0 md:group-hover/chip:opacity-100 transition-opacity">
                                <button type="button" wire:click="$dispatchTo('category-modal','open-modal', { category: {{ $category->id }} })"
                                        class="p-1 rounded-full transition-colors {{ $activeCategoryId == $category->id ? 'hover:bg-white/20' : 'text-slate-400 hover:bg-indigo-100 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400' }}"
                                        title="ویرایش دسته‌بندی">
                                    <i class="fas fa-pen text-[9px]"></i>
                                </button>
                                <button type="button" wire:click.stop="deleteCategory({{ $category->id }})"
                                        wire:confirm="آیا از حذف این دسته‌بندی مطمئن هستید؟"
                                        class="p-1 rounded-full transition-colors {{ $activeCategoryId == $category->id ? 'hover:bg-white/20' : 'text-slate-400 hover:bg-rose-100 dark:hover:bg-rose-950/40 hover:text-rose-600' }}"
                                        title="حذف دسته‌بندی">
                                    <i class="fas fa-trash text-[9px]"></i>
                                </button>
                            </span>
                        </div>
                    @endforeach
                    <button type="button" wire:click="$dispatchTo('category-modal','open-modal')"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-bold border border-dashed border-slate-300 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors">
                        <i class="fas fa-plus text-[9px]"></i>
                        دسته
                    </button>
                </div>
            @endif

            {{-- لیست تسک‌ها --}}
            @php($visibleTasks = $this->tasks->take($this->limit))
            <div class="space-y-2">
                @forelse($visibleTasks as $task)
                    <div wire:key="task-{{ $task->id }}"
                         class="group flex items-center gap-3 p-3 border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all duration-200">
                        <button type="button" wire:click="toggleTask({{ $task->id }})"
                                class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-200 active:scale-90
                                {{ $task->status === 'completed'
                                    ? 'bg-emerald-500 border-emerald-500 text-white'
                                    : 'border-slate-300 dark:border-slate-600 hover:border-indigo-500 bg-white dark:bg-slate-900' }}">
                            @if($task->status === 'completed') <i class="fas fa-check text-[10px]"></i> @endif
                        </button>

                        <div class="flex-1 min-w-0">
                            <p wire:click="$dispatchTo('task.modal','show', { task: {{ $task->id }} })"
                               class="cursor-pointer text-sm font-bold truncate transition {{ $task->status === 'completed' ? 'text-slate-400 line-through' : 'text-slate-900 dark:text-slate-100' }}">
                                {{ $task->title }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                                    @fa(\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($task->due_date))->format('%d %B'))
                                </span>
                                @if($task->category)
                                    <span class="text-[10px] flex items-center gap-1.5 px-2 py-0.5 rounded-full border" style="background-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}15; border-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}40; color: {{ $colorsMap[$task->category?->color] ?? '#64748b' }}">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}"></span>
                                        {{ $task->category->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                        <p class="text-slate-500 dark:text-slate-400 text-sm">تسک جدیدی تعریف نشده است.</p>
                    </div>
                @endforelse
            </div>

            @if($this->tasks->count() > $this->limit)
                <button type="button" wire:click="loadMore"
                        class="w-full mt-4 flex items-center justify-center gap-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 px-4 py-3 rounded-xl transition-colors">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                    نمایش بیشتر
                </button>
            @endif
        </div>
    </div>

    {{-- ستون کناری --}}
    <aside class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-soft">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2 mb-4">
                <i class="fas fa-chart-bar text-indigo-500"></i>
                حجم کار هفته
            </h3>
            <div class="h-32 flex items-end justify-between gap-1 px-1">
                @foreach($this->weeklyActivity as $day)
                    <div class="flex flex-col items-center gap-2 group w-full relative">
                        <div class="relative w-full {{ $day['height'] }} rounded-t-lg transition-colors duration-300
                            {{ $day['isToday'] ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700 group-hover:bg-indigo-200' }}">
                        </div>
                        <span class="text-[10px] lg:text-[8px] font-bold whitespace-nowrap leading-none {{ $day['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                            {{ $day['day'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <a href="{{ route('reports') }}" wire:navigate class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-indigo-300 transition-all p-4 flex items-center justify-between group shadow-soft">
             <span class="text-sm font-medium text-slate-700 dark:text-slate-300">گزارش کامل</span>
             <i class="fas fa-arrow-left text-slate-400 text-sm group-hover:text-indigo-600"></i>
        </a>
    </aside>

</div>