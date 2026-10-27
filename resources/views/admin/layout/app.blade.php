<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>پنل مدیریت</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea',
            license_key: 'gpl' // gpl for open source, T8LK:... for commercial
        });
    </script>
</head>
<body class="bg-slate-100 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-200">

<div class="flex h-screen antialiased">
    @include('admin.layout.sidebar')
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white dark:bg-slate-900 shadow-soft p-4 flex justify-between items-center relative z-20 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="md:hidden text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 focus:outline-none focus:ring-2
                        focus:ring-indigo-500 rounded-xl p-2 transition-colors duration-200 ms-4">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="text-lg font-bold text-slate-900 dark:text-white">
                    <a href="{{ url('/') }}" target="_blank" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">مشاهده سایت</a>
                </div>
            </div>
        </header>
        @yield('main')
    </div>
</div>

 {{--<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--    @csrf--}}
{{--</form>--}}

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    // 1. منطق باز/بسته کردن سایدبار (تکمیل شده)
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        // در حالت موبایل، z-index را بالا ببرید تا روی محتوا بیاید
        if (window.innerWidth < 768) {
            sidebar.classList.toggle('z-50');
        }
    });

    // 2. بستن سایدبار با کلیک بیرون (تکمیل شده)
    document.addEventListener('click', (event) => {
        const isSidebarOpen = !sidebar.classList.contains('hidden');
        const clickedInsideSidebar = sidebar.contains(event.target);
        const clickedOnToggle = sidebarToggle.contains(event.target);
        const isMobile = window.innerWidth < 768;

        if (isMobile && isSidebarOpen && !clickedInsideSidebar && !clickedOnToggle) {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('z-50'); // حذف z-50 هنگام بسته شدن
        }
    });

    // 3. منطق دراپ‌داون کاربر (تکمیل شده)
    if (userMenuButton) {
        userMenuButton.addEventListener('click', (event) => {
            userMenu.classList.toggle('hidden');
            event.stopPropagation();
        });

        document.addEventListener('click', (event) => {
            if (!userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }

</script>

@livewireScripts

</body>
</html>
