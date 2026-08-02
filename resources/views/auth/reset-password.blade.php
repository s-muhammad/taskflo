@extends('auth.layout')
@section('main')
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-200/60 p-6 sm:p-8 border border-gray-100">

        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-black text-gray-900">تغییر رمز عبور 🔄</h2>
            <p class="text-gray-500 mt-2 text-sm">رمز عبور جدید خود را وارد کنید</p>
        </div>

        <form action="{{ route('reset.password.post') }}" method="post" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور جدید</label>
                <input
                    type="password"
                    name="password"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="حداقل ۶ کاراکتر"
                    required
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-l from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/25 focus:ring-4 focus:ring-indigo-200 transition-all mt-2"
            >
                تغییر رمز عبور
            </button>
        </form>
    </div>
@endsection
