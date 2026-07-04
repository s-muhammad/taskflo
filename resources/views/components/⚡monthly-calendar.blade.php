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
    // این متد خیلی مهم است. اگر نباشد، متغیرها null می‌مانند
    public function mount()
    {
        $this->initCalendar();
    }

    public function getCalendarDataProperty()
    {
        $structure = $this->calendarStructure;

        // لایه محافظتی: اگر به هر دلیلی ساختار null بود، یک آرایه خالی بده تا سایت کرش نکند
        if (!$structure) {
            return [
                'start_padding' => 0,
                'total_days' => 0,
                'tasks_grouped' => [],
                'month_name' => '',
            ];
        }

        // حالا با خیال راحت از structure استفاده می‌کنیم
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

        // اگر دیتا خالی بود (حالت خطا)، آرایه خالی برگردان
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

<div>
    <div class="flex items-center justify-between mb-6 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm sticky top-16 lg:top-0 z-20">
        <div class="flex items-center gap-3 lg:gap-4">
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2 font-bold text-indigo-700 min-w-[140px] text-center">
                {{ $this->calendarData['month_name'] }} {{ $calendarYear }}
            </div>
            <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
                <button wire:click="prevMonth" class="p-2 hover:bg-white rounded-lg transition disabled:opacity-50 text-slate-600">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
                <div class="w-[1px] h-4 bg-slate-300"></div>
                <button wire:click="nextMonth" class="p-2 hover:bg-white rounded-lg transition disabled:opacity-50 text-slate-600">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="hidden md:grid grid-cols-7 gap-4 mb-4 px-2">
        <div class="text-center text-sm font-bold text-slate-400 uppercase tracking-widest">شنبه</div>
        <div class="text-center text-sm font-bold text-slate-400 uppercase tracking-widest">۱شنبه</div>
        <div class="text-center text-sm font-bold text-slate-400 uppercase tracking-widest">۲شنبه</div>
        <div class="text-center text-sm font-bold text-slate-400 uppercase tracking-widest">۳شنبه</div>
        <div class="text-center text-sm font-bold text-slate-400 uppercase tracking-widest">۴شنبه</div>
        <div class="text-center text-sm font-bold text-slate-400 uppercase tracking-widest">۵شنبه</div>
        <div class="text-center text-sm font-bold text-rose-400 uppercase tracking-widest">جمعه</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-7 gap-3 lg:gap-4 pb-20 lg:pb-0" x-data>

        {{-- رندر کردن خانه‌های خالی اول ماه --}}
        @for($i = 0; $i < $this->calendarData['start_padding']; $i++)
            <div class="min-h-[120px] bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 hidden md:block"></div>
        @endfor

        {{-- رندر کردن روزهای واقعی --}}
        @foreach($this->days as $day)
            <div
                @if($day['isToday'])
                    id="today-cell"
                x-init="$nextTick(() => { $el.scrollIntoView({behavior: 'smooth', block: 'center'}) })"
                @endif
                class="day-cell min-h-[120px] {{ $day['isToday'] ? 'bg-white border-2 border-indigo-500 ring-4 ring-indigo-50' : 'bg-white border border-slate-200' }} rounded-2xl p-3 relative group transition hover:shadow-lg hover:-translate-y-1">

                <div class="flex items-center justify-between mb-2">
                    <div class="flex flex-col md:flex-row md:items-center gap-1">
                        <span class="md:hidden text-[10px] font-medium {{ $day['dayName'] === 'جمعه' ? 'text-rose-500' : 'text-slate-400' }}">
                            {{ $day['dayName'] }}
                        </span>
                        <span class="text-sm font-bold {{ $day['isToday'] ? 'text-indigo-600' : ($day['dayName'] === 'جمعه' ? 'text-rose-600' : 'text-slate-700') }}">
                            {{ $day['number'] }}
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
                            class="group/task flex items-center justify-between gap-1 px-2 py-1 rounded-md text-[13px] font-medium transition-all"
                            style="background-color: {{ $colorsMap[$task->category?->color] ?? '#cbd5e1' }}66"
                        >
                            <div class="flex items-center gap-1 truncate">
                                <input type="checkbox"
                                       wire:click="toggleTask({{ $task->id }})"
                                       @checked($task->status === 'completed')
                                       class="w-3 h-3 rounded border-slate-300">
                                <a class="cursor-pointer" title="نمایش" wire:click="$dispatchTo('task.modal','show', { task: {{ $task->id }} })">
                                    <span class="{{ $task->status == 'completed' ? 'line-through opacity-50' : '' }}">
                                        {{ $task->title }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="h-8"></div>
                <a href="{{ route('task.form', ['date'=>$day['fullDate']] ) }}" wire:navigate>
                    <button
                        {{--                        wire:click="$dispatchTo('task.task-form', 'open-modal', { date: '{{ $day['fullDate'] }}' })"--}}
                        class="absolute bottom-2 left-2 w-7 h-7 bg-slate-100 rounded-full flex items-center justify-center
                       md:group-hover:opacity-100 opacity-100 md:opacity-0 transition-all text-slate-400 hover:bg-indigo-500 hover:text-white">
                        <i class="fas fa-plus text-[10px]"></i>
                    </button>
                </a>
            </div>
        @endforeach
    </div>
</div>
