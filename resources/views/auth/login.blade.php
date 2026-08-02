@extends('auth.layout')
@section('main')
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-200/60 p-6 sm:p-8 border border-gray-100">

        @if (session('success'))
            <div class="bg-green-50 border-r-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm text-sm mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-black text-gray-900">خوش آمدید 👋</h2>
            <p class="text-gray-500 mt-2 text-sm">برای ادامه وارد حساب کاربری خود شوید</p>
        </div>

        <form action="{{ route('login.post') }}" method="post" class="space-y-4 sm:space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">شماره تلفن</label>
                <input
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    dir="ltr"
                    inputmode="numeric"
                    class="w-full px-4 py-3 text-right bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="09xxxxxxxxx"
                >
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">رمز عبور</label>
                    <a href="{{ route('forgot.password.form') }}" class="text-xs text-indigo-600 font-medium hover:underline">فراموشی رمز؟</a>
                </div>
                <input
                    type="password"
                    name="password"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="••••••••"
                >
            </div>

            <label class="flex items-center gap-2 cursor-pointer w-fit">
                <input
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
                <span class="text-sm text-gray-600">مرا به خاطر بسپار</span>
            </label>

            <button
                type="submit"
                class="w-full bg-gradient-to-l from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/25 focus:ring-4 focus:ring-indigo-200 transition-all"
            >
                ورود به حساب
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6 sm:mt-8">
            حساب کاربری ندارید؟
            <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline transition-all">ثبت‌نام کنید</a>
        </p>
    </div>
@endsection
