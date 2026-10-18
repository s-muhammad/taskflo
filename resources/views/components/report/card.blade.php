@props(['title' => null, 'icon' => null, 'pill' => null])
<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-200 p-5']) }}>
    @if($title)
        <div class="flex items-center justify-between gap-2 mb-4">
            <h3 class="font-bold tracking-tight text-slate-900 text-sm flex items-center gap-2 min-w-0">
                @if($icon)
                    <span class="w-7 h-7 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                        <i class="fas {{ $icon }} text-xs" aria-hidden="true"></i>
                    </span>
                @endif
                <span class="truncate">{{ $title }}</span>
            </h3>
            @if(!is_null($pill) && $pill !== '')
                <span class="text-[10px] font-medium bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full shrink-0">{{ $pill }}</span>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
