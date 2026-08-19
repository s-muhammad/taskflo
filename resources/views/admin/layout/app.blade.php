<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body class="bg-gradient-to-br from-gray-50 to-indigo-50 font-sans text-gray-800">

<div class="flex h-screen antialiased">
    @include('admin.layout.sidebar')
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md p-4 flex justify-between items-center relative z-20 border-b border-gray-200">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none focus:ring-2
                        focus:ring-blue-500 rounded-lg p-2 transition-colors duration-200 ml-4">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="text-2xl font-extrabold text-gray-900">
                    <a href="{{ url('/') }}" target="_blank" class="text-gray-600 hover:text-blue-600">مشاهده سایت</a>
                </div>
            </div>

            {{-- بخش عملیات کاربر و نوتیفیکیشن‌ها --}}
{{--            <div class="flex items-center space-x-4 space-x-reverse">--}}

{{--                --}}{{-- دکمه نوتیفیکیشن --}}
{{--                <button class="text-gray-500 hover:text-blue-600 p-2 rounded-full relative transition-colors duration-200">--}}
{{--                    <i class="fas fa-bell text-xl"></i>--}}
{{--                    <span class="absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-red-500 border border-white"></span>--}}
{{--                </button>--}}

{{--                --}}{{-- دراپ‌داون کاربر --}}
{{--                <div class="relative">--}}
{{--                    <button id="user-menu-button" class="flex items-center focus:outline-none p-1 rounded-full bg-gray-100 hover:bg-gray-200 transition duration-150">--}}
{{--                        <span class="ml-3 text-sm font-semibold text-gray-700 hidden sm:block">نام ادمین</span>--}}
{{--                        <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm"--}}
{{--                             src="https://via.placeholder.com/150/0000FF/808080?text=AD"--}}
{{--                             alt="User Avatar">--}}
{{--                        <i class="fas fa-chevron-down text-xs mr-2 text-gray-400"></i>--}}
{{--                    </button>--}}

{{--                    --}}{{-- منوی دراپ‌داون --}}
{{--                    <div id="user-menu" class="hidden absolute left-0 mt-3 w-48 rounded-lg shadow-xl bg-white border border-gray-200 z-30 transition-all duration-300 transform origin-top-left">--}}
{{--                        <div class="p-3 border-b border-gray-100">--}}
{{--                            <p class="text-sm font-semibold text-gray-800">حساب کاربری</p>--}}
{{--                            <p class="text-xs text-gray-500 truncate">admin@example.com</p>--}}
{{--                        </div>--}}
{{--                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-150">--}}
{{--                            <i class="fas fa-user-circle ml-2"></i> پروفایل--}}
{{--                        </a>--}}
{{--                        <a href="{{ route('admin.setting.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-150">--}}
{{--                            <i class="fas fa-cog ml-2"></i> تنظیمات--}}
{{--                        </a>--}}
{{--                        <div class="border-t border-gray-100"></div>--}}
{{--                        <a href="#" onclick="document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-150">--}}
{{--                            <i class="fas fa-sign-out-alt ml-2"></i> خروج--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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
