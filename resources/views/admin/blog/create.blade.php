@extends('admin.layout.app')
@section('main')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
        <div class="w-full max-w-2xl mx-auto">
            <div class="flex items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 ml-4">ایجاد مقاله جدید</h2>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="bg-red-100 border-r-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle ml-2"></i>
                                <span class="font-bold">خطا:</span>
                            </div>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-6">
                        <label for="title" class="block text-gray-700 text-sm font-semibold mb-2">عنوان مقاله</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2
                               focus:ring-blue-500 transition-colors duration-200"
                               placeholder="عنوان مقاله را وارد کنید" required>
                    </div>
                    <div class="mb-6">
                        <label for="summary" class="block text-gray-700 text-sm font-semibold mb-2">خلاصه مقاله</label>
                        <textarea id="summary" name="summary" rows="4"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2
                                  focus-ring-blue-500 transition-colors duration-200"
                                  placeholder="خلاصه کوتاهی از مقاله بنویسید" required>{{ old('summary') }}</textarea>
                    </div>
                    <div class="mb-6">
                        <label for="content" class="block text-gray-700 text-sm font-semibold mb-2">محتوای کامل</label>
                        <textarea id="mytextarea" name="description" rows="10"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2
                                  focus:ring-blue-500 transition-colors duration-200"
                                  placeholder="محتوای اصلی مقاله را وارد کنید" >{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 text-sm font-semibold mb-2">تصویر مقاله</label>
                        <div class="flex items-center">
                            <label class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg
                            font-semibold transition-colors duration-200">
                                <span>انتخاب فایل</span>
                                <input type="file" id="image" name="image" class="hidden">
                            </label>
    {{--                        <span id="file-name" class="mr-4 text-gray-500">فایلی انتخاب نشده است</span>--}}
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">پست ویژه</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="featured" value="1" {{ old('featured') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="mr-2 text-gray-700">بله</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="featured" value="0" {{ old('featured') == '0' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="mr-2 text-gray-700">خیر</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-between space-x-4 space-x-reverse">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3
                        px-8 rounded-full shadow-lg transition-colors duration-200">
                            <i class="fas fa-save ml-2"></i> ذخیره مقاله
                        </button>
                        <a href="{{ route('admin.blog.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white
                        font-semibold py-3 px-8 rounded-full transition-colors duration-200">
                            <i class="fas fa-arrow-right ml-2"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
