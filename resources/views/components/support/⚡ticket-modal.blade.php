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
    class="fixed inset-0 z-50 bg-slate-900/40 dark:bg-slate-950/60 flex items-center justify-center backdrop-blur-sm transition-opacity"
    x-transition
    wire:click.self="close"
>
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-soft-lg overflow-hidden border border-slate-200 dark:border-slate-800
         relative font-sans transform transition-all max-h-[90vh] flex flex-col"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    >

        {{-- Header --}}
        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex justify-between items-center border-b border-slate-200 dark:border-slate-700 shrink-0">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ $ticketId ? 'جزئیات تیکت شماره ' . $ticketId : 'تیکت جدید' }}</h2>
            <button type="button" wire:click="close"
                    class="w-8 h-8 rounded-full text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition-colors text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto p-6 space-y-6">
            <form id="ticket-form" wire:submit.prevent="save">
                <div class="space-y-1.5 mb-5">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mr-2 uppercase">عنوان تیکت</label>
                    <input type="text"
                           wire:model="subject"
                           {{ $ticketId ? 'disabled' : '' }}
                           class="w-full bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20
                           rounded-xl px-4 py-3 outline-none transition text-sm font-medium placeholder-slate-400 dark:placeholder-slate-600 text-slate-900 dark:text-white disabled:opacity-70 disabled:bg-slate-100 dark:disabled:bg-slate-900">
                    @error('subject') <span class="text-rose-600 text-[10px] mr-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mr-2 uppercase">متن تیکت</label>
                    <textarea
                        wire:model="message"
                        {{ $ticketId ? 'disabled' : '' }}
                        class="w-full bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20
                           rounded-xl px-4 py-3 outline-none transition text-sm font-medium placeholder-slate-400 dark:placeholder-slate-600 text-slate-900 dark:text-white disabled:opacity-70 disabled:bg-slate-100 dark:disabled:bg-slate-900 min-h-[100px]"></textarea>
                    @error('message') <span class="text-rose-600 text-[10px] mr-2">{{ $message }}</span> @enderror
                </div>
            </form>

            @if($ticketId && count($ticketReplies) > 0)
                <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800 mt-4">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mr-2 uppercase">پاسخ‌های پشتیبانی</label>

                    @foreach($ticketReplies as $reply)
                        <div class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 text-slate-700 dark:text-slate-200
                               rounded-xl px-4 py-3 text-sm font-medium whitespace-pre-wrap">{{ $reply['message']}}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-6 pt-4 bg-white dark:bg-slate-900 flex gap-3 border-t border-slate-100 dark:border-slate-800 shrink-0">
            @if(!$ticketId)
                <button type="submit"
                        form="ticket-form"
                        wire:loading.attr="disabled"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-70 text-white font-bold py-3 rounded-xl shadow-soft transition-all active:scale-95 text-sm flex justify-center items-center gap-2">
                    <span wire:loading.remove>ذخیره تیکت</span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin"></i> در حال ارسال...
                    </span>
                </button>
            @endif

            <button type="button" wire:click="close"
                    class="{{ $ticketId ? 'w-full' : 'px-5' }} bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm py-3">
                {{ $ticketId ? 'بستن' : 'لغو' }}
            </button>
        </div>
    </div>
</div>
