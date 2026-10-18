@props(['percent' => 0, 'color' => '#6366f1'])
@php($pct = max(0, min(100, (float) $percent)))
<div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden"
     role="progressbar" aria-valuenow="{{ round($pct) }}" aria-valuemin="0" aria-valuemax="100">
    <div class="h-full rounded-full transition-all" style="width: {{ $pct }}%; background-color: {{ $color }};"></div>
</div>
