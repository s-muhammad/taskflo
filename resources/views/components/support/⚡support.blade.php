<?php

use App\Models\Blog;
use App\Models\Ticket;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {

    #[Computed]
    public function blogs()
    {
        return Blog::latest()->take(3)->get();
    }

    #[Computed]
    public function tickets()
    {
        return Ticket::where('user_id', auth()->id())->where('parent_id', null)->get();
    }

    #[Computed]
    public function ticketCount()
    {
        return Ticket::where('user_id', auth()->id())->where('parent_id', null)->count();
    }

    #[On('refresh')]
    public function refresh()
    {

    }
};
?>
<div class="space-y-4">

    <!-- هدر صفحه -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 flex flex-wrap items-center justify-between gap-3 shadow-soft">
        <div>
            <h1 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">پشتیبانی</h1>
            <a href="{{ route('help') }}" wire:navigate
                    class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition">
                قبل از ایجاد تیکت، بخش آموزش را ببینید
                <i class="fas fa-chevron-left text-[10px]"></i>
            </a>
        </div>
        <button wire:click="$dispatchTo('support.ticket-modal','open-modal')"
                class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl shadow-soft
                   transition-all flex items-center justify-center gap-2 font-bold text-sm active:scale-95 cursor-pointer">
            <i class="fas fa-plus text-xs"></i>
            تیکت جدید
        </button>
    </div>

    <!-- جدول تیکت‌ها -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-soft">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                <tr class="border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3 px-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">شماره تیکت</th>
                    <th class="py-3 px-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">موضوع</th>
                    <th class="py-3 px-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">وضعیت</th>
                    <th class="py-3 px-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">تاریخ</th>
                    <th class="py-3 px-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($this->tickets as $ticket)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <td class="py-3 px-4 font-bold text-slate-900 dark:text-slate-100">@fa($ticket->id)#</td>
                        <td class="py-3 px-4 text-slate-700 dark:text-slate-300">{{ $ticket->subject }}</td>
                        <td class="py-3 px-4">
                            @php
                                $statusStyles = [
                                    'closed' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400',
                                    'in_progress' => 'bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400',
                                    'open' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
                                ];
                                $statusLabels = ['closed' => 'پاسخ داده شده', 'in_progress' => 'در حال بررسی', 'open' => 'در انتظار پاسخ'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $statusStyles[$ticket->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusLabels[$ticket->status] ?? 'نامشخص' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400">@fa(\Morilog\Jalali\Jalalian::fromCarbon($ticket->created_at)->format('Y/m/d'))</td>
                        <td class="py-3 px-4">
                            <button
                                wire:click="$dispatchTo('support.ticket-modal','open-modal',{ ticket: {{ $ticket->id }} })"
                                class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors cursor-pointer">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- اگر تیکتی وجود نداشته باشد -->
        @if($this->ticketCount === 0)
            <div class="text-center py-12 px-6">
                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-ticket-alt text-lg text-slate-400"></i>
                </div>
                <p class="text-slate-900 dark:text-white font-bold text-sm">هنوز تیکتی ثبت نکرده‌اید</p>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-1.5">اولین تیکت خود را ایجاد کنید تا تیم پشتیبانی پاسخ دهد.</p>
                <button wire:click="$dispatchTo('support.ticket-modal','open-modal')"
                        class="mt-4 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold
                           px-4 py-2 rounded-xl shadow-soft transition-all active:scale-95 cursor-pointer">
                    <i class="fas fa-plus text-xs"></i>
                    تیکت جدید
                </button>
            </div>
        @endif
    </div>
</div>
