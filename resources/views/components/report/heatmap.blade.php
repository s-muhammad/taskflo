@props(['cells' => [], 'padding' => 0])
@php
    $levelClasses = [
        0 => 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500',
        1 => 'bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300',
        2 => 'bg-indigo-200 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200',
        3 => 'bg-indigo-400 dark:bg-indigo-600 text-white',
        4 => 'bg-indigo-600 dark:bg-indigo-500 text-white',
    ];
    $headers = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
@endphp
<div>
    <div class="grid grid-cols-7 gap-1.5 mb-1">
        @foreach($headers as $letter)
            <div class="text-center text-[10px] font-bold text-slate-400 dark:text-slate-500">{{ $letter }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-1.5">
        @for($i = 0; $i < $padding; $i++)
            <div class="h-10"></div>
        @endfor

        @foreach($cells as $cell)
            <div class="h-10 rounded-lg flex items-center justify-center text-[11px] font-bold transition-all {{ $levelClasses[$cell['level']] ?? $levelClasses[0] }} {{ !empty($cell['isToday']) ? 'ring-2 ring-indigo-500 ring-inset' : '' }}"
                 title="{{ fa_digits($cell['day']) }}">
                {{ fa_digits($cell['day']) }}
            </div>
        @endforeach
    </div>

    <div class="flex items-center justify-end gap-1.5 mt-3 text-[10px] text-slate-400 dark:text-slate-500">
        <span>کم</span>
        @foreach([0, 1, 2, 3, 4] as $level)
            <span class="w-3.5 h-3.5 rounded-md {{ $levelClasses[$level] }}"></span>
        @endforeach
        <span>زیاد</span>
    </div>
</div>
