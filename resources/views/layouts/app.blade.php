<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <title>{{ setting('site_title') }}</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="{{asset('/font/FontAwesome/css/all.css')}}" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        function applyTheme() {
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        applyTheme();
        document.addEventListener('livewire:navigated', applyTheme);
    </script>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100">

<div class="flex h-screen overflow-hidden relative" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.darkMode);
    }
}">
    {{-- سایدبار دسکتاپ --}}
    <aside id="sidebar" 
           class="hidden lg:flex w-60 bg-white dark:bg-slate-900 border-e border-slate-200 dark:border-slate-800 flex-col p-4 shadow-soft shrink-0">

        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-soft text-white shrink-0">
                <img src="{{ setting('site_logo') }}" alt="" class="w-6 h-6 object-contain">
            </div>
            <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white truncate">{{ setting('site_title') }}</span>
        </div>

        <nav class="space-y-1.5 flex-1 overflow-y-auto custom-scrollbar">
            @php
                $navLinks = [
                    ['route' => 'dashboard', 'label' => 'پیشخوان', 'icon' => 'fa-home'],
                    ['route' => 'monthly.calendar', 'label' => 'برنامه ماهانه', 'icon' => 'fa-calendar-alt'],
                    ['route' => 'reports', 'label' => 'گزارش‌ها', 'icon' => 'fa-chart-line'],
                    ['route' => 'profile', 'label' => 'پروفایل', 'icon' => 'fa-user'],
                ];
            @endphp

            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}" wire:navigate
                   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm transition-all rounded-xl font-medium
                   {{ request()->routeIs($link['route']) ? 'bg-indigo-600 text-white shadow-soft' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach

            @if(auth()->check() && auth()->user()->is_admin)
                <a href="{{route('admin.')}}" wire:navigate class="group flex items-center gap-3 px-3.5 py-2.5 text-sm transition-all rounded-xl font-medium
                {{ request()->routeIs('admin.') ? 'bg-indigo-600 text-white shadow-soft' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <i class="fas fa-user-tie w-5 text-center"></i>
                    <span>مدیریت</span>
                </a>
            @endif
        </nav>
    </aside>

    <main class="flex-1 flex flex-col min-w-0">
        {{-- Header --}}
        <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-6 shrink-0 sticky top-0 z-30">
            @php
                $routeName = request()->route()?->getName() ?? '';
                $pageTitles = [
                    'dashboard'        => 'پیشخوان',
                    'monthly.calendar' => 'برنامه ماهانه',
                    'reports'          => 'گزارش‌ها',
                    'profile'          => 'پروفایل',
                    'timeline'         => 'خط زمان',
                    'support'          => 'پشتیبانی',
                    'help'             => 'آموزش و راهنما',
                    'task'             => 'تسک‌های من',
                    'task.form'        => request('id') ? 'ویرایش تسک' : 'تسک جدید',
                ];
                $pageTitle = $pageTitles[$routeName] ?? (request()->is('admin*') ? 'مدیریت' : setting('site_title'));
            @endphp
            <h1 class="text-base font-bold text-slate-900 dark:text-white truncate">
                {{ $pageTitle }}
            </h1>

            <div class="flex items-center gap-3">
                {{-- Toggle Theme --}}
                <button @click="toggleTheme" 
                        class="relative inline-flex h-8 w-14 items-center rounded-full bg-slate-200 dark:bg-slate-800 transition-colors focus:outline-none border border-slate-300 dark:border-slate-700">
                    <span :class="darkMode ? '-translate-x-7 bg-indigo-600' : '-translate-x-1 bg-white'" 
                          class="inline-block h-6 w-6 transform rounded-full shadow-md transition-all duration-300 ease-in-out flex items-center justify-center">
                        <i x-show="!darkMode" class="fas fa-sun text-amber-500 text-[10px]"></i>
                        <i x-show="darkMode" class="fas fa-moon text-white text-[10px]" x-cloak></i>
                    </span>
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-slate-100 dark:bg-slate-950 p-4 lg:p-6 pb-28 lg:pb-6 custom-scrollbar content-scroll">
            {{ $slot }}
        </div>
    </main>
</div>

<x-bottom-nav />

<livewire:task.modal />
<livewire:category-modal />
<livewire:support.ticket-modal />

@livewireScripts
</body>
</html>
