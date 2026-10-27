@props(['label' => '', 'value' => '', 'hint' => null, 'icon' => null])
<div class="text-start bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 flex items-start justify-between gap-2 shadow-soft">
    <div class="min-w-0">
        <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 truncate">{{ $label }}</p>
        <p class="text-lg font-bold tracking-tight text-slate-900 dark:text-white mt-1">{{ $value }}</p>
        @if(!is_null($hint) && $hint !== '')
            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 truncate">{{ $hint }}</p>
        @endif
    </div>
    @if($icon)
        <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0">
            <i class="fas {{ $icon }} text-xs" aria-hidden="true"></i>
        </span>
    @endif
</div>
