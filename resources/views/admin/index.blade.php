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

            {{-- نمودار تعداد کاربران --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-soft border border-slate-200 dark:border-slate-800 p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-1 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-chart-column text-indigo-500"></i>
                        کاربران جدید
                    </span>
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500">۶ ماه اخیر</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-5">تعداد ثبت‌نام در هر ماه</p>
                <div class="relative h-64 w-full">
                    <canvas id="adminUsersChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('adminUsersChart');
            if (!canvas || typeof Chart === 'undefined') return;

            const labels = @js(array_map('fa_digits', $userChart['labels']));
            const values = @js($userChart['counts']);

            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.95)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.35)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'کاربران جدید',
                        data: values,
                        backgroundColor: gradient,
                        borderRadius: 10,
                        maxBarThickness: 42,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            rtl: true,
                            callbacks: {
                                label: (item) => ' ' + item.parsed.y + ' کاربر'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#94a3b8' },
                            grid: { color: 'rgba(148, 163, 184, 0.15)' }
                        },
                        x: {
                            ticks: { color: '#94a3b8' },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endsection
