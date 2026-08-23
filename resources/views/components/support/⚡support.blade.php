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
<div class="flex-1 overflow-y-auto bg-slate-50">
    <div class="container mx-auto p-4 lg:p-8 max-w-6xl space-y-4">

        <!-- هدر ساده صفحه -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 md:p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-lg md:text-xl font-bold tracking-tight text-slate-900">پشتیبانی</h1>
                <a href="{{ route('help') }}" wire:navigate
                        class="mt-1.5 inline-flex items-center gap-1.5 text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                    قبل از ایجاد تیکت، بخش آموزش را ببینید
                    <i class="fas fa-chevron-left text-[10px]"></i>
                </a>
            </div>
            <button wire:click="$dispatchTo('support.ticket-modal','open-modal')"
                    class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-sm
                       transition-colors flex items-center justify-center gap-2 font-medium active:scale-95 cursor-pointer">
                <i class="fas fa-plus text-xs"></i>
                تیکت جدید
            </button>
        </div>

        <!-- جدول تیکت‌ها -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-3 px-5 text-right text-xs font-medium text-slate-500">شماره تیکت</th>
                        <th class="py-3 px-5 text-right text-xs font-medium text-slate-500">موضوع</th>
                        <th class="py-3 px-5 text-right text-xs font-medium text-slate-500">وضعیت</th>
                        <th class="py-3 px-5 text-right text-xs font-medium text-slate-500">تاریخ</th>
                        <th class="py-3 px-5 text-right text-xs font-medium text-slate-500">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($this->tickets as $ticket)
                        <tr class="border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-5 font-medium text-slate-900">{{ $ticket->id }}#</td>
                            <td class="py-3 px-5 text-slate-700">{{ $ticket->subject }}</td>
                            <td class="py-3 px-5">
                                @if($ticket->status == 'closed')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-medium">پاسخ داده شده</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium">در حال بررسی</span>
                                @elseif($ticket->status == 'open')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-medium">در انتظار پاسخ</span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-slate-500">{{ \Morilog\Jalali\Jalalian::fromCarbon($ticket->created_at)->format('Y/m/d') }}</td>
                            <td class="py-3 px-5">
                                <button
                                    wire:click="$dispatchTo('support.ticket-modal','open-modal',{ ticket: {{ $ticket->id }} })"
                                    class="text-slate-400 hover:text-indigo-600 transition-colors cursor-pointer">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- اگر تیکتی وجود نداشته باشد -->
            @if($this->ticketCount === 0)
                <div class="text-center py-14 px-6">
                    <i class="fas fa-ticket-alt text-3xl text-slate-300 mb-3"></i>
                    <p class="text-slate-900 font-medium text-sm">هنوز تیکتی ثبت نکرده‌اید</p>
                    <p class="text-slate-500 text-xs mt-1.5">اولین تیکت خود را ایجاد کنید تا تیم پشتیبانی پاسخ دهد.</p>
                    <button wire:click="$dispatchTo('support.ticket-modal','open-modal')"
                            class="mt-4 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                               px-4 py-2 rounded-lg shadow-sm transition-colors active:scale-95 cursor-pointer">
                        <i class="fas fa-plus text-xs"></i>
                        تیکت جدید
                    </button>
                </div>
            @endif
        </div>

    </div>
</div>
