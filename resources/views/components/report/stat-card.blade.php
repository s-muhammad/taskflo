@props(['label' => '', 'value' => '', 'hint' => null, 'icon' => null])
<div class="text-right bg-white rounded-lg border border-slate-200 p-4 flex items-start justify-between gap-2">
    <div class="min-w-0">
        <p class="text-xs font-medium text-slate-500 truncate">{{ $label }}</p>
        <p class="text-xl font-bold tracking-tight text-slate-900 mt-1">{{ $value }}</p>
        @if(!is_null($hint) && $hint !== '')
            <p class="text-[11px] text-slate-400 mt-1 truncate">{{ $hint }}</p>
        @endif
    </div>
    @if($icon)
        <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
            <i class="fas {{ $icon }} text-xs" aria-hidden="true"></i>
        </span>
    @endif
</div>
