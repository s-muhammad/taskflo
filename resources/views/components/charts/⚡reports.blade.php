<?php

use App\Models\Category;
use App\Models\Task;
use App\Traits\HasJalaliCalendar;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

new class extends Component {
    use HasJalaliCalendar {
        nextMonth as protected jalaliNextMonth;
    }

    public array $colorsMap = [
        'slate'   => '#64748b', 'red'     => '#ef4444', 'orange'  => '#f97316',
        'amber'   => '#f59e0b', 'yellow'  => '#eab308', 'lime'    => '#84cc16',
        'green'   => '#10b981', 'emerald' => '#10b981', 'teal'    => '#14b8a6',
        'cyan'    => '#06b6d4', 'sky'     => '#0ea5e9', 'blue'    => '#3b82f6',
        'indigo'  => '#6366f1', 'violet'  => '#8b5cf6', 'purple'  => '#a855f7',
        'fuchsia' => '#d946ef', 'pink'    => '#ec4899', 'rose'    => '#f43f5e',
    ];

    public function mount()
    {
        $this->initCalendar();
    }

    // اجازه نده به ماه‌های آینده برود
    public function nextMonth()
    {
        $this->jalaliNextMonth();

        $now = Jalalian::now();
        if ($this->calendarYear > $now->getYear()
            || ($this->calendarYear === $now->getYear() && $this->calendarMonth > $now->getMonth())) {
            $this->calendarYear = $now->getYear();
            $this->calendarMonth = $now->getMonth();
        }
    }

    #[Computed]
    public function isCurrentMonth()
    {
        $now = Jalalian::now();

        return $this->calendarYear === $now->getYear() && $this->calendarMonth === $now->getMonth();
    }

    #[Computed]
    public function period()
    {
        $structure = $this->calendarStructure;

        $start = (new Jalalian($structure['year'], $structure['month'], 1))->toCarbon()->startOfDay();
        $end = (new Jalalian($structure['year'], $structure['month'], $structure['total_days']))->toCarbon()->endOfDay();

        return [
            'year' => $structure['year'],
            'month' => $structure['month'],
            'total_days' => $structure['total_days'],
            'start' => $start,
            'end' => $end,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'name' => $this->monthName,
        ];
    }

    /**
     * گرادیان مخروطی دونات وضعیت با فاصله‌ی سفید بین سگمنت‌ها.
     */
    protected function donutGradient(int $completed, int $pending, int $overdue, int $total, float $gap = 0.6): string
    {
        $segments = [
            [$completed, '#10b981'], // انجام‌شده
            [$pending, '#f59e0b'],   // جاری
            [$overdue, '#fb7185'],   // تاخیر (rose-400)
        ];

        $nonZero = array_values(array_filter($segments, fn ($s) => $s[0] > 0));

        if (count($nonZero) <= 1) {
            $color = $nonZero[0][1] ?? '#e2e8f0';

            return "conic-gradient($color 0% 100%)";
        }

        $stops = [];
        $cursor = 0.0;

        foreach ($nonZero as [$count, $color]) {
            $span = $count / $total * 100;
            $start = $cursor;
            $end = $cursor + $span;
            $colorStart = min($end, $start + $gap);
            $colorEnd = max($colorStart, $end - $gap);

            $stops[] = sprintf('white %s%% %s%%', round($start, 3), round($colorStart, 3));
            $stops[] = sprintf('%s %s%% %s%%', $color, round($colorStart, 3), round($colorEnd, 3));
            $stops[] = sprintf('white %s%% %s%%', round($colorEnd, 3), round($end, 3));

            $cursor = $end;
        }

        return 'conic-gradient('.implode(', ', $stops).')';
    }

    #[Computed]
    public function report()
    {
        $userId = auth()->id();
        $period = $this->period;

        // تسک‌های انجام‌شده‌ی ماه به تفکیک روز (بر اساس تاریخ تکمیل = updated_at)
        $completedByDate = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$period['start'], $period['end']])
            ->selectRaw('DATE(updated_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        // عملکرد هفتگی: شنبه تا جمعه
        // Carbon dayOfWeek: 0=یکشنبه ... 6=شنبه → نگاشت به شنبه=0 ... جمعه=6
        $weeklyCounts = [0, 0, 0, 0, 0, 0, 0];
        foreach ($completedByDate as $date => $count) {
            $index = (Carbon::parse($date)->dayOfWeek + 1) % 7;
            $weeklyCounts[$index] += (int) $count;
        }
        $maxWeekly = max($weeklyCounts);

        // وضعیت تسک‌های ماه بر اساس سررسید
        $today = Carbon::today();
        $statusRow = Task::where('user_id', $userId)
            ->whereBetween('due_date', [$period['start_date'], $period['end_date']])
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'pending' AND due_date >= ? THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'pending' AND due_date < ? THEN 1 ELSE 0 END) as overdue
            ", [$today->toDateString(), $today->toDateString()])
            ->first();

        $completed = (int) ($statusRow->completed ?? 0);
        $pending = (int) ($statusRow->pending ?? 0);
        $overdue = (int) ($statusRow->overdue ?? 0);
        $total = (int) ($statusRow->total ?? 0);
        $completionRate = $total > 0 ? (int) round($completed / $total * 100) : 0;

        // روزهای متوالی: روزهای پشت‌سرهم تا امروز که حداقل یک تسک انجام‌شده داشته‌اند
        $completedDates = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->selectRaw('DATE(updated_at) as d')
            ->distinct()
            ->pluck('d')
            ->all();
        $completedSet = array_flip($completedDates);

        $streak = 0;
        $cursor = Carbon::today();
        while (isset($completedSet[$cursor->toDateString()])) {
            $streak++;
            $cursor->subDay();
        }

        // پربازده‌ترین روز هفته (اگر مساوی بود، روز زودتر = ایندکس کوچک‌تر)
        $weekdayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $peakWeekday = ['name' => null, 'count' => 0];
        if ($maxWeekly > 0) {
            $index = array_search($maxWeekly, $weeklyCounts, true);
            $peakWeekday = ['name' => $weekdayNames[$index], 'count' => (int) $maxWeekly];
        }

        // تقویم حرارتی ماه جاری
        $maxDaily = (int) ($completedByDate->max() ?? 0);
        $todayJalali = Jalalian::now();
        $cells = [];
        for ($day = 1; $day <= $period['total_days']; $day++) {
            $gregorian = (new Jalalian($period['year'], $period['month'], $day))->toCarbon();
            $count = (int) ($completedByDate[$gregorian->toDateString()] ?? 0);

            $level = 0;
            if ($count > 0 && $maxDaily > 0) {
                $level = (int) min(4, max(1, ceil($count / $maxDaily * 4)));
            }

            $isToday = $period['year'] === $todayJalali->getYear()
                && $period['month'] === $todayJalali->getMonth()
                && $day === $todayJalali->getDay();

            $cells[] = ['day' => $day, 'level' => $level, 'isToday' => $isToday];
        }

        // ساعات تمرکز (همان داده‌ی رادار قبلی: ساعتِ تکمیل تسک‌ها)
        $timeRanges = ['morning' => 0, 'afternoon' => 0, 'evening' => 0, 'night' => 0];
        $completedTimestamps = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$period['start'], $period['end']])
            ->pluck('updated_at');

        foreach ($completedTimestamps as $timestamp) {
            $hour = Carbon::parse($timestamp)->hour;
            match (true) {
                $hour >= 6 && $hour < 12 => $timeRanges['morning']++,
                $hour >= 12 && $hour < 18 => $timeRanges['afternoon']++,
                $hour >= 18 && $hour <= 23 => $timeRanges['evening']++,
                default => $timeRanges['night']++,
            };
        }

        $focusTotal = array_sum($timeRanges);
        $focusRanges = [
            ['key' => 'morning', 'label' => 'صبح', 'sub' => '۶ تا ۱۲', 'count' => $timeRanges['morning']],
            ['key' => 'afternoon', 'label' => 'ظهر', 'sub' => '۱۲ تا ۱۸', 'count' => $timeRanges['afternoon']],
            ['key' => 'evening', 'label' => 'عصر', 'sub' => '۱۸ تا ۲۴', 'count' => $timeRanges['evening']],
            ['key' => 'night', 'label' => 'شب', 'sub' => '۰ تا ۶', 'count' => $timeRanges['night']],
        ];

        foreach ($focusRanges as &$range) {
            $range['pct'] = $focusTotal > 0 ? (int) round($range['count'] / $focusTotal * 100) : 0;
        }
        unset($range);

        $peakFocus = null;
        if ($focusTotal > 0) {
            $peakFocus = collect($focusRanges)->firstWhere('count', max($timeRanges));
        }

        // دسته‌بندی‌ها (بر اساس سررسید ماه)
        $categories = Category::query()
            ->where('user_id', $userId)
            ->withCount([
                'tasks as total_tasks' => function ($query) use ($userId, $period) {
                    $query->where('user_id', $userId)
                        ->whereBetween('due_date', [$period['start_date'], $period['end_date']]);
                },
                'tasks as completed_tasks' => function ($query) use ($userId, $period) {
                    $query->where('user_id', $userId)
                        ->where('status', 'completed')
                        ->whereBetween('due_date', [$period['start_date'], $period['end_date']]);
                },
            ])
            ->whereHas('tasks', function ($query) use ($userId, $period) {
                $query->where('user_id', $userId)
                    ->whereBetween('due_date', [$period['start_date'], $period['end_date']]);
            })
            ->get()
            ->map(function ($category) {
                $percent = $category->total_tasks > 0
                    ? (int) round($category->completed_tasks / $category->total_tasks * 100)
                    : 0;

                return [
                    'name' => $category->name,
                    'total' => (int) $category->total_tasks,
                    'completed' => (int) $category->completed_tasks,
                    'percent' => $percent,
                    'color' => $this->colorsMap[$category->color] ?? '#6366f1',
                ];
            });

        // نوار بینش (نیم‌فاصله بین نام و «ها»)
        if ($total >= 5 && $completed > 0 && $peakWeekday['name'] && $peakFocus) {
            $insight = 'این ماه بیشترین فعالیتت '.$peakFocus['label']."\u{200C}".'ها بوده و '.$peakWeekday['name']."\u{200C}".'ها پربازده‌ترین روزهایت هستند.';
        } else {
            $insight = 'هنوز داده‌ی کافی نیست؛ با ثبت تسک‌های بیشتر، تحلیل ساعات تمرکز و روزهای پربازده‌ات اینجا ظاهر می‌شود.';
        }

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'overdue' => $overdue,
            'completion_rate' => $completionRate,
            'streak' => $streak,
            'peak_weekday' => $peakWeekday,
            'weekly' => [
                'names' => $weekdayNames,
                'counts' => $weeklyCounts,
                'max' => $maxWeekly,
            ],
            'heatmap' => [
                'cells' => $cells,
                'padding' => (int) ($this->calendarStructure['start_padding'] ?? 0),
                'max' => $maxDaily,
            ],
            'categories' => $categories,
            'focus' => [
                'ranges' => $focusRanges,
                'peak' => $peakFocus,
                'total' => $focusTotal,
            ],
            'donut_gradient' => $this->donutGradient($completed, $pending, $overdue, $total),
            'insight' => $insight,
        ];
    }
};
?>

<main class="flex-1 overflow-y-auto bg-slate-50 p-4 lg:p-8">
    <div class="space-y-6" wire:key="reports-{{ $this->period['year'] }}-{{ $this->period['month'] }}">
        @php($r = $this->report)

        {{-- سرتیتر محتوا + سوئیچر ماه --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-base lg:text-lg font-bold tracking-tight text-slate-900">گزارش عملکرد</h2>
                <p class="text-xs text-slate-500 mt-0.5">الگوهای رفتاری و پیشرفت پروژه‌هایت در یک نگاه</p>
            </div>

            <div class="bg-slate-100 rounded-xl p-1 flex items-center gap-1">
                <button type="button" wire:click="prevMonth" title="ماه قبل" aria-label="ماه قبل"
                        class="p-2 hover:bg-white rounded-lg transition text-slate-600">
                    <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                </button>
                <div class="bg-white border border-slate-200 rounded-lg px-4 py-1.5 font-bold text-indigo-700 min-w-[130px] text-center text-sm">
                    {{ $this->period['name'] }} @fa($this->period['year'])
                </div>
                <button type="button" wire:click="nextMonth" title="ماه بعد" aria-label="ماه بعد"
                        @disabled($this->isCurrentMonth)
                        class="p-2 hover:bg-white rounded-lg transition text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        {{-- نوار بینش --}}
        <div class="flex items-start gap-2 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3">
            <i class="fas fa-star text-[10px] text-indigo-500 mt-1 shrink-0" aria-hidden="true"></i>
            <p class="text-xs leading-relaxed text-indigo-700">{{ $r['insight'] }}</p>
        </div>

        {{-- چهار کارت آمار --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <x-report.stat-card
                label="کل تسک‌ها"
                :value="fa_digits($r['total'])"
                hint="ثبت‌شده در این ماه"
                icon="fa-layer-group" />
            <x-report.stat-card
                label="نرخ تکمیل"
                :value="fa_digits($r['completion_rate']).'٪'"
                :hint="fa_digits($r['completed']).' از '.fa_digits($r['total']).' تسک انجام شده'"
                icon="fa-check-circle" />
            <x-report.stat-card
                label="روزهای متوالی"
                :value="fa_digits($r['streak'])"
                hint="روز فعالیت پشت‌سرهم"
                icon="fa-fire" />
            <x-report.stat-card
                label="پربازده‌ترین روز"
                :value="$r['peak_weekday']['name'] ?? '—'"
                :hint="fa_digits($r['peak_weekday']['count']).' تسک'"
                icon="fa-calendar-check" />
        </div>

        {{-- ردیف دوم --}}
        <div class="flex flex-wrap gap-4">
            <x-report.card title="وضعیت کلی" icon="fa-chart-pie" class="flex-1 min-w-[280px]">
                @if($r['total'] >= 3)
                    <div class="flex justify-center">
                        <div class="relative w-[200px] h-[200px] rounded-full"
                             style="background: {{ $r['donut_gradient'] }};">
                            <div class="absolute inset-0 m-auto w-[156px] h-[156px] bg-white rounded-full"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-slate-800">@fa($r['completion_rate'])٪</span>
                                <span class="text-[10px] text-slate-400">تکمیل‌شده</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="h-[200px] flex flex-col items-center justify-center text-center">
                        <i class="fas fa-chart-pie text-slate-300 text-2xl" aria-hidden="true"></i>
                        <p class="text-xs text-slate-400 mt-2">برای نمایش نمودار، حداقل ۳ تسک لازم است.</p>
                    </div>
                @endif

                <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                    <div>
                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> انجام‌شده
                        </div>
                        <p class="text-sm font-bold text-slate-800 mt-1">@fa($r['completed'])</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> جاری
                        </div>
                        <p class="text-sm font-bold text-slate-800 mt-1">@fa($r['pending'])</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span> تاخیر
                        </div>
                        <p class="text-sm font-bold text-slate-800 mt-1">@fa($r['overdue'])</p>
                    </div>
                </div>
            </x-report.card>

            <x-report.card title="عملکرد هفتگی" icon="fa-calendar-week" class="flex-1 min-w-[280px]">
                <div class="flex items-end justify-between gap-1.5 h-40">
                    @foreach($r['weekly']['names'] as $index => $name)
                        @php($count = $r['weekly']['counts'][$index])
                        @php($isZero = $count <= 0)
                        @php($isPeak = !$isZero && $count === $r['weekly']['max'])
                        @php($height = $isZero ? 6 : max(24, (int) round($count / max(1, $r['weekly']['max']) * 160)))
                        <div class="flex-1 flex flex-col items-center justify-end gap-1 h-full">
                            <span class="text-[10px] font-bold text-slate-500 leading-none h-3">{{ $isZero ? '' : fa_digits($count) }}</span>
                            <div class="w-full max-w-[28px] rounded-t-md {{ $isZero ? 'bg-slate-200' : ($isPeak ? 'bg-indigo-600' : 'bg-indigo-200') }}"
                                 style="height: {{ $height }}px"></div>
                        </div>
                    @endforeach
                </div>
                <div class="flex items-start justify-between gap-1.5 mt-2">
                    @foreach($r['weekly']['names'] as $name)
                        <span class="flex-1 text-center text-[10px] leading-tight text-slate-400">{{ $name }}</span>
                    @endforeach
                </div>
            </x-report.card>
        </div>

        {{-- ردیف سوم --}}
        <div class="flex flex-wrap gap-4">
            <x-report.card
                title="تقویم فعالیت ماه"
                icon="fa-calendar-alt"
                pill="{{ $this->period['name'].' '.fa_digits($this->period['year']) }}"
                class="grow basis-full lg:basis-[58%] min-w-0">
                <x-report.heatmap :cells="$r['heatmap']['cells']" :padding="$r['heatmap']['padding']" />
            </x-report.card>

            <div class="grow basis-full lg:basis-[38%] min-w-0 flex flex-col gap-4">
                <x-report.card title="عملکرد بر اساس دسته‌بندی" icon="fa-layer-group" class="flex-1">
                    @forelse($r['categories'] as $category)
                        <div class="mb-3 last:mb-0">
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <span class="text-xs font-medium text-slate-600 truncate">{{ $category['name'] }}</span>
                                <span class="text-[11px] text-slate-400 shrink-0">
                                    @fa($category['completed']) از @fa($category['total']) · @fa($category['percent'])٪
                                </span>
                            </div>
                            <x-report.progress-bar :percent="$category['percent']" :color="$category['color']" />
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <i class="fas fa-layer-group text-slate-300 text-xl" aria-hidden="true"></i>
                            <p class="text-xs text-slate-400 mt-2">این ماه تسکی در دسته‌بندی‌ها ثبت نشده است.</p>
                        </div>
                    @endforelse
                </x-report.card>

                <x-report.card
                    title="ساعات تمرکز"
                    icon="fa-crosshairs"
                    :pill="$r['focus']['peak'] ? 'اوج: '.$r['focus']['peak']['label'] : null"
                    class="flex-1">
                    @if($r['focus']['total'] > 0)
                        @foreach($r['focus']['ranges'] as $range)
                            <div class="mb-3 last:mb-0">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="text-xs font-medium text-slate-600">
                                        {{ $range['label'] }}
                                        <span class="text-slate-400 text-[10px]">{{ $range['sub'] }}</span>
                                    </span>
                                    <span class="text-[11px] text-slate-400 shrink-0">@fa($range['pct'])٪</span>
                                </div>
                                <x-report.progress-bar
                                    :percent="$range['pct']"
                                    :color="$r['focus']['peak'] && $r['focus']['peak']['key'] === $range['key'] ? '#4f46e5' : '#c7d2fe'" />
                            </div>
                        @endforeach
                    @else
                        <div class="py-8 text-center">
                            <i class="fas fa-crosshairs text-slate-300 text-xl" aria-hidden="true"></i>
                            <p class="text-xs text-slate-400 mt-2">این ماه تسک انجام‌شده‌ای برای تحلیل ساعات تمرکز ثبت نشده است.</p>
                        </div>
                    @endif
                </x-report.card>
            </div>
        </div>
    </div>
</main>
