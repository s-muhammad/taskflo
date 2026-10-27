<?php

use App\Models\Task;
use App\Traits\HasJalaliCalendar;
use Livewire\Attributes\On;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

new class extends Component {
    use HasJalaliCalendar;

    public $colorsMap = [
        'slate'   => '#64748b', 'red'     => '#ef4444', 'orange'  => '#f97316',
        'amber'   => '#f59e0b', 'yellow'  => '#eab308', 'lime'    => '#84cc16',
        'green'   => '#22c55e', 'emerald' => '#10b981', 'teal'    => '#14b8a6',
        'cyan'    => '#06b6d4', 'sky'     => '#0ea5e9', 'blue'    => '#3b82f6',
        'indigo'  => '#6366f1', 'violet'  => '#8b5cf6', 'purple'  => '#a855f7',
        'fuchsia' => '#d946ef', 'pink'    => '#ec4899', 'rose'    => '#f43f5e'
    ];
    public function mount()
    {
        $this->initCalendar();
    }

    public function getCalendarDataProperty()
    {
        $structure = $this->calendarStructure;

        if (!$structure) {
            return [
                'start_padding' => 0,
                'total_days' => 0,
                'tasks_grouped' => [],
                'month_name' => '',
            ];
        }

        $startGregorian = (new Jalalian($structure['year'], $structure['month'], 1))->toCarbon();
        $endGregorian = (new Jalalian($structure['year'], $structure['month'], $structure['total_days']))->toCarbon();

        $tasks = Task::where('user_id', auth()->id())
            ->whereBetween('due_date', [$startGregorian->startOfDay(), $endGregorian->endOfDay()])
            ->with('category')
            ->get()
            ->groupBy(function ($task) {
                return Jalalian::fromCarbon($task->due_date)->getDay();
            });

        return [
            'start_padding' => $structure['start_padding'],
            'total_days' => $structure['total_days'],
            'tasks_grouped' => $tasks,
            'month_name' => $this->monthName,
        ];
    }

    #[On('refresh')]
    public function refresh(){}

    public function toggleTask($taskId)
    {
        $task = Task::find($taskId);
        if($task) {
            $task->update([
                'status' => $task->status === 'completed' ? 'pending' : 'completed'
            ]);
        }
    }

    public function deleteTask($taskId)
    {
        Task::where('id', $taskId)->where('user_id' , auth()->id() )->delete();
    }

    public function getDaysProperty()
    {
        $data = $this->calendarData;

        if(empty($data['total_days'])) return [];

        $days = [];
        $today = Jalalian::now();

        for ($day = 1; $day <= $data['total_days']; $day++) {
            $isToday = ($day == $today->getDay() &&
                $this->calendarMonth == $today->getMonth() &&
                $this->calendarYear == $today->getYear());

            $days[] = [
                'number' => $day,
                'isToday' => $isToday,
                'tasks' => $data['tasks_grouped'][$day] ?? [],
                'fullDate' => sprintf('%04d/%02d/%02d', $this->calendarYear, $this->calendarMonth, $day),
                'dayName' => (new Jalalian($this->calendarYear, $this->calendarMonth, $day))->format('%A')
            ];
        }
        return $days;
    }
};
?>

<div class="space-y-4">
    {{-- هدر تقویم با دکمه‌های جدید --}}
    <div class="flex items-center justify-between bg-white dark:bg-slate-900 p-1.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-soft sticky top-0 z-20">
        <button wire:click="prevMonth" class="p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition disabled:opacity-50 text-slate-600 dark:text-slate-400">
            <i class="fas fa-chevron-right text-sm"></i>
        </button>
        
        <div class="font-bold text-slate-900 dark:text-white text-base">
            {{ $this->calendarData['month_name'] }} @fa($calendarYear)
        </div>

        <button wire:click="nextMonth" class="p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition disabled:opacity-50 text-slate-600 dark:text-slate-400">
            <i class="fas fa-chevron-left text-sm"></i>
        </button>
    </div>

    <div class="hidden md:grid grid-cols-7 gap-3 mb-1 px-2">
        @foreach(['شنبه', '۱شنبه', '۲شنبه', '۳شنبه', '۴شنبه', '۵شنبه', 'جمعه'] as $dayName)
            <div class="text-center text-sm font-bold {{ $dayName == 'جمعه' ? 'text-rose-400' : 'text-slate-400' }} uppercase tracking-widest">{{ $dayName }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-7 gap-2 lg:gap-3 pb-20 lg:pb-0" x-data>

        @for($i = 0; $i < $this->calendarData['start_padding']; $i++)
            <div class="min-h-[104px] bg-slate-50 dark:bg-slate-800/30 rounded-xl border border-dashed border-slate-200 dark:border-slate-800 hidden md:block"></div>
        @endfor

        @foreach($this->days as $day)
            <div
                @if($day['isToday']) id="today-cell" x-init="$nextTick(() => { $el.scrollIntoView({behavior: 'smooth', block: 'center'}) })" @endif
                class="min-h-[104px] {{ $day['isToday'] ? 'bg-white dark:bg-slate-900 border-2 border-indigo-500 shadow-soft' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800' }} rounded-xl p-2.5 relative group transition hover:shadow-soft-lg">

                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-1">
                        <span class="md:hidden text-[10px] font-medium {{ $day['dayName'] === 'جمعه' ? 'text-rose-500' : 'text-slate-400' }}">
                            {{ $day['dayName'] }}
                        </span>
                        <span class="text-sm font-bold {{ $day['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : ($day['dayName'] === 'جمعه' ? 'text-rose-600' : 'text-slate-900 dark:text-slate-100') }}">
                            @fa($day['number'])
                        </span>
                    </div>

                    @if($day['isToday'])
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    @endif
                </div>

                <div class="mt-2 space-y-1 overflow-hidden">
                    @foreach($day['tasks'] as $task)
                        <div
                            wire:key="task-{{ $task->id }}"
                            class="group/task flex items-center gap-2 px-2 py-1.5 rounded-lg text-[11px] font-medium transition-all dark:text-slate-200 border"
                            style="background-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}10; border-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}30"
                        >
                            <button type="button" wire:click="toggleTask({{ $task->id }})"
                                    class="shrink-0 w-3.5 h-3.5 rounded border border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all
                                    {{ $task->status == 'completed' ? 'bg-indigo-600 border-indigo-600 text-white' : '' }}">
                                @if($task->status == 'completed') <i class="fas fa-check text-[8px]"></i> @endif
                            </button>
                            <a class="cursor-pointer truncate flex-1" wire:click="$dispatchTo('task.modal','show', { task: {{ $task->id }} })">
                                <span class="{{ $task->status == 'completed' ? 'line-through opacity-50' : '' }}">
                                    {{ $task->title }}
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <a href="{{ route('task.form', ['date'=>$day['fullDate']] ) }}" wire:navigate>
                    <button class="absolute bottom-2 left-2 w-7 h-7 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center
                       opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-all text-slate-400 hover:bg-indigo-600 hover:text-white">
                        <i class="fas fa-plus text-[10px]"></i>
                    </button>
                </a>
            </div>
        @endforeach
    </div>
</div>
