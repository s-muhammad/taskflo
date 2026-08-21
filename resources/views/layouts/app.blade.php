<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <title>{{ setting('site_title') }}</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, interactive-widget=resizes-content">
    <meta name="description" content="{{ setting('site_description') }}">
    <meta name="keywords" content="{{ setting('seo_meta_keywords') }}">
    <meta name="author" content="{{ setting('seo_meta_author') }}">
    {{--    <link rel="icon" type="image/png" sizes="32x32" href="{{ setting('site_favicon') }}">--}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="{{asset('/font/FontAwesome/css/all.css')}}" rel="stylesheet">
    <link rel="preload" href="/font/vazir-font-v16.1.0/Vazir.woff" as="font" type="font/woff2" crossorigin>
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; overscroll-behavior: none; }

        /* موبایل: صفحه به‌صورت طبیعی اسکرول می‌شود و هدر با sticky بالا می‌چسبد.
           دسکتاپ: شل برنامه ارتفاع کامل دارد و فقط بخش محتوا داخلی اسکرول می‌شود. */
        @media (min-width: 1024px) {
            .app-shell { height: 100vh; height: 100dvh; overflow: hidden; }
        }

        /* موبایل/تبلت: ارتفاع صفحه با dvh مدیریت می‌شود تا با باز شدن کیبورد موبایل،
           نوار پایین نپرد یا زیر کیبورد قایم نشود (PWA/WebView) */
        @media (max-width: 1023.98px) {
            .app-shell { min-height: 100vh; min-height: 100dvh; }
        }

        /* استایل‌های پایه */
        .day-cell { min-height: 120px; transition: all 0.2s; }
        @media (max-width: 768px) { .day-cell { min-height: auto; } }
        .day-cell:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        body.menu-open { overflow: hidden; }

        /* مخفی کردن نوار اسکرول در سایدبار */
        aside::-webkit-scrollbar { width: 4px; }
        aside::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,0.1); border-radius: 4px; }

        /* اسکرول نرم و روان محتوا در موبایل (iOS) */
        .content-scroll { -webkit-overflow-scrolling: touch; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800">

<div id="overlay" onclick="toggleMenu()" class="fixed inset-0 bg-slate-900/50 z-40 hidden transition-opacity backdrop-blur-sm lg:hidden"></div>

<div class="app-shell flex lg:overflow-hidden">
    <aside id="sidebar" class="w-72 bg-slate-900 text-white fixed inset-y-0 right-0 z-50 transform translate-x-full
    transition-all duration-300 lg:translate-x-0 lg:static lg:flex flex-col p-4 shadow-2xl lg:shadow-none overflow-x-hidden">

        <div class="flex items-center justify-between mb-8 whitespace-nowrap">
            <div class="flex items-center gap-3 transition-all duration-300 overflow-hidden" id="logo-container">
                <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 flex-shrink-0">
                    <img src="{{ setting('site_logo') }}" alt="">
                </div>
                <span class="text-xl font-bold tracking-tight menu-text opacity-100 transition-opacity duration-300">{{ setting('site_title') }}</span>
            </div>

            <button onclick="toggleMenu()" class="text-slate-400 hover:text-white hover:bg-white/10 p-2 rounded-lg transition-all">
                <i class="fas fa-times text-xl lg:hidden"></i>
                <i id="desktop-icon" class="fas fa-indent text-xl hidden lg:block transform transition-transform duration-300"></i>
            </button>
        </div>

        <nav class="space-y-2 flex-1">
            <a href="{{route('dashboard')}}" class="group flex items-center gap-3 p-3 text-slate-300 hover:bg-indigo-600 hover:text-white
            rounded-xl transition-all whitespace-nowrap overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fas fa-home w-6 flex-shrink-0 text-center transition-transform group-hover:scale-110"></i>
                <span class="menu-text transition-opacity duration-300 font-medium">پیشخوان</span>
            </a>

            <a href="{{route('task')}}" class="group flex items-center gap-3 p-3 text-slate-300 hover:bg-indigo-600 hover:text-white
            rounded-xl transition-all whitespace-nowrap overflow-hidden {{ request()->routeIs('task') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fas fa-check-square w-6 flex-shrink-0 text-center transition-transform group-hover:scale-110"></i>
                <span class="menu-text transition-opacity duration-300 font-medium">تسک</span>
            </a>

            <a href="{{route('monthly.calendar')}}" class="group flex items-center gap-3 p-3 text-slate-300 hover:bg-indigo-600 hover:text-white
            rounded-xl transition-all whitespace-nowrap overflow-hidden {{ request()->routeIs('monthly.calendar') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fas fa-calendar-alt w-6 flex-shrink-0 text-center transition-transform group-hover:scale-110"></i>
                <span class="menu-text transition-opacity duration-300 font-medium">برنامه ماهانه</span>
            </a>

            <a href="{{route('profile')}}" class="group flex items-center gap-3 p-3 text-slate-300 hover:bg-indigo-600 hover:text-white
            rounded-xl transition-all whitespace-nowrap overflow-hidden {{ request()->routeIs('profile') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fas fa-user w-6 flex-shrink-0 text-center transition-transform group-hover:scale-110"></i>
                <span class="menu-text transition-opacity duration-300 font-medium">پروفایل</span>
            </a>

            @if(auth()->user()->is_admin)
                <a href="{{route('admin.')}}" class="group flex items-center gap-3 p-3 text-slate-300 hover:bg-indigo-600 hover:text-white
                    rounded-xl transition-all whitespace-nowrap overflow-hidden {{ request()->routeIs('admin.') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fas fa-user-tie w-6 flex-shrink-0 text-center transition-transform group-hover:scale-110"></i>
                    <span class="menu-text transition-opacity duration-300 font-medium">مدیریت</span>
                </a>
            @endif
        </nav>
        <div class="pt-4 border-t border-gray-700">
            <a href="#" onclick="document.getElementById('form').submit();"
               class="flex items-center p-3 rounded-xl bg-red-700 hover:bg-red-600 transition-all duration-200 ease-in-out text-white font-bold
            shadow-lg shadow-red-700/50 group w-full"
            >
                <i class="fas fa-sign-out-alt text-lg ml-3 group-hover:scale-110 transition-transform"></i>
                <span class="menu-text transition-opacity duration-300 font-medium">خروج </span>
            </a>
        </div>
        <form action="{{route('logout')}}" method="post" id="form" style="display: none">
            @csrf
        </form>
        {{--        <div class="mt-auto border-t border-white/10 pt-4">--}}
        {{--            <div class="flex items-center gap-3 whitespace-nowrap overflow-hidden">--}}
        {{--                <img src="https://ui-avatars.com/api/?name=User&background=6366f1&color=fff" class="w-9 h-9 rounded-full flex-shrink-0 border-2 border-slate-700">--}}
        {{--                <div class="flex flex-col menu-text transition-opacity duration-300">--}}
        {{--                    <span class="text-sm font-bold">برنامه‌نویس</span>--}}
        {{--                    <span class="text-xs text-slate-400">Manage Account</span>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </aside>

    <main class="flex-1 flex flex-col min-w-0 lg:overflow-hidden">

        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 sticky top-0 z-30">

            <div class="flex items-center gap-4">
                <div class="flex flex-col">
                    @php
                        $routeName = request()->route()?->getName() ?? '';
                        $titleMap = [
                            'task*'            => 'تسک‌های من',
                            'monthly.calendar' => 'برنامه ماهانه',
                            'profile'          => 'پروفایل من',
                            'reports'          => 'نمودار عملکرد',
                            'support'          => 'پشتیبانی',
                            'help'             => 'آموزش و راهنما',
                            'timeline'         => 'خط زمان',
                        ];
                        $pageTitle = 'پیشخوان';
                        foreach ($titleMap as $pattern => $title) {
                            if (\Illuminate\Support\Str::is($pattern, $routeName)) {
                                $pageTitle = $title;
                                break;
                            }
                        }
                    @endphp
                    <h1 class="text-sm lg:text-base font-bold text-slate-800">
                        @if(request()->routeIs('dashboard'))
                            {{ auth()->user()->name }} عزیز خوش آمدید!
                        @else
                            {{ $pageTitle }}
                        @endif
                    </h1>
                    <p class="text-xs text-slate-500 ">{{ \Morilog\Jalali\Jalalian::now()->format('%A، d %B Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- <button class="relative p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                    <i class="far fa-bell text-xl"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="h-8 w-[1px] bg-slate-200 mx-2"></div> -->
                <a href="{{ route('profile') }}" wire:navigate title="پروفایل من"
                   aria-label="پروفایل من"
                   class="w-9 h-9 rounded-xl flex items-center justify-center border shadow-sm transition-all
                      {{ request()->routeIs('profile')
                          ? 'bg-indigo-600 text-white border-indigo-600 shadow-indigo-200'
                          : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200' }}">
                    <i class="fas fa-user text-sm"></i>
                </a>
        </header>
        <div class="flex-1 lg:overflow-y-auto bg-[#f8fafc] p-4 pb-28 lg:pb-4 custom-scrollbar content-scroll">
            {{ $slot }}
        </div>
    </main>
</div>

<x-bottom-nav />

<livewire:category-modal />
<livewire:support.ticket-modal />
<livewire:task.modal />
<x-alert.flash-message />

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const desktopIcon = document.getElementById('desktop-icon');
    const menuTexts = document.querySelectorAll('.menu-text');

    // تابع برای اعمال وضعیت دسکتاپ (باز یا بسته)
    function setDesktopState(collapse) {
        if (collapse) {
            sidebar.classList.remove('lg:w-72');
            sidebar.classList.add('lg:w-20');
            desktopIcon.classList.add('rotate-180');
            menuTexts.forEach(el => el.classList.add('lg:hidden'));
        } else {
            sidebar.classList.remove('lg:w-20');
            sidebar.classList.add('lg:w-72');
            desktopIcon.classList.remove('rotate-180');
            menuTexts.forEach(el => el.classList.remove('lg:hidden'));
        }
        localStorage.setItem('sidebar-collapsed', collapse);
    }

    // بارگذاری وضعیت ذخیره شده هنگام لود صفحه
    document.addEventListener('DOMContentLoaded', () => {
        if (window.innerWidth >= 1024) {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            setDesktopState(isCollapsed);
        }
    });

    function toggleMenu() {
        if (window.innerWidth >= 1024) {
            // منطق دسکتاپ: تغییر عرض
            const isCurrentlyCollapsed = sidebar.classList.contains('lg:w-20');
            setDesktopState(!isCurrentlyCollapsed);
        } else {
            // منطق موبایل: باز/بسته شدن کشویی
            sidebar.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('menu-open');
        }
    }

    function urlBase64ToUint8Array(b64) {
        const pad = '='.repeat((4 - b64.length % 4) % 4);
        const raw = window.atob((b64 + pad).replace(/-/g,'+').replace(/_/g,'/'));
        const arr = new Uint8Array(raw.length);
        for (let i = 0; i < raw.length; i++) arr[i] = raw.charCodeAt(i);
        return arr;
    }

    document.addEventListener('DOMContentLoaded', async () => {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
        if (localStorage.getItem('notif_dismissed')) return;

        try {
            const r = await fetch('{{ route('webpush.status') }}');
            if ((await r.json()).subscribed) return;

            const banner = document.createElement('div');
            banner.style.cssText = 'position:fixed;bottom:88px;right:20px;background:#1e293b;color:white;padding:14px 20px;border-radius:12px;font-size:13px;max-width:340px;box-shadow:0 8px 30px rgba(0,0,0,0.2);z-index:9999;display:flex;align-items:center;gap:12px;direction:rtl;font-family:Vazirmatn,sans-serif';
            banner.innerHTML = `
                <span style="flex:1">فعال‌سازی اعلان برای یادآوری وظایف</span>
                <button id="notif-yes" style="background:#6366f1;color:white;border:none;padding:6px 14px;border-radius:8px;cursor:pointer;font-weight:bold;font-size:12px">فعال</button>
                <button id="notif-no" style="background:transparent;color:#94a3b8;border:none;padding:4px;cursor:pointer;font-size:16px">&times;</button>
            `;
            document.body.appendChild(banner);

            document.getElementById('notif-yes').onclick = async () => {
                try {
                    const reg = await navigator.serviceWorker.register('/sw.js');
                    await navigator.serviceWorker.ready;
                    const existing = await reg.pushManager.getSubscription();
                    if (existing) await existing.unsubscribe();
                    const p = await Notification.requestPermission();
                    if (p !== 'granted') return;
                    const sub = await reg.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array('{{ config('webpush.vapid.public_key') }}')
                    });
                    await fetch('{{ route('webpush.subscribe') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content },
                        body: JSON.stringify(sub)
                    });
                } catch (e) { console.error(e); }
                banner.remove();
            };

            document.getElementById('notif-no').onclick = () => {
                localStorage.setItem('notif_dismissed', '1');
                banner.remove();
            };
        } catch (e) {}
    });
</script>
<script src="/chartJs/chart.js"></script>
@livewireScripts
</body>
</html>
