<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_title') }}</title>
    @vite(['resources/js/app.js','resources/css/app.css'])
    <link rel="preload" href="/font/vazir-font-v16.1.0/Vazir.woff" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/font/vazir-font-v16.1.0/Vazir-Bold.woff" as="font" type="font/woff2" crossorigin>
    <style>
        body { font-family: 'Vazirmatn', sans-serif; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 5s ease-in-out infinite; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* منوی موبایل به صورت dropdown از بالا به پایین */
        .mobile-dropdown {
            position: fixed;
            top: 80px; /* ارتفاع نوبار */
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border-bottom: 1px solid #e2e8f0;
            z-index: 40;
            max-height: calc(100vh - 80px);
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
            top: 80px;
        }

        .mobile-overlay.visible {
            opacity: 1;
            visibility: visible;
        }

        /* انیمیشن برای آیتم‌های منو */
        .mobile-dropdown a {
            transform: translateX(0);
            transition: all 0.2s ease;
        }

        .mobile-dropdown a:active {
            transform: translateX(-5px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-600 selection:text-white overflow-x-hidden">

<!-- Navbar -->
<nav class="fixed w-full z-50 transition-all duration-300 glass-effect">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/40">
                    <x-icon.svg-icon name="check" class="w-6 h-6 text-white" />
                </div>
                <span class="font-bold text-2xl text-slate-900 tracking-tight">{{ setting('site_title') }}</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">امکانات</a>
                <a href="#pricing" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">تعرفه‌ها</a>
                <a href="{{url('/blog')}}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">بلاگ</a>
            </div>

            <!-- Desktop CTA Buttons -->
            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-indigo-600 font-medium px-4 py-2 transition-colors">ورود</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transform hover:-translate-y-0.5">ثبت‌نام رایگان</a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobileMenuBtn" class="text-slate-600 hover:text-indigo-600 focus:outline-none transition-transform duration-200">
                    <x-icon.svg-icon name="menu" id="menuIcon" class="h-6 w-6" />
                    <x-icon.svg-icon name="close" id="closeIcon" class="h-6 w-6 hidden" />
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Dropdown Menu (از بالا به پایین) -->
<div id="mobileOverlay" class="mobile-overlay"></div>
<div id="mobileDropdown" class="mobile-dropdown">
    <div class="px-4 py-6 space-y-6">
        <!-- Navigation Links -->
        <div class="flex flex-col gap-2">
            <a href="#features" class="flex items-center justify-between py-3 px-4 text-slate-700 hover:text-indigo-600
            hover:bg-indigo-50 rounded-xl font-medium transition-all border border-slate-100">
                <span>امکانات</span>
            </a>
            <a href="#pricing" class="flex items-center justify-between py-3 px-4 text-slate-700 hover:text-indigo-600
            hover:bg-indigo-50 rounded-xl font-medium transition-all border border-slate-100">
                <span>تعرفه‌ها</span>
            </a>
            <a href="{{url('/blog')}}" class="flex items-center justify-between py-3 px-4 text-slate-700 hover:text-indigo-600
            hover:bg-indigo-50 rounded-xl font-medium transition-all border border-slate-100">
                <span>بلاگ</span>
            </a>
        </div>
    </div>
</div>

<!-- Hero Section  -->
<section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-indigo-600/10 blur-3xl opacity-60 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-right">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 font-semibold text-sm mb-6 border border-indigo-100">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    نسخه ۱.۰ منتشر شد!
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.3] mb-6">
                    مدیریت زمان و تسک‌ها، <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">هوشمندانه‌تر از همیشه</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 mb-8 max-w-2xl leading-relaxed">
                    با Smart Planner بهره‌وری خود را به اوج برسانید. برنامه‌ریزی روزانه، دسته‌بندی تسک‌ها و مشاهده نمودار عملکرد در یک محیط زیبا، مدرن و کارآمد.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{route('dashboard')}}" class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 transform hover:-translate-y-1">
                        شروع کنید - رایگان
                        <x-icon.svg-icon name="arrow-left" class="w-5 h-5 rotate-180" />
                    </a>
{{--                    <a href="#dashboard" class="flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border-2 border-slate-200 text-slate-700 px-8 py-4 rounded-2xl font-bold text-lg transition-all hover:border-indigo-200">--}}
{{--                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>--}}
{{--                        مشاهده محیط پنل--}}
{{--                    </a>--}}
                </div>

                <div class="mt-10 flex items-center gap-4 text-sm text-slate-500">
                    <div class="flex -space-x-3 space-x-reverse">
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs z-30">ع‌م</div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs z-20">س‌ت</div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs z-10">پ‌ر</div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-slate-600 text-xs font-bold z-0">+1k</div>
                    </div>
                    <p>مورد اعتماد بیش از <span class="font-bold text-slate-800">۱,۰۰۰</span> کاربر فعال</p>
                </div>
            </div>

            <!-- Dashboard Mockup -->
            <div class="relative lg:mr-10 animate-float mt-12 lg:mt-0 hidden md:block" id="dashboard">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-blue-400 rounded-3xl transform rotate-3 opacity-20 shadow-2xl"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col h-full">
                    <div class="bg-slate-50 border-b border-slate-100 px-4 py-3 flex items-center justify-between">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="text-xs font-bold text-slate-400">Smart Planner App</div>
                    </div>
                    <div class="p-6 flex flex-col gap-5 bg-slate-50/50">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                        <x-icon.svg-icon name="list" class="w-5 h-5" />
                                    </div>
                                    <h4 class="text-slate-500 text-sm font-bold">کل تسک‌ها</h4>
                                </div>
                                <p class="text-2xl font-black text-slate-800">۲۴</p>
                            </div>
                            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        <x-icon.svg-icon name="check" class="w-5 h-5" />
                                    </div>
                                    <h4 class="text-slate-500 text-sm font-bold">انجام شده</h4>
                                </div>
                                <p class="text-2xl font-black text-slate-800">۱۸</p>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                            <h4 class="font-bold text-slate-800 mb-4 text-sm">تسک‌های امروز</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded border-2 border-indigo-500 cursor-pointer"></div>
                                        <span class="text-slate-700 text-sm font-medium">طراحی صفحه لندینگ</span>
                                    </div>
                                    <span class="text-[10px] bg-red-100 text-red-600 px-2 py-1 rounded-md font-bold">مهم</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded border-2 border-slate-300 bg-slate-200 flex items-center justify-center">
                                            <x-icon.svg-icon name="check" class="w-4 h-4 text-white" />
                                        </div>
                                        <span class="text-slate-400 text-sm font-medium line-through">توسعه API کاربری</span>
                                    </div>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-600 px-2 py-1 rounded-md font-bold">تکمیل</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 bg-white relative"  loading="lazy">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-indigo-600 font-bold tracking-wide mb-3 text-sm bg-indigo-50 border border-indigo-100 inline-block px-4 py-1.5 rounded-full">امکانات سیستم</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">هر آنچه برای مدیریت نیاز دارید</h3>
            <p class="text-lg text-slate-500 leading-relaxed">ابزارهای ساده اما قدرتمند برای دسته‌بندی کارها، مدیریت زمان و مشاهده روند پیشرفت پروژه‌های شما.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                    <x-icon.svg-icon name="list" class="w-7 h-7" />
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">مدیریت آسان تسک‌ها</h4>
                <p class="text-slate-500 leading-relaxed text-sm">تسک‌های جدید اضافه کنید، وضعیت آن‌ها را تغییر دهید و اولویت‌بندی کنید. همه‌چیز در یک نگاه قابل مشاهده است.</p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                    <x-icon.svg-icon name="list" class="w-7 h-7" />
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">گزارش و نمودار</h4>
                <p class="text-slate-500 leading-relaxed text-sm">عملکرد خود را با نمودارهای گرافیکی بررسی کنید. ببینید در طول ماه چقدر پیشرفت داشته‌اید.</p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-14 h-14 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                    <x-icon.svg-icon name="list" class="w-7 h-7" />
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">زمان‌بندی هوشمند</h4>
                <p class="text-slate-500 leading-relaxed text-sm">برای کارهای خود مهلت زمانی (Deadline) تعیین کنید تا هرگز کارهای مهم را فراموش نکنید.</p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-20 bg-slate-50 overflow-hidden relative"  loading="lazy">
    <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-20">
            <div class="w-full lg:w-1/2 text-center lg:text-right">
                <span class="text-indigo-600 font-bold tracking-wide mb-4 text-sm bg-indigo-100 border border-indigo-200 inline-block px-4 py-1.5 rounded-full">تعرفه اشتراک</span>
                <h3 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">سرمایه‌گذاری روی<br/>بهره‌وری تیم شما</h3>
                <p class="text-lg text-slate-600 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                    ما به جای پلن‌های پیچیده، تمام امکانات سیستم را در یک پکیج کامل با قیمتی منصفانه ارائه می‌دهیم. بدون هیچ ریسکی، یک ماه اول را کاملاً رایگان استفاده کنید.
                </p>
                <div class="flex flex-col gap-4 text-slate-700 font-medium">
                    <div class="flex items-center gap-3 justify-center lg:justify-start">
                        <div class="bg-white p-1 rounded-full shadow-sm">
                            <x-icon.svg-icon name="check" class="w-5 h-5 text-emerald-500" />
                        </div>
                        لغو اشتراک در هر زمان به سادگی
                    </div>
                    <div class="flex items-center gap-3 justify-center lg:justify-start">
                        <div class="bg-white p-1 rounded-full shadow-sm">
                            <x-icon.svg-icon name="check" class="w-5 h-5 text-emerald-500" />
                        </div>
                        بدون نیاز به کارت بانکی برای تست رایگان
                    </div>
                </div>
            </div>

            <div class="w-full max-w-md lg:w-[380px] shrink-0">
                <div class="bg-white rounded-3xl p-6 border-2 border-indigo-600 shadow-2xl shadow-indigo-600/20 relative">
                    <div class="absolute -top-4 right-1/2 translate-x-1/2 bg-indigo-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg shadow-indigo-600/40 flex items-center gap-2 whitespace-nowrap">
                        <x-icon.svg-icon name="star" class="w-5 h-5 text-indigo-200" fill="currentColor" />
                        ۳۰ روز اول کاملاً رایگان
                    </div>
                    <div class="text-center mt-6 mb-8">
                        <h4 class="text-2xl font-extrabold text-slate-900">اشتراک ویژه</h4>
                        <p class="text-slate-500 mt-2">دسترسی نامحدود به تمامی امکانات</p>
                    </div>
                    <div class="mb-8 text-center bg-indigo-50/50 rounded-2xl py-6 border border-indigo-50">
                        <span class="text-5xl font-black text-indigo-600">۴۹,۰۰۰</span>
                        <span class="text-slate-600 text-base font-medium block mt-2">تومان / ماهانه</span>
                    </div>
                    <ul class="space-y-5 mb-8 text-right px-2">
                        <li class="flex items-center gap-3 text-slate-700 font-medium">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-icon.svg-icon name="check" class="w-4 h-4" />
                            </div>
                            تسک‌ها و پروژه‌های نامحدود
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 font-medium">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-icon.svg-icon name="check" class="w-4 h-4" />
                            </div>
                            گزارش‌گیری و نمودارهای پیشرفته
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 font-medium">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-icon.svg-icon name="check" class="w-4 h-4" />
                            </div>
                            پشتیبانی اولویت‌دار و سریع
                        </li>
                    </ul>
                    <a href="/register" class="flex items-center justify-center w-full py-4 px-4 rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-1">
                        شروع تست رایگان
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section id="blog" class="py-24 bg-white relative border-b border-slate-100"  loading="lazy">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-end mb-12">
            <div class="max-w-2xl">
                <h2 class="text-indigo-600 font-bold tracking-wide mb-3 text-sm bg-indigo-50 border border-indigo-100 inline-block px-4 py-1.5 rounded-full">بلاگ و آموزش</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">آخرین مقالات بهره‌وری</h3>
            </div>
            <a href="/blog" class="hidden md:flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-800 transition-colors">
                مشاهده همه
                <x-icon.svg-icon name="arrow-left" class="w-5 h-5 rotate-180" />
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Blog Post 1 -->
            <div class="bg-slate-50 rounded-3xl overflow-hidden border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 group cursor-pointer">
                <!-- Image Placeholder (Gradient) -->
                <div class="h-48 bg-gradient-to-br from-indigo-100 via-blue-50 to-slate-200 flex items-center justify-center relative overflow-hidden">
                    <svg class="w-16 h-16 text-indigo-200 transform group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="p-6">
                    <div class="text-sm text-slate-400 mb-2 font-medium">۲۰ فروردین ۱۴۰۵</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">تکنیک پومودورو: راز تمرکز عمیق</h4>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">چگونه با استفاده از بازه‌های زمانی ۲۵ دقیقه‌ای، بهره‌وری خود را در طول روز دو برابر کنیم؟ در این مقاله به بررسی...</p>
                    <span class="text-indigo-600 font-bold text-sm flex items-center gap-1">مطالعه مقاله <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></span>
                </div>
            </div>

            <!-- Blog Post 2 -->
            <div class="bg-slate-50 rounded-3xl overflow-hidden border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 group cursor-pointer">
                <!-- Image Placeholder (Gradient) -->
                <div class="h-48 bg-gradient-to-br from-emerald-100 via-teal-50 to-slate-200 flex items-center justify-center relative overflow-hidden">
                    <svg class="w-16 h-16 text-emerald-200 transform group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div class="p-6">
                    <div class="text-sm text-slate-400 mb-2 font-medium">۱۵ فروردین ۱۴۰۵</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">چگونه لیست تسک‌های روزانه بنویسیم؟</h4>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">نوشتن یک To-Do List اشتباه می‌تواند باعث استرس بیشتر شود. بیاموزید چگونه وظایف خود را اصولی اولویت‌بندی کنید.</p>
                    <span class="text-indigo-600 font-bold text-sm flex items-center gap-1">مطالعه مقاله <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></span>
                </div>
            </div>

            <!-- Blog Post 3 -->
            <div class="bg-slate-50 rounded-3xl overflow-hidden border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 group cursor-pointer hidden md:block">
                <!-- Image Placeholder (Gradient) -->
                <div class="h-48 bg-gradient-to-br from-orange-100 via-yellow-50 to-slate-200 flex items-center justify-center relative overflow-hidden">
                    <svg class="w-16 h-16 text-orange-200 transform group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="p-6">
                    <div class="text-sm text-slate-400 mb-2 font-medium">۱۰ فروردین ۱۴۰۵</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">غلبه بر اهمال‌کاری در ۳ قدم ساده</h4>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">به تعویق انداختن کارها دشمن شماره یک موفقیت است. با این تکنیک‌های روانشناسی، همین امروز دست به کار شوید.</p>
                    <span class="text-indigo-600 font-bold text-sm flex items-center gap-1">مطالعه مقاله <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></span>
                </div>
            </div>
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
<footer class="bg-slate-900 pt-16 pb-8 border-t-4 border-indigo-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center md:items-start gap-8 mb-12 border-b border-slate-800 pb-12">
            <div class="flex flex-col items-center md:items-start gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                        <x-icon.svg-icon name="check" class="w-5 h-5" />
                    </div>
                    <span class="font-bold text-2xl text-white">Smart Planner</span>
                </div>
                <p class="text-slate-400 max-w-xs text-center md:text-right text-sm leading-relaxed">
                    بهترین ابزار برای برنامه‌ریزی روزانه و مدیریت تسک‌ها برای فریلنسرها و تیم‌های خلاق.
                </p>
            </div>

            <div class="flex gap-16">
                <div class="flex flex-col gap-3 text-center md:text-right">
                    <h5 class="text-white font-bold mb-2">لینک‌های سریع</h5>
                    <a href="#features" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">امکانات سیستم</a>
                    <a href="#pricing" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">تعرفه‌های اشتراک</a>
                    <a href="#blog" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">مجله و بلاگ</a>
                </div>
                <div class="flex flex-col gap-3 text-center md:text-right">
                    <h5 class="text-white font-bold mb-2">حساب کاربری</h5>
                    <a href="/login" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">ورود به پنل</a>
                    <a href="/register" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">ثبت‌نام جدید</a>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center">
            <p class="text-slate-500 text-sm w-full">
                © 2026 تمامی حقوق برای <span class="text-slate-300">Smart Planner</span> محفوظ است. طراحی شده با عشق و Tailwind.
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

        // Close menu when clicking on any link inside dropdown
        const dropdownLinks = dropdown.querySelectorAll('a');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeMenu();
            }
        });
    })();
</script>
</body>
</html>
