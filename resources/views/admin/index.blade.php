@extends('admin.layout.app')
@section('main')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 dark:bg-slate-950 p-6 md:p-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">پیشخوان مدیریت</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $adminStats = [
                    ['route' => 'admin.comments.index', 'label' => 'نظر جدید', 'val' => $comments, 'icon' => 'fa-comment', 'color' => 'indigo'],
                    ['route' => 'admin.ticket.index', 'label' => 'تیکت جدید', 'val' => $tickets, 'icon' => 'fa-ticket', 'color' => 'emerald'],
                    ['route' => 'admin.user.index', 'label' => 'کاربر', 'val' => $users, 'icon' => 'fa-users', 'color' => 'amber'],
                    ['route' => '#', 'label' => 'اشتراک فعال', 'val' => '-', 'icon' => 'fa-star', 'color' => 'sky'],
                ];
            @endphp

            @foreach($adminStats as $stat)
            <a href="{{ $stat['route'] != '#' ? route($stat['route']) : '#' }}" class="block group">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-slate-200 dark:border-slate-800 p-6 transition-all group-hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/20 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 flex items-center justify-center">
                            <i class="fas {{ $stat['icon'] }} text-xl"></i>
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">آمار</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white">@fa($stat['val'])</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $stat['label'] }}</p>
                </div>
            </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-soft border border-slate-200 dark:border-slate-800 p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center justify-between">
                    <span>آخرین مقالات</span>
                    <a href="{{ route('admin.blog.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400">مشاهده همه</a>
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="text-slate-500 dark:text-slate-400 text-xs border-b border-slate-100 dark:border-slate-800">
                                <th class="py-3 px-2">#</th>
                                <th class="py-3 px-2">عنوان</th>
                                <th class="py-3 px-2">تصویر</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @forelse($blogs as $blog)
                                <tr class="text-sm">
                                    <td class="py-4 px-2 text-slate-500">@fa($loop->iteration)</td>
                                    <td class="py-4 px-2 font-medium text-slate-700 dark:text-slate-200">{{ $blog->title }}</td>
                                    <td class="py-4 px-2">
                                        <img src="{{ asset($blog->image) }}" class="w-10 h-10 rounded-lg object-cover">
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-10 text-slate-400">موردی یافت نشد.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
