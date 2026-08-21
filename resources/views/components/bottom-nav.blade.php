{{-- نویگیشن پایین موبایل (شبیه اپ نیتیو) — فقط زیر lg نمایش داده می‌شود و سایدبار دسکتاپ دست‌نخورده می‌ماند --}}
@php
    $navItems = [
        ['label' => 'پیشخوان', 'route' => 'dashboard', 'pattern' => 'dashboard', 'icon' => 'fa-home'],
        ['label' => 'تسک', 'route' => 'task', 'pattern' => 'task*', 'icon' => 'fa-check-square'],
        ['label' => 'برنامه ماهانه', 'route' => 'monthly.calendar', 'pattern' => 'monthly.calendar', 'icon' => 'fa-calendar-alt'],
        ['label' => 'پروفایل', 'route' => 'profile', 'pattern' => 'profile', 'icon' => 'fa-user'],
    ];
@endphp

<nav class="fixed bottom-0 inset-x-0 z-30 lg:hidden bg-slate-900/95 backdrop-blur-md border-t border-white/10
     shadow-[0_-8px_24px_rgba(15,23,42,0.25)]"
     style="padding-bottom: env(safe-area-inset-bottom);"
     aria-label="ناوبری اصلی">

    <div class="grid grid-cols-5 items-stretch h-16 max-w-lg mx-auto px-2">

        @foreach(array_slice($navItems, 0, 2) as $item)
            <a href="{{ route($item['route']) }}" wire:navigate
               @if(request()->routeIs($item['pattern'])) aria-current="page" @endif
               class="flex flex-col items-center justify-center gap-1 transition-colors duration-200
                  {{ request()->routeIs($item['pattern']) ? 'text-indigo-400' : 'text-slate-400 hover:text-white' }}">
                <i class="fas {{ $item['icon'] }} text-lg transition-transform duration-200 {{ request()->routeIs($item['pattern']) ? 'scale-110' : '' }}"></i>
                <span class="text-[10px] leading-none {{ request()->routeIs($item['pattern']) ? 'font-bold' : 'font-medium' }}">{{ $item['label'] }}</span>
            </a>
        @endforeach

        {{-- دکمه افزودن تسک (FAB برجسته وسط نوار) --}}
        <div class="relative flex justify-center">
            <a href="{{ route('task.form') }}" wire:navigate
               title="افزودن تسک جدید"
               aria-label="افزودن تسک جدید"
               class="absolute -top-6 w-14 h-14 rounded-full bg-indigo-500 hover:bg-indigo-400 text-white
                  flex items-center justify-center shadow-lg shadow-indigo-500/40 ring-4 ring-slate-900
                  transition-all duration-200 active:scale-90">
                <i class="fas fa-plus text-xl"></i>
            </a>
            <span class="absolute bottom-1.5 text-[10px] font-medium text-slate-400">افزودن</span>
        </div>

        @foreach(array_slice($navItems, 2) as $item)
            <a href="{{ route($item['route']) }}" wire:navigate
               @if(request()->routeIs($item['pattern'])) aria-current="page" @endif
               class="flex flex-col items-center justify-center gap-1 transition-colors duration-200
                  {{ request()->routeIs($item['pattern']) ? 'text-indigo-400' : 'text-slate-400 hover:text-white' }}">
                <i class="fas {{ $item['icon'] }} text-lg transition-transform duration-200 {{ request()->routeIs($item['pattern']) ? 'scale-110' : '' }}"></i>
                <span class="text-[10px] leading-none {{ request()->routeIs($item['pattern']) ? 'font-bold' : 'font-medium' }}">{{ $item['label'] }}</span>
            </a>
        @endforeach

    </div>
</nav>
