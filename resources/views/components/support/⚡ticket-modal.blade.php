<?php

use App\Models\Ticket;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {

    public $showModal = false;
    public $ticketId = null;
    public $subject;
    public $message;
    public $ticketReplies = [];

    #[On('open-modal')]
    public function open(Ticket $ticket = null)
    {
        $this->showModal = true;

        if ($ticket && $ticket->exists) {
            $this->ticketId = $ticket->id;
            $this->subject = $ticket->subject;
            $this->message = $ticket->message;
            // به صورت آرایه ذخیره می‌کنیم تا لایووایر مشکلی در دیتابایندینگ نداشته باشد
            $this->ticketReplies = $ticket->replies()->get()->toArray();
        }
    }

    public function close()
    {
        $this->showModal = false;
        $this->reset();
    }

    public function save()
    {
        $this->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->close();
        $this->dispatch('refresh');
        $this->dispatch('flash', message: 'تیکت ارسال شد!');
    }
};
?>

<div
    wire:cloak
    wire:show="showModal"
    class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center backdrop-blur-sm transition-opacity"
    x-transition
    wire:click.self="close"
>
    <div
        class="bg-white w-full max-w-lg rounded-xl shadow-lg overflow-visible border border-slate-200
         relative font-sans transform transition-all max-h-[90vh] flex flex-col"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    >

        {{-- Header --}}
        <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-slate-200 shrink-0">
            <div>
                <h2 class="text-base font-bold tracking-tight text-slate-900">{{ $ticketId ? 'جزئیات تیکت شماره ' . $ticketId : 'تیکت جدید' }}</h2>
            </div>
            <button type="button" wire:click="close"
                    class="w-8 h-8 rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 flex items-center justify-center transition-colors cursor-pointer text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body (Scrollable if content is long) --}}
        <div class="overflow-y-auto p-6 space-y-5">
            <form id="ticket-form" wire:submit.prevent="save">
                <div class="space-y-1.5 mb-5">
                    <label class="text-xs font-medium text-slate-500 mr-2">عنوان تیکت</label>
                    <input type="text"
                           wire:model="subject"
                           {{ $ticketId ? 'disabled' : '' }}
                           class="w-full bg-white border border-slate-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200
                           rounded-lg px-4 py-3 outline-none transition text-sm font-medium placeholder-slate-400 disabled:opacity-70 disabled:bg-slate-100">
                    @error('subject') <span class="text-red-500 text-[10px] mr-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-slate-500 mr-2">متن تیکت</label>
                    <textarea
                        wire:model="message"
                        {{ $ticketId ? 'disabled' : '' }}
                        class="w-full bg-white border border-slate-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200
                           rounded-lg px-4 py-3 outline-none transition text-sm font-medium placeholder-slate-400 disabled:opacity-70 disabled:bg-slate-100 min-h-[100px]"></textarea>
                    @error('message') <span class="text-red-500 text-[10px] mr-2">{{ $message }}</span> @enderror
                </div>
            </form>

            {{-- نمایش پاسخ‌ها (فقط در صورتی که تیکت از قبل موجود باشد و پاسخی داشته باشد) --}}
            @if($ticketId && count($ticketReplies) > 0)
                <div class="space-y-3 pt-4 border-t border-slate-100 mt-4">
                    <label class="text-xs font-medium text-slate-500 mr-2">پاسخ‌های پشتیبانی</label>

                    @foreach($ticketReplies as $reply)
                        <div class="w-full bg-slate-50 border border-slate-200 text-slate-700
                               rounded-lg px-4 py-3 text-sm font-medium whitespace-pre-wrap">{{ $reply['message']}}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer Buttons --}}
        <div class="px-6 pb-6 pt-4 bg-white flex gap-3 border-t border-slate-200 shrink-0">
            {{-- اگر در حالت مشاهده تیکت هستیم، دکمه ذخیره رو مخفی می‌کنیم --}}
            @if(!$ticketId)
                <button type="submit"
                        form="ticket-form"
                        wire:loading.attr="disabled"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-70 text-white font-bold py-3 rounded-lg shadow-sm transition-colors active:scale-95 text-sm flex justify-center items-center gap-2">
                    <span wire:loading.remove>ذخیره تیکت</span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin"></i> در حال ارسال...
                    </span>
                </button>
            @endif

            <button type="button" wire:click="close"
                    class="{{ $ticketId ? 'w-full' : 'px-5' }} bg-white border border-slate-200 text-slate-600 font-medium rounded-lg hover:bg-slate-100 transition-colors text-sm py-3">
                {{ $ticketId ? 'بستن' : 'لغو' }}
            </button>
        </div>
    </div>
</div>
