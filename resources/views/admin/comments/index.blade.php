@extends('admin.layout.app')
@section('main')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-10">
        @if(session('success'))
            <div class="bg-green-50 border-r-4 border-green-500 text-green-700 p-4 rounded-lg mb-8 shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-xl ml-3"></i>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
            <h2 class="text-3xl font-extrabold text-gray-800">
                <i class="fas fa-newspaper text-purple-600 ml-3"></i>
                 نظرات
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 overflow-x-auto">
            <table class="min-w-full text-right divide-y divide-gray-200">
                <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm uppercase font-semibold tracking-wider">
                    <th class="py-4 px-4 rounded-r-xl">#</th>
                    <th class="py-4 px-4">کاربر</th>
                    <th class="py-4 px-4">نظر</th>
                    <th class="py-4 px-4 text-center">وضعیت</th>
                    <th class="py-4 px-4">تاریخ ایجاد</th>
                    <th class="py-4 px-4 rounded-l-xl text-center">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($comments as $comment)
                    <tr class="hover:bg-purple-50/50 transition-colors duration-200">
                        <td class="py-4 px-4 text-gray-500">@fa($loop->iteration)</td>
                        <td class="py-4 px-4 font-semibold text-gray-800 max-w-xs truncate">{{ $comment->name }}</td>
                        <td class="py-4 px-4 text-gray-600 max-w-sm text-sm">{{$comment->comment}}</td>
                        <td class="py-4 px-4 text-center">
                            @if($comment->approved)
                                <span class="bg-green-200 text-gray-700 py-1 px-3 rounded-full text-xs">تایید شده</span>
                            @else
                                <span class="bg-red-200 text-gray-700 py-1 px-3 rounded-full text-xs">تایید نشده</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center space-x-3 space-x-reverse">

                                <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                                    @csrf
                                    @method('patch')
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition duration-200">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                      onsubmit="return confirm('آیا مطمئن هستید که می‌خواهید  حذف کنید؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="حذف" class="text-red-600 hover:text-red-800 p-2
                                    rounded-full hover:bg-red-100 transition duration-200 focus:outline-none">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500 text-lg">
{{--                            <i class="fas fa-book-open mb-3 text-3xl text-gray-300"></i>--}}
                            <p>هیچ موردی برای نمایش وجود ندارد.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8 flex justify-center">
            {{ $comments->links() }}
        </div>
    </main>
@endsection
