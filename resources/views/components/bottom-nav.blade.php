@php
    $navItems = [
        ['label' => 'پیشخوان', 'route' => 'dashboard', 'icon' => 'fa-home'],
        ['label' => 'برنامه ماهانه', 'route' => 'monthly.calendar', 'icon' => 'fa-calendar-alt'],
        ['label' => 'گزارش', 'route' => 'reports', 'icon' => 'fa-chart-line'],
        ['label' => 'پروفایل', 'route' => 'profile', 'icon' => 'fa-user'],
    ];
@endphp

<nav class="fixed bottom-0 inset-x-0 z-40 lg:hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 shadow-[0_-8px_30px_rgb(0,0,0,0.04)] px-2 pb-safe">
    <div class="flex items-center justify-between h-16 max-w-md mx-auto relative">

        {{-- دو آیتم سمت راست --}}
        <div class="flex flex-1 justify-around">
            @foreach(array_slice($navItems, 0, 2) as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px] {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                    <i class="fas {{ $item['icon'] }} text-lg"></i>
                    <span class="text-[10px] font-bold">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- دکمه افزودن در وسط --}}
        <div class="flex-shrink-0 -mt-8 px-4">
            <a href="{{ route('task.form') }}" wire:navigate
               class="flex items-center justify-center w-14 h-14 bg-indigo-600 rounded-2xl text-white shadow-lg shadow-indigo-500/40 active:scale-90 transition-all border-4 border-slate-100 dark:border-slate-950">
                <i class="fas fa-plus text-xl"></i>
            </a>
        </div>

        {{-- دو آیتم سمت چپ --}}
        <div class="flex flex-1 justify-around">
            @foreach(array_slice($navItems, 2, 2) as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px] {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                    <i class="fas {{ $item['icon'] }} text-lg"></i>
                    <span class="text-[10px] font-bold">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

    </div>
</nav>
