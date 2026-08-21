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

    #[Url]
    public $activeTab = 'all';

    #[Url]
    public $activeCategoryId = null;

    public function setTab($tab, $id = null)
    {
        $this->activeTab = $tab;
        $this->activeCategoryId = $id;
    }

    // این تابع کوئری مشترک را می‌سازد (بر اساس تب انتخاب شده)
    private function getFilteredQuery()
    {
        $query = Task::where('user_id', auth()->id());

        match ($this->activeTab) {
            'today' => $query->whereBetween('due_date', [
                Jalalian::now()->toCarbon()->startOfDay(),
                Jalalian::now()->toCarbon()->endOfDay()
            ]),
            'week' => $query->whereBetween('due_date', [
                Jalalian::now()->toCarbon(),
                Jalalian::now()->addDays(6)->toCarbon()
            ]),
            'category' => $query->where('category_id', $this->activeCategoryId),
            default => null
        };

        return $query;
    }

    #[Computed]
    public function pendingTasks()
    {
        $start = Jalalian::now()->subDay()->toCarbon();
        $end = Jalalian::now()->getEndDayOfMonth()->toCarbon()->endOfDay();

        return $this->getFilteredQuery()
            ->where('status', 'pending')
            ->whereBetween('due_date', [$start, $end])
            ->orderBy('due_date', 'ASC')
            ->get();
    }

    #[Computed]
    public function delayedTasks()
    {
        return $this->getFilteredQuery()
            ->where('status', 'pending')
            ->whereBetween('due_date', [
                Jalalian::now()->toCarbon()->startOfMonth(),
                Jalalian::now()->subDay()->toCarbon()
            ])
            ->orderBy('due_date', 'ASC')
            ->get();
    }

    #[Computed]
    public function completedTasks()
    {
        // تسک‌های انجام شده (مرتب شده بر اساس زمان انجام)
        return $this->getFilteredQuery()
            ->where('status', 'completed')
            ->orderBy('updated_at', 'DESC')
            ->get();
    }

    #[Computed]
    public function pageTitle()
    {
        return match ($this->activeTab) {
            'today' => 'برنامه امروز',
            'week' => '۷ روز آینده',
            'category' => Category::find($this->activeCategoryId)?->name ?? 'دسته‌بندی',
            default => 'همه تسک‌ها',
        };
    }

    #[Computed]
    public function categories()
    {
        return Category::where('user_id', auth()->id())->get();
    }

    #[Computed]
    public function counts()
    {
        return [
            'all' => Task::where('user_id', auth()->id())->count(),
            'today' => Task::where('user_id', auth()->id())->whereDate('due_date', now())->count(),
            'week' => Task::where('user_id', auth()->id())->whereBetween('due_date', [now(), now()->addDays(6)])->count(),
        ];
    }

    // تغییر وضعیت تسک (انجام شده/نشده)
    public function toggleStatus($taskId)
    {
        $task = Task::where('id', $taskId)->where('user_id', auth()->id())->first();
        if ($task) {
            $task->status = $task->status === 'pending' ? 'completed' : 'pending';
            $task->save();
        }
    }

    public function deleteTask($taskId)
    {
        Task::where('id', $taskId)->where('user_id', auth()->id())->delete();
    }

    #[On('refresh')]
    public function refresh()
    {

    }

    public function deleteCategory($categoryId)
    {
        Category::where('id',$categoryId)->where('user_id', auth()->id())->delete();
    }

};
?>
<div class="flex h-[calc(100dvh-11rem)] lg:h-[calc(100vh-8rem)] overflow-hidden bg-white rounded-3xl shadow-sm border border-slate-200"
     x-data="{ innerSidebarOpen: window.innerWidth >= 768 }">

    @include('components.task.sidebar', [
            'activeTab' => $activeTab,
            'activeCategoryId' => $activeCategoryId,
            'counts' => $this->counts,
            'categories' => $this->categories
        ])
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        <header
            class="h-16 flex items-center justify-between px-6 border-b border-slate-100 flex-shrink-0 bg-white z-10">
            <div class="flex items-center gap-3">
                <button @click="innerSidebarOpen = !innerSidebarOpen"
                        class="text-slate-400 hover:bg-slate-100 hover:text-indigo-600 p-2 rounded-xl transition-all duration-200 active:scale-95 border border-transparent hover:border-slate-200">
                    <i class="fas" :class="innerSidebarOpen ? 'fa-bars-staggered' : 'fa-bars'"></i>
                </button>

                <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <span wire:loading.remove target="setTab">{{ $this->pageTitle }}</span>
                    <span wire:loading target="setTab"
                          class="text-sm text-slate-400 font-normal">در حال بارگذاری...</span>
                </h1>
            </div>

            <div class="flex gap-2 text-slate-400">
                <button class="hover:bg-slate-100 p-2 rounded-lg transition"><i class="fas fa-ellipsis-v"></i></button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto px-6 custom-scrollbar relative">

            <div class="sticky top-0 z-30 pt-6 pb-4 bg-white/95 backdrop-blur-sm">
                <a href="{{ route('task.form') }}">
                <button
{{--                    wire:click="$dispatchTo('task.task-form','open-modal', { date: '{{Jalalian::now()->format('Y/m/d')}}' })"--}}
                    class="w-full group flex items-center gap-3 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/60 text-indigo-700 px-4 py-3.5 rounded-2xl transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.99] cursor-pointer"
                >
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-200 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <i class="fas fa-plus text-sm"></i>
                    </div>
                    <span class="font-bold text-sm">افزودن تسک جدید...</span>
                    <div class="mr-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span
                            class="text-[10px] font-mono bg-white border border-indigo-200 px-1.5 py-0.5 rounded text-indigo-400">Enter</span>
                    </div>
                </button>
                </a>
            </div>

            <div class="pb-10 space-y-8">
                @if($this->delayedTasks->isNotEmpty())
                    <div x-data="{ expanded: false }">

                        <div @click="expanded = !expanded"
                             class="flex items-center justify-between mb-3 px-1 cursor-pointer select-none group">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 flex items-center justify-center transition-transform duration-200"
                                     :class="expanded ? 'rotate-0' : 'rotate-90'">
                                    <i class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-indigo-500"></i>
                                </div>

                                <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">تاخیر افتاده</span>
                                <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md font-bold">
                {{ $this->delayedTasks->count() }}
            </span>
                            </div>
                            <div class="h-px bg-slate-100 flex-1 mr-4 transition-all"
                                 :class="expanded ? 'opacity-0' : 'opacity-100'"></div>
                        </div>

                        <div x-show="expanded"
                             x-collapse
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-3">

                            @foreach($this->delayedTasks as $task)
                                <div wire:key="pending-{{ $task->id }}"
                                     class="group flex items-center gap-3 p-3.5 border border-slate-100 bg-white hover:border-indigo-200 hover:shadow-md rounded-2xl transition-all duration-200 cursor-pointer">

                                    {{-- Checkbox --}}
                                    <label class="relative flex items-center cursor-pointer p-1">
                                        <input type="checkbox" wire:click="toggleStatus({{ $task->id }})" class="peer sr-only">
                                        <div class="w-6 h-6 border-2 border-slate-300 rounded-lg transition-all hover:border-indigo-400 peer-checked:bg-indigo-500 peer-checked:border-indigo-500 flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                        </div>
                                    </label>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <p class="text-slate-800 text-sm font-bold truncate ml-2">{{ $task->title }}</p>

                                            <div class="flex items-center gap-1 transition-opacity duration-200 shrink-0 opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                                <button wire:click="$dispatchTo('task.task-form','open-modal', { task: {{ $task->id }} })"
                                                        class="text-slate-400 hover:text-indigo-600 p-1">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </button>
                                                <button wire:click="deleteTask({{ $task->id }})"
                                                        class="text-slate-400 hover:text-red-500 p-1">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Footer Info: Date, Time, Category --}}
                                        <div class="flex flex-wrap items-center gap-2 mt-2">

                                            {{-- Date Badge --}}
                                            <div class="flex items-center gap-1.5 text-[10px] px-2 py-1 rounded-md bg-slate-50 border border-slate-100 {{ $task->due_date < now() ? 'text-red-500 font-bold bg-red-50 border-red-100' : 'text-slate-500' }}">
                                                <i class="far fa-calendar-alt"></i>
                                                <span>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($task->due_date))->format('%A، %d %B') }}</span>
                                            </div>

                                            {{-- Time Badge (NEW) --}}
                                            @if($task->start_time)
                                                <div class="flex items-center gap-1.5 text-[10px] text-slate-500 px-2 py-1 rounded-md bg-slate-50 border border-slate-100" dir="ltr">
                                                    <span class="font-mono font-bold">
                                                        {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }}
                                                        @if($task->end_time)
                                                            - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}
                                                        @endif
                                                    </span>
                                                    <i class="far fa-clock text-slate-400 text-[9px]"></i>
                                                </div>
                                            @endif

                                            {{-- Category Badge --}}
                                            @if($task->category)
                                                <span class="text-[10px] flex items-center gap-1 bg-{{$task->category->color}}-50 text-{{$task->category->color}}-600 px-2 py-1 rounded-full border border-{{$task->category->color}}-100 ml-auto sm:ml-0">
                                                    <span class="w-1.5 h-1.5 rounded-full"
                                                          style="background-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}66"
                                                    ></span>
                                                    {{ $task->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($this->pendingTasks->isNotEmpty())
                    <div x-data="{ expanded: true }">

                        <div @click="expanded = !expanded"
                             class="flex items-center justify-between mb-3 px-1 cursor-pointer select-none group">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 flex items-center justify-center transition-transform duration-200"
                                     :class="expanded ? 'rotate-0' : 'rotate-90'">
                                    <i class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-indigo-500"></i>
                                </div>

                                <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">در انتظار انجام</span>
                                <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md font-bold">
                {{ $this->pendingTasks->count() }}
            </span>
                            </div>
                            <div class="h-px bg-slate-100 flex-1 mr-4 transition-all"
                                 :class="expanded ? 'opacity-0' : 'opacity-100'"></div>
                        </div>

                        <div x-show="expanded"
                             x-collapse
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-3">

                            @foreach($this->pendingTasks as $task)
                                <div wire:key="pending-{{ $task->id }}"
                                     class="group flex items-center gap-3 p-3.5 border border-slate-100 bg-white hover:border-indigo-200 hover:shadow-md rounded-2xl transition-all duration-200 cursor-pointer">

                                    {{-- Checkbox --}}
                                    <label class="relative flex items-center cursor-pointer p-1">
                                        <input type="checkbox" wire:click="toggleStatus({{ $task->id }})" class="peer sr-only">
                                        <div class="w-6 h-6 border-2 border-slate-300 rounded-lg transition-all hover:border-indigo-400 peer-checked:bg-indigo-500 peer-checked:border-indigo-500 flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                        </div>
                                    </label>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                                <p wire:click="$dispatchTo('task.modal','show', { task: {{ $task->id }} })"
                                                   class="cursor-pointer text-slate-800 text-sm font-bold truncate ml-2">{{ $task->title }}
                                                </p>
                                            <div class="flex items-center gap-1 transition-opacity duration-200 shrink-0 opacity-100 md:opacity-0 md:group-hover:opacity-100">
{{--                                                <a href="{{ route('task.form',$task->id) }}"--}}
{{--                                                        class="text-slate-400 hover:text-indigo-600 p-1">--}}
{{--                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />--}}
{{--                                                    </svg>--}}
{{--                                                </a>--}}
{{--                                                <button wire:click="deleteTask({{ $task->id }})"--}}
{{--                                                        wire:confirm="آیا از حذف این تسک اطمینان دارید؟"--}}
{{--                                                        class="text-slate-400 hover:text-red-500 p-1">--}}
{{--                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />--}}
{{--                                                    </svg>--}}
{{--                                                </button>--}}
                                            </div>
                                        </div>

                                        {{-- Footer Info: Date, Time, Category --}}
                                        <div class="flex flex-wrap items-center gap-2 mt-2">

                                            {{-- Date Badge --}}
                                            <div class="flex items-center gap-1.5 text-[10px] px-2 py-1 rounded-md bg-slate-50 border border-slate-100 {{ $task->due_date < now() ? 'text-orange-400 font-bold bg-red-50 border-red-100' : 'text-slate-500' }}">
                                                <i class="far fa-calendar-alt"></i>
                                                <span>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($task->due_date))->format('%A، %d %B') }}</span>
                                            </div>

                                            {{-- Time Badge (NEW) --}}
                                            @if($task->start_time)
                                                <div class="flex items-center gap-1.5 text-[10px] text-slate-500 px-2 py-1 rounded-md bg-slate-50 border border-slate-100" dir="ltr">
                                                    <span class="font-mono font-bold">
                                                        {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }}
                                                        @if($task->end_time)
                                                            - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}
                                                        @endif
                                                    </span>
                                                    <i class="far fa-clock text-slate-400 text-[9px]"></i>
                                                </div>
                                            @endif

                                            {{-- Category Badge --}}
                                            @if($task->category)
                                                <span class="text-[10px] flex items-center gap-1 bg-{{$task->category->color}}-50 text-{{$task->category->color}}-600 px-2 py-1 rounded-full border border-{{$task->category->color}}-100 ml-auto sm:ml-0">
                                                    <span class="w-1.5 h-1.5 rounded-full"
                                                          style="background-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}66"
                                                    ></span>
                                                    {{ $task->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($this->activeTab !== 'all' && $this->completedTasks->isEmpty())
                    <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-slate-400 text-sm">همه کارها انجام شده! 🎉</p>
                    </div>
                @endif
                @if($this->completedTasks->isNotEmpty())
                    <div x-data="{ expanded: false }" class="pt-4 border-t border-slate-100">

                        <div @click="expanded = !expanded"
                             class="flex items-center gap-2 mb-3 px-1 opacity-70 cursor-pointer hover:opacity-100 transition select-none">
                            <div class="w-5 h-5 flex items-center justify-center transition-transform duration-200"
                                 :class="expanded ? 'rotate-0' : 'rotate-90'">
                                <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-600">انجام شده</span>
                            <span
                                class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold">{{ $this->completedTasks->count() }}</span>
                        </div>

                        <div x-show="expanded"
                             x-collapse
                             class="space-y-2">
                            @foreach($this->completedTasks as $task)
                                <div wire:key="completed-{{ $task->id }}"
                                     class="group flex items-center gap-3 p-3 border border-transparent bg-slate-50 rounded-xl hover:bg-white hover:border-slate-200 transition-all cursor-pointer opacity-60 hover:opacity-100">

                                    <label class="relative flex items-center cursor-pointer p-1">
                                        <input type="checkbox" wire:click="toggleStatus({{ $task->id }})" checked
                                               class="peer sr-only">
                                        <div
                                            class="w-5 h-5 border-2 border-slate-300 bg-slate-300 rounded-md peer-checked:bg-green-500 peer-checked:border-green-500 flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </label>

                                    <div class="flex-1 flex items-center justify-between">
                                        <span
                                            class="text-slate-500 line-through text-sm decoration-slate-400 decoration-1">{{ $task->title }}</span>

                                        <span class="text-[10px] text-slate-400 ml-2">
                                {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($task->due_date))->format('Y/m/d') }}
                            </span>
                                    </div>

                                    <button wire:click="deleteTask({{ $task->id }})"
                                            wire:confirm="آیا از حذف این تسک اطمینان دارید؟"
                                            class="text-slate-300 hover:text-red-500 p-1 opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>
</div>
