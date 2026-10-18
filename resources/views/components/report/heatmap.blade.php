@props(['cells' => [], 'padding' => 0])
@php
    $levelClasses = [
        0 => 'bg-slate-100 text-slate-400',
        1 => 'bg-indigo-100 text-indigo-700',
        2 => 'bg-indigo-200 text-indigo-800',
        3 => 'bg-indigo-400 text-white',
        4 => 'bg-indigo-600 text-white',
    ];
    $headers = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
@endphp
<div>
    <div class="grid grid-cols-7 gap-2 mb-1.5">
        @foreach($headers as $letter)
            <div class="text-center text-[11px] font-bold text-slate-400">{{ $letter }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-2">
        @for($i = 0; $i < $padding; $i++)
            <div class="h-12"></div>
        @endfor

        @foreach($cells as $cell)
            <div class="h-12 rounded-lg flex items-center justify-center text-xs font-bold {{ $levelClasses[$cell['level']] ?? $levelClasses[0] }} {{ !empty($cell['isToday']) ? 'ring-2 ring-slate-800 ring-inset' : '' }}"
                 title="{{ fa_digits($cell['day']) }}">
                {{ fa_digits($cell['day']) }}
            </div>
        @endforeach
    </div>

    <div class="flex items-center justify-end gap-1.5 mt-3 text-[10px] text-slate-400">
        <span>کم</span>
        @foreach([0, 1, 2, 3, 4] as $level)
            <span class="w-3.5 h-3.5 rounded {{ $levelClasses[$level] }}"></span>
        @endforeach
        <span>زیاد</span>
    </div>
</div>
