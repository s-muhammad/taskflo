<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>{{ setting('site_title') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="preload" href="/font/vazir-font-v16.1.0/Vazir.woff" as="font" type="font/woff2" crossorigin>

    <style>
        html { color-scheme: light; }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #111827;
            box-shadow: 0 0 0px 1000px #f9fafb inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* جلوگیری از زوم خودکار سافاری موبایل روی فوکوس اینپوت */
        input { font-size: 16px; }
        @media (min-width: 640px) {
            input { font-size: 0.875rem; }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-100 via-slate-100 to-indigo-200 text-gray-900">
<div class="min-h-screen flex flex-col items-center justify-center px-4 py-10 sm:p-6">

    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-6 sm:mb-8">
        <span class="text-lg sm:text-xl font-black tracking-tight">DoTask</span>
        <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </span>
    </a>

    <div class="flex flex-col gap-4 sm:gap-6 w-full max-w-md">
        @if ($errors->any())
            <div class="bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm text-sm" role="alert">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span class="font-bold">خطا در ارسال فرم</span>
                </div>
                <ul class="mt-2 list-disc list-inside space-y-1 pr-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('main')
    </div>
</div>
</body>
</html>
