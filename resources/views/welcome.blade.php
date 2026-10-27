<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <title>{{ setting('site_title') }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ setting('site_description') }}">
    <meta name="keywords" content="{{ setting('seo_meta_keywords') }}">
    <meta name="author" content="{{ setting('seo_meta_author') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    @vite(['resources/js/app.js','resources/css/app.css'])
    <style>
        body { font-family: 'Vazirmatn FD', 'Vazirmatn', sans-serif; }

        /* منوی موبایل به صورت dropdown از بالا به پایین */
        .mobile-dropdown {
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border-bottom: 1px solid #e2e8f0;
            z-index: 40;
            max-height: calc(100vh - 64px);
            overflow-y: auto;
            transform: translateY(-100%);
            transition: transform 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
        }

        .mobile-dropdown.open {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .mobile-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 35;
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
            top: 64px;
        }

        .mobile-overlay.visible {
            opacity: 1;
            visibility: visible;
        }

        .mobile-dropdown a {
            transform: translateX(0);
            transition: all 0.2s ease;
        }

        .mobile-dropdown a:active {
            transform: translateX(-5px);
        }

        /* اندازه و سرریز موکاپ هیرو */
        @media (min-width: 1280px) {
            .hero-mockup { width: 108%; }
        }
        @media (min-width: 1366px) {
            .hero-mockup { width: 112%; margin-inline-end: -3%; }
        }
        @media (min-width: 1400px) {
            .hero-mockup { width: 118%; margin-inline-end: -8%; }
        }
        @media (min-width: 1700px) {
            .hero-container { max-width: 1600px; }
            .hero-title { font-size: 2.75rem; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-600 selection:text-white overflow-x-hidden">

<!-- Navbar -->
<nav class="sticky top-0 z-50 border-b border-slate-900/5 bg-white/70 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden">
                    <img src="{{ setting('site_logo') }}" alt="{{ setting('site_title') }}" class="w-full h-full object-contain p-1.5">
                </div>
                <span class="font-bold text-xl text-slate-900 tracking-tight">{{ setting('site_title') }}</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-9">
                <a href="#features" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">امکانات</a>
                <a href="#pricing" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">تعرفه‌ها</a>
                <a href="{{ url('/blog') }}" class="text-sm text-slate-500 hover:text-slate-900 transition-colors">بلاگ</a>
            </div>

            <!-- Desktop CTA Buttons -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-xl transition-colors hover:bg-slate-100/70">ورود</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/45 hover:-translate-y-0.5">ثبت‌نام رایگان</a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobileMenuBtn" class="text-slate-600 hover:text-slate-900 focus:outline-none transition-transform duration-200" aria-label="منو">
                    <x-icon.svg-icon name="menu" id="menuIcon" class="h-6 w-6" />
                    <x-icon.svg-icon name="close" id="closeIcon" class="h-6 w-6 hidden" />
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Dropdown Menu -->
<div id="mobileOverlay" class="mobile-overlay"></div>
<div id="mobileDropdown" class="mobile-dropdown">
    <div class="px-4 py-6 space-y-6">
        <div class="flex flex-col gap-2">
            <a href="#features" class="flex items-center justify-between py-3 px-4 text-slate-700 hover:text-indigo-600
            hover:bg-indigo-50 rounded-xl font-medium transition-all border border-slate-100">
                <span>امکانات</span>
            </a>
            <a href="#pricing" class="flex items-center justify-between py-3 px-4 text-slate-700 hover:text-indigo-600
            hover:bg-indigo-50 rounded-xl font-medium transition-all border border-slate-100">
                <span>تعرفه‌ها</span>
            </a>
            <a href="{{ url('/blog') }}" class="flex items-center justify-between py-3 px-4 text-slate-700 hover:text-indigo-600
            hover:bg-indigo-50 rounded-xl font-medium transition-all border border-slate-100">
                <span>بلاگ</span>
            </a>
            <a href="{{ route('login') }}" class="flex items-center justify-between py-3 px-4 text-slate-700 hover:text-indigo-600
            hover:bg-indigo-50 rounded-xl font-medium transition-all border border-slate-100">
                <span>ورود</span>
            </a>
            <a href="{{ route('register') }}" class="flex items-center justify-center py-3 px-4 text-white bg-indigo-600
            hover:bg-indigo-700 rounded-xl font-bold transition-all">
                <span>ثبت‌نام رایگان</span>
            </a>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section class="relative pt-12 pb-16 lg:pt-20 lg:pb-24 overflow-hidden">
    <div class="absolute -top-32 right-[-10%] w-[38rem] h-[38rem] rounded-full bg-indigo-500/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/3 left-[-12%] w-[30rem] h-[30rem] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <div class="hero-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)] gap-10 xl:gap-10 items-center">

            <!-- Copy -->
            <div class="text-right">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200/80 text-slate-600 text-xs font-medium mb-5 shadow-soft">
                    <span class="flex h-1.5 w-1.5 rounded-full bg-indigo-600"></span>
                    نسخهٔ ۱.۰ منتشر شد
                </div>

                <h1 class="hero-title text-[1.75rem] md:text-4xl xl:text-[2.5rem] font-extrabold text-slate-900 leading-[1.35] tracking-tight mb-5">
                    مدیریت زمان و تسک‌ها،<br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-l from-indigo-600 to-blue-500">هوشمندانه‌تر از همیشه</span>
                </h1>

                <p class="text-slate-500 text-base leading-loose mb-7 max-w-xl">
                    برنامه‌ریزی روزانه با تقویم شمسی، دسته‌بندی تسک‌ها و گزارش پیشرفت هفته — همه در یک پنل. اولین ماه کاملاً رایگان.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 mb-7">
                    <a href="{{ route('register') }}" class="group flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition-all duration-300 shadow-lg shadow-indigo-600/25 hover:shadow-[0_14px_44px_-10px_rgba(79,70,229,0.65)] hover:-translate-y-0.5">
                        ساخت حساب رایگان
                        <x-icon.svg-icon name="arrow-left" class="w-5 h-5 rotate-180 transition-transform duration-300 group-hover:-translate-x-1" />
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 px-6 py-3 rounded-xl font-bold transition-all duration-300 hover:-translate-y-0.5">
                        ورود به حساب
                    </a>
                </div>

                {{-- <div class="flex items-center gap-5 text-sm text-slate-500">
                    <div class="flex -space-x-3 space-x-reverse">
                        <div class="w-9 h-9 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-[10px] z-30">ع‌م</div>
                        <div class="w-9 h-9 rounded-full border-2 border-white bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-[10px] z-20">س‌ت</div>
                        <div class="w-9 h-9 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-[10px] z-10">پ‌ر</div>
                        <div class="w-9 h-9 rounded-full border-2 border-white bg-slate-900 flex items-center justify-center text-white text-[10px] font-bold z-0">+۱k</div>
                    </div>
                    <p>بیش از <span class="font-bold text-slate-900">۱,۰۰۰</span> کاربر فعال، بدون نیاز به کارت بانکی</p>
                </div> --}}
            </div>

            <!-- Mockup with depth -->
            <div class="hero-mockup relative mt-4 xl:mt-0">
                <!-- soft glow instead of the tilted rectangle -->
                <div class="absolute -inset-x-12 top-1/2 -translate-y-1/2 h-[80%] rounded-full bg-indigo-500/20 blur-[90px] pointer-events-none"></div>
                <div class="absolute -inset-x-4 bottom-0 h-1/2 rounded-full bg-blue-400/20 blur-[70px] pointer-events-none"></div>

                <div class="relative">
                    <img src="{{ asset('mockup.png') }}"
                         alt="نمایش داشبورد {{ setting('site_title') }}"
                         class="relative w-full h-auto select-none drop-shadow-[0_40px_80px_rgba(15,23,42,0.16)]"
                         width="1385" height="731" loading="eager" decoding="async">
                </div>

                <!-- floating card: task done -->
                <div id="float-done" class="hidden lg:flex absolute -top-1 left-6 items-center gap-3 bg-white/90 backdrop-blur-md border border-slate-200/70 rounded-2xl px-4 py-3 shadow-lift">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <x-icon.svg-icon name="check-circle" class="w-5 h-5" />
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-900 leading-tight">تسک انجام شد</p>
                        <p class="text-[11px] text-slate-400 leading-tight mt-0.5">گزارش نهایی</p>
                    </div>
                </div>

                <!-- floating card: weekly progress -->
                <div id="float-progress" class="hidden lg:flex absolute -bottom-1 right-6 items-center gap-4 bg-white/90 backdrop-blur-md border border-slate-200/70 rounded-2xl px-4 py-3 shadow-lift">
                    <div class="flex items-end gap-1 h-9">
                        <span class="w-1.5 h-3 rounded-full bg-indigo-200"></span>
                        <span class="w-1.5 h-5 rounded-full bg-indigo-300"></span>
                        <span class="w-1.5 h-4 rounded-full bg-indigo-400"></span>
                        <span class="w-1.5 h-7 rounded-full bg-indigo-500"></span>
                        <span class="w-1.5 h-9 rounded-full bg-indigo-600"></span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-900 leading-tight">۷۲٪ این هفته</p>
                        <p class="text-[11px] text-slate-400 leading-tight mt-0.5">۱۲ تسک انجام شد</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section (Bento Grid) -->
<section id="features" class="py-16 lg:py-20 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="reveal max-w-2xl mb-10">
            <h2 class="text-indigo-600 font-bold tracking-wide mb-3 text-sm">امکانات سیستم</h2>
            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-5 leading-snug">هر بخش از کارتان، سر جای خودش</h3>
            <p class="text-base text-slate-500 leading-loose">برنامه‌ریزی روزانه با تقویم شمسی، در یک نگاه — بدون جابه‌جا کردن بین چند صفحه و ابزار.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <!-- Calendar card (large) -->
            <div class="reveal  rounded-2xl border border-slate-200/70 bg-white p-4 sm:p-5 shadow-soft hover:border-indigo-200 hover:shadow-lift transition-all duration-500">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-9 h-9 shrink-0 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <x-icon.svg-icon name="calendar-check" class="w-5 h-5" />
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900 mb-1.5">تقویم شمسی برای برنامه‌ریزی روزانه</h4>
                        <p class="text-slate-500 leading-relaxed text-[13px] max-w-lg">مهلت هر تسک را روی تقویم شمسی ببینید؛ روزهای شلوغ هفته را قبل از شروع، تشخیص دهید.</p>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50/80 border border-slate-100 p-3 sm:p-4 sm:max-w-[22rem] sm:mx-auto">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-slate-900">آبان ۱۴۰۵</span>
                        <span class="text-[11px] font-medium text-slate-400">۳ تسک در این هفته</span>
                    </div>
                    <div class="grid grid-cols-7 gap-1.5">
                        @foreach(['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] as $weekday)
                            <div class="text-center text-[11px] font-bold text-slate-400 pb-0.5">{{ $weekday }}</div>
                        @endforeach
                        <div></div>
                        @for($day = 1; $day <= 30; $day++)
                            @php
                                $isToday = $day === 24;
                                $hasTask = in_array($day, [6, 13, 20, 27]);
                            @endphp
                            <div class="aspect-square rounded-lg text-[11px] font-bold flex items-center justify-center
                                {{ $isToday ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                                   : ($hasTask ? 'bg-indigo-50 text-indigo-700 border border-indigo-100'
                                   : 'bg-white text-slate-400 border border-slate-100') }}">
                                {{ fa_digits($day) }}
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Recurring routines card -->
            <div class="reveal lg:col-span-2 rounded-2xl border border-slate-200/70 bg-white p-4 sm:p-5 shadow-soft hover:border-indigo-200 hover:shadow-lift transition-all duration-500 flex flex-col" style="transition-delay: 80ms">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                    <x-icon.svg-icon name="repeat" class="w-5 h-5" />
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">روتین تکرار تسک‌ها</h4>
                <p class="text-slate-500 leading-relaxed text-[13px] mb-3">یک‌بار بسازید؛ هر روز، روزهای زوج یا روزهای فرد، خودکار تکرار می‌شود.</p>

                <ul class="space-y-1.5">
                    <li class="flex items-center gap-2.5 rounded-xl bg-slate-50 border border-slate-100 px-2.5 py-2">
                        <span class="w-7 h-7 shrink-0 rounded-lg bg-white border border-slate-100 text-indigo-500 flex items-center justify-center">
                            <x-icon.svg-icon name="repeat" class="w-4 h-4" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[13px] font-bold text-slate-900 truncate">بررسی ایمیل‌ها</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">هر روز</span>
                        </span>
                        <span class="shrink-0 text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg px-2 py-1">روزانه</span>
                    </li>
                    <li class="flex items-center gap-2.5 rounded-xl bg-slate-50 border border-slate-100 px-2.5 py-2">
                        <span class="w-7 h-7 shrink-0 rounded-lg bg-white border border-slate-100 text-indigo-500 flex items-center justify-center">
                            <x-icon.svg-icon name="repeat" class="w-4 h-4" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[13px] font-bold text-slate-900 truncate">پیگیری فاکتورها</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">۲، ۴، ۶ و…</span>
                        </span>
                        <span class="shrink-0 text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg px-2 py-1">روزهای زوج</span>
                    </li>
                    <li class="flex items-center gap-2.5 rounded-xl bg-slate-50 border border-slate-100 px-2.5 py-2">
                        <span class="w-7 h-7 shrink-0 rounded-lg bg-white border border-slate-100 text-indigo-500 flex items-center justify-center">
                            <x-icon.svg-icon name="repeat" class="w-4 h-4" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[13px] font-bold text-slate-900 truncate">بازبینی گزارش‌ها</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">۱، ۳، ۵ و…</span>
                        </span>
                        <span class="shrink-0 text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg px-2 py-1">روزهای فرد</span>
                    </li>
                </ul>

                <div class="mt-auto pt-3">
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-indigo-50/70 border border-indigo-100 px-3 py-2">
                        <span class="flex items-center gap-2 text-xs font-bold text-indigo-900">
                            <x-icon.svg-icon name="check-circle" class="w-4 h-4 text-indigo-600" />
                            ۴ روتین فعال
                        </span>
                        <span class="text-[11px] font-medium text-indigo-500">اجرای خودکار، بدون فراموشی</span>
                    </div>
                </div>
            </div>

            <!-- Today tasks card -->
            <div class="reveal lg:col-span-2 rounded-2xl border border-slate-200/70 bg-white p-4 shadow-soft hover:border-indigo-200 hover:shadow-lift transition-all duration-500">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                    <x-icon.svg-icon name="list-checks" class="w-5 h-5" />
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">تسک‌های امروز</h4>
                <p class="text-slate-500 leading-relaxed text-[13px] mb-4">فقط همان چیزی که امروز باید انجام شود.</p>

                <ul class="space-y-1.5">
                    <li class="flex items-center gap-2.5 rounded-xl bg-slate-50 border border-slate-100 px-2.5 py-2">
                        <span class="w-5 h-5 rounded-md border-2 border-indigo-500 shrink-0"></span>
                        <span class="text-[13px] font-medium text-slate-700 truncate">بازبینی طراحی صفحهٔ لندینگ</span>
                    </li>
                    <li class="flex items-center gap-2.5 rounded-xl bg-slate-50 border border-slate-100 px-2.5 py-2">
                        <span class="w-5 h-5 rounded-md border-2 border-slate-300 bg-slate-200 flex items-center justify-center shrink-0">
                            <x-icon.svg-icon name="check" class="w-3.5 h-3.5 text-white" />
                        </span>
                        <span class="text-[13px] font-medium text-slate-400 line-through truncate">پیاده‌سازی مسیر یادآوری</span>
                    </li>
                    <li class="flex items-center gap-2.5 rounded-xl bg-slate-50 border border-slate-100 px-2.5 py-2">
                        <span class="w-5 h-5 rounded-md border-2 border-indigo-500 shrink-0"></span>
                        <span class="text-[13px] font-medium text-slate-700 truncate">نوشتن یادداشت بلاگ</span>
                    </li>
                </ul>
            </div>

            <!-- Reports card (wide) -->
            <div class="reveal  rounded-2xl border border-slate-200/70 bg-white p-4 shadow-soft hover:border-indigo-200 hover:shadow-lift transition-all duration-500" style="transition-delay: 80ms">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-9 h-9 shrink-0 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <x-icon.svg-icon name="chart-column" class="w-5 h-5" />
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900 mb-1.5">گزارش فعالیت، هفته‌به‌هفته</h4>
                        <p class="text-slate-500 leading-relaxed text-[13px] max-w-lg">نمودار فعالیت و روند پیشرفت، تا بدانید کدام روزها واقعاً پُر کاری کرده‌اید.</p>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50/80 border border-slate-100 p-3">
                    <div class="flex items-end justify-between gap-2 h-24" dir="ltr">
                        @foreach([38, 56, 44, 78, 62, 92, 70] as $height)
                            <div class="flex-1 rounded-t-lg bg-indigo-500/90 hover:bg-indigo-600 transition-colors" style="height: {{ $height }}%"></div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between gap-2 mt-3 text-[11px] font-medium text-slate-400" dir="ltr">
                        @foreach(['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] as $day)
                            <span class="flex-1 text-center">{{ $day }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dark Section -->
<section class="relative py-16 lg:py-20 bg-slate-950 overflow-hidden">
    <div class="absolute top-0 left-1/4 w-[34rem] h-[34rem] rounded-full bg-indigo-600/25 blur-[110px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[28rem] h-[28rem] rounded-full bg-violet-600/20 blur-[110px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div class="reveal">
                <h2 class="text-indigo-400 font-bold tracking-wide mb-3 text-sm">گزارش‌ها</h2>
                <h3 class="text-2xl md:text-3xl font-extrabold text-white mb-5 leading-snug">روند کارتان را هفته‌به‌هفته ببینید</h3>
                <p class="text-base text-slate-400 leading-loose mb-7 max-w-xl">
                    نقشهٔ حرارتی فعالیت، همان لحظه‌ای را نشان می‌دهد که بیشترین فشار را روی شما بوده — جایی که باید برنامه‌ریزی را تغییر دهید.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white hover:bg-slate-100 text-slate-900 px-6 py-3 rounded-xl font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_14px_44px_-12px_rgba(255,255,255,0.35)]">
                    گزارش من را بساز
                    <x-icon.svg-icon name="arrow-left" class="w-5 h-5 rotate-180" />
                </a>
            </div>

            <div class="reveal" style="transition-delay: 120ms">
                <div class="rounded-2xl border border-white/10 bg-white/[0.04] backdrop-blur-sm p-5 shadow-[0_40px_90px_-40px_rgba(0,0,0,0.9)]">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-bold text-white">۱۲ هفتهٔ گذشته</span>
                        <span class="text-[11px] font-medium text-slate-400">کم‌کاری زیاد</span>
                    </div>
                    <div class="grid grid-flow-col grid-rows-7 gap-1" dir="ltr">
                        @php
                            $heatmap = [0,0,1,0,2,3,1,0,0,2,4,4,3,2,1,0,0,1,3,4,4,2,0,1,2,3,4,4,3,1,0,2,3,4,3,2,1,0,0,1,2,4,3,2,1,0,3,4,4,3,2,0,1,2,3,4,4,3,2,1,0,2,3,4,3,1];
                            $levels = [
                                0 => 'bg-white/5',
                                1 => 'bg-indigo-900/70',
                                2 => 'bg-indigo-700/70',
                                3 => 'bg-indigo-500/80',
                                4 => 'bg-indigo-400',
                            ];
                        @endphp
                        @foreach($heatmap as $level)
                            <span class="w-3 h-3 rounded-[4px] {{ $levels[$level] }}"></span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-end gap-1.5 mt-3 text-[10px] text-slate-500" dir="ltr">
                        <span>کم</span>
                        @foreach(array_keys($levels) as $level)
                            <span class="w-3 h-3 rounded-[4px] {{ $levels[$level] }}"></span>
                        @endforeach
                        <span>زیاد</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-16 lg:py-20 bg-slate-50 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">

            <div class="reveal">
                <h2 class="text-indigo-600 font-bold tracking-wide mb-4 text-sm">تعرفهٔ اشتراک</h2>
                <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-5 leading-snug">یک اشتراک، همهٔ امکانات</h3>
                <p class="text-base text-slate-500 leading-loose mb-8 max-w-xl">
                    بدون پلن‌بندی گیج‌کننده و بدون محدودیت تعداد تسک یا کاربر. ۳۰ روز اول کاملاً رایگان، بعدش تصمیم بگیرید.
                </p>
                <div class="space-y-2.5 text-sm text-slate-700 font-medium">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-white border border-slate-200 text-indigo-600 flex items-center justify-center shrink-0">
                            <x-icon.svg-icon name="check" class="w-4 h-4" />
                        </div>
                        لغو اشتراک در هر زمان، با یک کلیک
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-white border border-slate-200 text-indigo-600 flex items-center justify-center shrink-0">
                            <x-icon.svg-icon name="check" class="w-4 h-4" />
                        </div>
                        بدون نیاز به کارت بانکی برای شروع
                    </div>
                </div>
            </div>

            <div class="reveal max-w-[380px] w-full mx-auto" style="transition-delay: 100ms">
                <div class="rounded-2xl bg-white border border-slate-200/70 p-5 shadow-lift relative">
                    <div class="absolute -top-4 right-10 bg-slate-900 text-white px-3.5 py-1.5 rounded-full text-xs font-bold shadow-lg">
                        ۳۰ روز اول رایگان
                    </div>
                    <div class="mb-4">
                        <h4 class="text-lg font-extrabold text-slate-900 mb-1">اشتراک ویژه</h4>
                        <p class="text-slate-500 text-[13px]">دسترسی کامل به همهٔ بخش‌های پنل</p>
                    </div>
                    <div class="mb-5 text-center bg-slate-50 border border-slate-100 rounded-xl py-4">
                        <span class="text-3xl font-extrabold text-slate-900">۴۹,۰۰۰</span>
                        <span class="text-slate-500 text-[13px] font-medium block mt-1">تومان / ماهانه</span>
                    </div>
                    <ul class="space-y-2.5 mb-5">
                        <li class="flex items-center gap-2.5 text-[13px] text-slate-700 font-medium">
                            <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <x-icon.svg-icon name="check" class="w-4 h-4" />
                            </span>
                            تسک و پروژهٔ نامحدود
                        </li>
                        <li class="flex items-center gap-2.5 text-[13px] text-slate-700 font-medium">
                            <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <x-icon.svg-icon name="check" class="w-4 h-4" />
                            </span>
                            تقویم شمسی و یادآور مهلت‌ها
                        </li>
                        <li class="flex items-center gap-2.5 text-[13px] text-slate-700 font-medium">
                            <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <x-icon.svg-icon name="check" class="w-4 h-4" />
                            </span>
                            گزارش ماهانه و نقشهٔ حرارتی فعالیت
                        </li>
                        <li class="flex items-center gap-2.5 text-[13px] text-slate-700 font-medium">
                            <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <x-icon.svg-icon name="check" class="w-4 h-4" />
                            </span>
                            پشتیبانی اولویت‌دار
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="flex items-center justify-center w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold transition-all duration-300 shadow-lg shadow-indigo-600/25 hover:shadow-[0_14px_44px_-12px_rgba(79,70,229,0.6)] hover:-translate-y-0.5">
                        شروع تست رایگان
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section id="blog" class="py-16 lg:py-20 bg-white relative border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="reveal flex justify-between items-end mb-10">
            <div class="max-w-2xl">
                <h2 class="text-indigo-600 font-bold tracking-wide mb-3 text-sm">بلاگ و آموزش</h2>
                <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-4">یادداشت‌هایی دربارهٔ بهره‌وری</h3>
            </div>
            <a href="/blog" class="hidden md:inline-flex items-center gap-2 text-sm font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                مشاهده همه
                <x-icon.svg-icon name="arrow-left" class="w-5 h-5 rotate-180" />
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($blogs as $blog)
                <a href="{{ route('blog.single', $blog->slug) }}" class="reveal group block rounded-2xl overflow-hidden border border-slate-200/70 bg-white shadow-soft hover:border-indigo-200 hover:shadow-lift hover:-translate-y-1 transition-all duration-500">
                    <div class="h-32 bg-slate-100 overflow-hidden">
                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-4">
                        <div class="text-[11px] text-slate-400 mb-1.5 font-medium">@fa(\Morilog\Jalali\Jalalian::fromCarbon($blog->created_at)->format('%d %B %Y'))</div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1.5 group-hover:text-indigo-600 transition-colors">{{ $blog->title }}</h4>
                        <p class="text-slate-500 text-[13px] leading-relaxed">{{ $blog->summary }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Mobile View All Button -->
        <div class="mt-8 text-center md:hidden">
            <a href="/blog" class="inline-flex items-center justify-center gap-2 text-indigo-600 font-bold bg-indigo-50 px-6 py-3 rounded-xl hover:bg-indigo-100 w-full transition-colors">
                مشاهده همه مقالات
                <x-icon.svg-icon name="arrow-left" class="w-5 h-5 rotate-180" />
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-slate-950 pt-12 pb-6 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center md:items-start gap-8 mb-8 border-b border-white/10 pb-8">
            <div class="flex flex-col items-center md:items-start gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center overflow-hidden">
                        <img src="{{ setting('site_logo') }}" alt="{{ setting('site_title') }}" class="w-full h-full object-contain p-1.5">
                    </div>
                    <span class="font-bold text-xl text-white">{{ setting('site_title') }}</span>
                </div>
                <p class="text-slate-400 max-w-xs text-center md:text-right text-sm leading-loose">
                    {{ setting('site_description') }}
                </p>
            </div>

            <a referrerpolicy='origin' target='_blank' href='https://trustseal.enamad.ir/?id=768123&Code=l9RRZ5lHfO29AbwWPmF1DoI51sq3ITgp'><img referrerpolicy='origin' src='https://trustseal.enamad.ir/logo.aspx?id=768123&Code=l9RRZ5lHfO29AbwWPmF1DoI51sq3ITgp' alt='' style='cursor:pointer' code='l9RRZ5lHfO29AbwWPmF1DoI51sq3ITgp'></a>

            <div class="flex gap-12">
                <div class="flex flex-col gap-3 text-center md:text-right">
                    <h5 class="text-white font-bold mb-2 text-sm">لینک‌های سریع</h5>
                    <a href="#features" class="text-slate-400 hover:text-white transition-colors text-sm">امکانات سیستم</a>
                    <a href="#pricing" class="text-slate-400 hover:text-white transition-colors text-sm">تعرفهٔ اشتراک</a>
                    <a href="#blog" class="text-slate-400 hover:text-white transition-colors text-sm">مجله و بلاگ</a>
                </div>
                <div class="flex flex-col gap-3 text-center md:text-right">
                    <h5 class="text-white font-bold mb-2 text-sm">حساب کاربری</h5>
                    <a href="/login" class="text-slate-400 hover:text-white transition-colors text-sm">ورود به پنل</a>
                    <a href="/register" class="text-slate-400 hover:text-white transition-colors text-sm">ثبت‌نام جدید</a>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center">
            <p class="text-slate-500 text-sm w-full">
                © ۲۰۲۶ تمامی حقوق برای <span class="text-slate-300">{{ setting('site_title') }}</span> محفوظ است.
            </p>
        </div>

    </div>
</footer>

<!-- JavaScript for Mobile Dropdown Menu -->
<script>
    (function() {
        const menuBtn = document.getElementById('mobileMenuBtn');
        const dropdown = document.getElementById('mobileDropdown');
        const overlay = document.getElementById('mobileOverlay');
        const menuIcon = document.getElementById('menuIcon');
        const closeIcon = document.getElementById('closeIcon');

        let isOpen = false;

        function openMenu() {
            dropdown.classList.add('open');
            overlay.classList.add('visible');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            isOpen = true;
        }

        function closeMenu() {
            dropdown.classList.remove('open');
            overlay.classList.remove('hidden');
            overlay.classList.remove('visible');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            document.body.style.overflow = '';
            isOpen = false;
        }

        function toggleMenu() {
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        if (menuBtn) {
            menuBtn.addEventListener('click', toggleMenu);
        }

        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }

        const dropdownLinks = dropdown.querySelectorAll('a');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeMenu();
            }
        });
    })();
</script>
</body>
</html>
