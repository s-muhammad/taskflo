<?php

use App\Models\Category;
use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

new class extends Component {
    public $showModal = false;
    public $id ;
    public $title;
    public $category_id;
    public $description;
    public $date;
    public $time;

    #[On('show')]
    public function show(Task $task)
    {
        $this->showModal = true;
        $this->title = $task->title;
        $this->id = $task->id;
        $this->category_id = $task->category_id;
        $this->description = $task->description;
        $this->date = Jalalian::fromDateTime($task->due_date)->format('Y/m/d');
        $this->time = $task->time;
    }

    #[Computed]
    public function category()
    {
        if (!$this->category_id) {
            return null;
        }
        return Category::find($this->category_id);
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function deleteTask($taskId)
    {
        Task::where('id', $taskId)->where('user_id', auth()->id())->delete();
        $this->close();
        $this->dispatch('refresh');
    }
};
?>

<div wire:cloak wire:show="showModal" wire:click.self="close"
     class="fixed inset-0 z-50 bg-slate-900/40 dark:bg-slate-950/60 flex items-center justify-center backdrop-blur-sm transition-opacity"
     x-transition
>
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-soft-lg overflow-hidden border border-slate-200 dark:border-slate-800 relative font-sans transform transition-all max-h-[90vh] flex flex-col"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    >
        {{-- Header --}}
        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex justify-between items-start border-b border-slate-200 dark:border-slate-700 shrink-0">
            <div class="min-w-0">
                <h2 class="text-base font-bold tracking-tight text-slate-900 dark:text-white truncate">{{ $this->title }}</h2>
                @if($this->category)
                    <span
                        class="inline-flex items-center gap-1.5 mt-1.5 text-[11px] font-bold px-2.5 py-0.5 rounded-full border"
                        style="background-color: {{ $this->category->color }}15; border-color: {{ $this->category->color }}40; color: {{ $this->category->color }}">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $this->category->color }}"></span>
                        {{ $this->category->name }}
                    </span>
                @endif
            </div>
            <button type="button" wire:click="close"
                    class="w-8 h-8 rounded-full text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition-colors cursor-pointer text-sm shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto p-6 space-y-5">
            {{-- Description --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mr-2 uppercase">توضیحات</label>
                <div
                    class="w-full min-h-[80px] bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-700 dark:text-slate-300 font-medium whitespace-pre-wrap {{ $this->description ? '' : 'text-slate-400' }}">
                    {{ $this->description ?: 'بدون توضیحات' }}
                </div>
            </div>

            {{-- Date & Time Row --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mr-2 uppercase">تاریخ</label>
                    <div
                        class="flex items-center gap-2 w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-700 dark:text-slate-300 font-medium">
                        <i class="fas fa-calendar-day text-xs text-slate-400"></i>
                        <span>{{ $this->date }}</span>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mr-2 uppercase">ساعت</label>
                    <div
                        class="flex items-center gap-2 w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-700 dark:text-slate-300 font-medium {{ $this->time ? '' : 'text-slate-400' }}">
                        <i class="far fa-clock text-xs text-slate-400"></i>
                        <span dir="ltr" class="font-mono">{{ $this->time ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Buttons --}}
        <div class="px-6 pb-6 pt-4 bg-white dark:bg-slate-900 flex gap-3 border-t border-slate-100 dark:border-slate-800 shrink-0">
            <a href="{{ route('task.form',['id'=>$this->id]) }}" wire:navigate
               class="flex-1 inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-soft transition-all active:scale-95 text-sm">
                <i class="fas fa-pen text-xs"></i>
                ویرایش
            </a>
            <button type="button"
                    wire:click="deleteTask({{ $this->id }})"
                    wire:confirm="آیا از حذف این تسک اطمینان دارید؟"
                    class="inline-flex justify-center items-center gap-2 px-5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 font-bold py-3 rounded-xl transition-all text-sm cursor-pointer">
                <i class="fas fa-trash-alt text-xs"></i>
                حذف
            </button>
        </div>
    </div>
</div>
