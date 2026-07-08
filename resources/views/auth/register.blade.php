@extends('auth.layout')
@section('main')
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-200/60 p-6 sm:p-8 border border-gray-100">

        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-black text-gray-900">ایجاد حساب جدید 🚀</h2>
            <p class="text-gray-500 mt-2 text-sm">چند ثانیه بیشتر تا شروع مدیریت هوشمند کارها</p>
        </div>

        <form action="{{ route('register.post') }}" method="post" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="مثال: علی احمدی"
                >
            </div>

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
                <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                <input
                    type="password"
                    name="password"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="حداقل ۸ کاراکتر"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-l from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/25 focus:ring-4 focus:ring-indigo-200 transition-all mt-2"
            >
                ایجاد حساب کاربری
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6 sm:mt-8">
            قبلاً ثبت‌نام کرده‌اید؟
            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline transition-all">وارد شوید</a>
        </p>
    </div>
@endsection
