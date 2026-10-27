@props(['title' => null, 'icon' => null, 'pill' => null])
<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 shadow-soft']) }}>
    @if($title)
        <div class="flex items-center justify-between gap-2 mb-3">
            <h3 class="font-bold tracking-tight text-slate-900 dark:text-white text-xs flex items-center gap-2 min-w-0">
                @if($icon)
                    <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0">
                        <i class="fas {{ $icon }} text-[11px]" aria-hidden="true"></i>
                    </span>
                @endif
                <span class="truncate">{{ $title }}</span>
            </h3>
            @if(!is_null($pill) && $pill !== '')
                <span class="text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-full shrink-0">{{ $pill }}</span>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
