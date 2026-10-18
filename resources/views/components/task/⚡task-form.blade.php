<?php

use App\Models\Category;
use App\Models\Task;
use App\Traits\HasJalaliCalendar;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

new class extends Component {
    use HasJalaliCalendar;

    public $showModal = false;
    public $taskId = null;
    public $title;
    public $due_date;
    public $start_time; // اضافه شده
    public $end_time;   // اضافه شده
    public $routine = false;
    public $category_id = null;
    public $showDatePicker = false;

    #[On('open-modal')]
    public function open($date = null, Task $task = null)
    {
        $this->resetValidation();
        $this->reset(['title', 'category_id', 'routine', 'showDatePicker', 'taskId', 'start_time', 'end_time']);
        $this->showModal = true;

        if ($task && $task->exists) {
            $this->taskId = $task->id;
            $this->title = $task->title;
            $this->category_id = $task->category_id;
            // اگر در دیتابیس ستون‌ها nullable هستند، ممکنه نال باشه
            $this->start_time = $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : null;
            $this->end_time = $task->end_time ? \Carbon\Carbon::parse($task->end_time)->format('H:i') : null;

            try {
                $jDate = Jalalian::fromDateTime($task->due_date);
            } catch (\Exception $e) {
                $jDate = Jalalian::now();
            }

            $this->due_date = $jDate->format('Y/m/d');
            $this->initCalendar($this->due_date);

        } else {
            // --- حالت ایجاد جدید ---
            $this->due_date = $date ? $date : Jalalian::now()->format('Y/m/d');
            $this->initCalendar($this->due_date);
        }
    }

    public function toggleDatePicker()
    {
        $this->showDatePicker = !$this->showDatePicker;
        if($this->showDatePicker && $this->due_date) {
            $this->initCalendar($this->due_date);
        }
    }

    #[Computed]
    public function calendarDays()
    {
        $structure = $this->calendarStructure;
        if (!$structure) return [];

        $days = [];
        for ($i = 0; $i < $structure['start_padding']; $i++) $days[] = null;
        for ($i = 1; $i <= $structure['total_days']; $i++) $days[] = $i;

        return $days;
    }

    public function selectDate($y, $m, $d)
    {
        $this->due_date = (new Jalalian($y, $m, $d))->format('Y/m/d');
        $this->showDatePicker = false;
    }

    public function close()
    {
        $this->showModal = false;
        $this->showDatePicker = false;
        $this->reset(['taskId']);
    }

    public function selectCategory($id)
    {
        $this->category_id = $id;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'start_time' => 'nullable',
            'end_time' => 'nullable|after:start_time', // ولیدیشن: پایان باید بعد از شروع باشد
        ]);

        $taskData = [
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'title' => $this->title,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];

        if ($this->taskId) {
            // --- آپدیت ---
            $task = Task::find($this->taskId);
            if ($task && $task->user_id == auth()->id()) {
                $task->update(array_merge($taskData, ['due_date' => $this->due_date]));
            }
        } else {
            // --- ایجاد ---
            if (!$this->routine) {
                Task::create(array_merge($taskData, ['due_date' => $this->due_date]));
            } else {
                try {
                    $startDate = Jalalian::fromFormat('Y/m/d', $this->due_date);
                    $daysInMonth = $startDate->getMonthDays();
                    $currentDay = $startDate->getDay();

                    for ($day = $currentDay; $day <= $daysInMonth; $day++) {
                        $dateString = sprintf('%04d/%02d/%02d', $startDate->getYear(), $startDate->getMonth(), $day);
                        Task::create(array_merge($taskData, ['due_date' => $dateString]));
                    }
                } catch (\Exception $e) {
                    Task::create(array_merge($taskData, ['due_date' => $this->due_date]));
                }
            }
        }
        $this->close();
        $this->dispatch('refresh');
        $this->dispatch('flash', message: 'تسک جدید با موفقیت اضافه شد!');
    }

    #[Computed]
    public function categories()
    {
        return Category::where('user_id', auth()->id())->get();
    }

    #[Computed]
    public function selectedCategory()
    {
        if (!$this->category_id) return null;
        return $this->categories->firstWhere('id', $this->category_id);
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
        class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl shadow-indigo-200/50 overflow-visible border border-slate-100
         relative font-sans transform transition-all"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    >

        {{-- Header --}}
        <div class="bg-slate-800 px-6 py-5 text-white flex justify-between items-center rounded-t-[2rem]">
            <div>
                <h2 class="text-lg font-bold">{{ $title }}</h2>
                <p class="text-indigo-100 text-xs mt-0.5">
                    {{ $taskId ? 'ویرایش جزئیات تسک' : 'ثبت سریع تسک در تقویم' }}
                </p>
            </div>
            <button wire:click="close"
                    class="bg-white/20 hover:bg-white/30 w-8 h-8 rounded-full flex items-center justify-center transition cursor-pointer text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form wire:submit="save">
            <div class="p-6 space-y-5">

                {{-- Title Input --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 mr-2 uppercase">عنوان تسک</label>
                    <input type="text"
                           placeholder="مثلاً: ۳۰ صفحه مطالعه"
                           wire:model="title"
                           class="w-full bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white focus:ring-1 focus:ring-indigo-500
                           rounded-xl px-4 py-3 outline-none transition text-sm font-medium shadow-sm placeholder-slate-400">
                    @error('title') <span class="text-red-500 text-[10px] mr-2">{{ $message }}</span> @enderror
                </div>

                {{-- Category & Date Row --}}
                <div class="grid grid-cols-2 gap-3">
                    {{-- Category Select --}}
                    <div class="space-y-1.5 relative" x-data="{ open: false }">
                        <label class="text-[10px] font-bold text-slate-400 mr-2 uppercase">دسته‌بندی</label>
                        <div @click="open = !open" @click.outside="open = false"
                             class="w-full bg-slate-50 border border-slate-100 hover:border-indigo-300 cursor-pointer
                             rounded-xl px-3 py-3 flex items-center justify-between transition shadow-sm h-[46px]"
                             :class="open ? 'ring-1 ring-indigo-500 border-indigo-500 bg-white' : ''">

                            @if($this->selectedCategory)
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full shadow-sm ring-1 ring-white"
                                          style="background-color: {{ $this->selectedCategory->color }}"></span>
                                    <span class="text-sm font-bold text-slate-700">{{ $this->selectedCategory->name }}</span>
                                </div>
                            @else
                                <span class="text-sm text-slate-400 font-medium">انتخاب کنید...</span>
                            @endif

                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"
                               :class="open ? 'rotate-180 text-indigo-500' : ''"></i>
                        </div>

                        {{-- Dropdown Body --}}
                        <div x-show="open" style="display: none"
                             class="absolute z-50 top-full mt-1 w-full bg-white rounded-xl shadow-xl border border-slate-100 max-h-48 overflow-y-auto custom-scrollbar p-1">
                            <div wire:click="selectCategory(null); open = false"
                                 class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50 cursor-pointer transition group">
                                <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 transition">
                                    <i class="fas fa-ban text-[10px]"></i>
                                </div>
                                <span class="text-xs text-slate-500 group-hover:text-slate-700">بدون دسته‌بندی</span>
                            </div>
                            @foreach($this->categories as $category)
                                <div wire:click="selectCategory({{ $category->id }})" @click="open = false"
                                     class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-indigo-50 cursor-pointer transition group mt-0.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full shadow-sm ring-2 ring-white"
                                              style="background-color: {{ $category->color }}"></span>
                                        <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-700 transition">
                                            {{ $category->name }}
                                        </span>
                                    </div>
                                    @if($category_id == $category->id)
                                        <i class="fas fa-check text-indigo-600 text-xs"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Date Picker --}}
                    <div class="space-y-1.5 relative">
                        <label class="text-[10px] font-bold text-slate-400 mr-2 uppercase">تاریخ</label>
                        <div class="relative cursor-pointer" wire:click="toggleDatePicker">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-calendar-day text-xs"></i>
                            </div>
                            <input type="text" readonly value="{{$this->due_date}}"
                                   class="w-full bg-slate-50 border border-slate-100 hover:border-indigo-300 focus:border-indigo-500 cursor-pointer
                                   rounded-xl pr-9 pl-3 py-3 text-sm text-slate-700 font-bold outline-none transition shadow-sm placeholder-slate-400">
                        </div>

                        @if($showDatePicker)
                            <div class="absolute z-50 top-full mt-2 left-0 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 p-4"
                                 x-transition.origin.top.left
                                 @click.outside="$wire.set('showDatePicker', false)">
                                {{-- Calendar Header --}}
                                <div class="flex items-center justify-between mb-4">
                                    <button type="button" wire:click="prevMonth" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-indigo-600 transition">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </button>
                                    <span class="text-sm font-bold text-slate-700">{{ $this->monthName }} @fa($this->calendarYear)</span>
                                    <button type="button" wire:click="nextMonth" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-indigo-600 transition">
                                        <i class="fas fa-chevron-left text-xs"></i>
                                    </button>
                                </div>
                                {{-- Week Days --}}
                                <div class="grid grid-cols-7 mb-2 text-center">
                                    @foreach(['ش','ی','د','س','چ','پ','ج'] as $dayName)
                                        <span class="text-[10px] text-slate-400 font-bold">{{ $dayName }}</span>
                                    @endforeach
                                </div>
                                {{-- Days Grid --}}
                                <div class="grid grid-cols-7 gap-1">
                                    @foreach($this->calendarDays as $day)
                                        @if(is_null($day))
                                            <span></span> @else
                                            @php
                                                $isToday = $day == Morilog\Jalali\Jalalian::now()->getDay() &&
                                                           $this->calendarMonth == Morilog\Jalali\Jalalian::now()->getMonth() &&
                                                           $this->calendarYear == Morilog\Jalali\Jalalian::now()->getYear();
                                                $isSelected = $this->due_date == (new Morilog\Jalali\Jalalian($this->calendarYear, $this->calendarMonth, $day))->format('Y/m/d');
                                            @endphp
                                            <button type="button"
                                                    wire:click="selectDate({{ $this->calendarYear }}, {{ $this->calendarMonth }}, {{ $day }})"
                                                    class="w-full aspect-square flex items-center justify-center rounded-lg text-xs font-medium transition duration-200
                                                    {{ $isSelected ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : ($isToday ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'text-slate-600 hover:bg-slate-100') }}">
                                                @fa($day)
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Time Inputs Row (NEW) --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5 relative">
                        <label class="text-[10px] font-bold text-slate-400 mr-2 uppercase">شروع</label>
                        <div class="relative">
                            <input type="time" wire:model="start_time"
                                   class="w-full bg-slate-50 border border-slate-100 hover:border-indigo-300 focus:border-indigo-500 focus:bg-white
                                   rounded-xl px-3 py-3 text-sm text-slate-700 font-medium outline-none transition shadow-sm placeholder-slate-400 ltr-input">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <i class="far fa-clock text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 relative">
                        <label class="text-[10px] font-bold text-slate-400 mr-2 uppercase">پایان</label>
                        <div class="relative">
                            <input type="time" wire:model="end_time"
                                   class="w-full bg-slate-50 border border-slate-100 hover:border-indigo-300 focus:border-indigo-500 focus:bg-white
                                   rounded-xl px-3 py-3 text-sm text-slate-700 font-medium outline-none transition shadow-sm placeholder-slate-400 ltr-input">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-history text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @error('end_time') <span class="text-red-500 text-[10px] block mt-1">{{ $message }}</span> @enderror


                {{-- Routine Option --}}
                @if(!$taskId)
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-md shadow-indigo-200">
                                    <i class="fas fa-sync-alt text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-700 text-xs">تبدیل به روتین</h4>
                                    <p class="text-[10px] text-slate-500">تکرار تا پایان ماه</p>
                                </div>
                            </div>
                            <label class="flex items-center cursor-pointer relative">
                                <input type="checkbox" wire:model.live="routine" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        <div x-show="$wire.routine" x-transition class="text-[10px] text-indigo-600 font-medium bg-indigo-100/50 px-3 py-2 rounded-lg border border-indigo-100">
                            <i class="fas fa-info-circle ml-1"></i>
                            این تسک برای تمام روزهای باقی‌مانده این ماه ایجاد می‌شود.
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer Buttons --}}
            <div class="px-6 pb-6 bg-slate-50 flex gap-3 rounded-b-[2rem] pt-4 border-t border-slate-100">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="flex-1 bg-slate-800 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95 text-sm flex justify-center items-center gap-2">
                    <span wire:loading.remove>{{ $taskId ? 'ثبت تغییرات' : 'ذخیره تسک' }}</span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin"></i> در حال ذخیره...
                    </span>
                </button>
                <button type="button" wire:click="close"
                        class="px-5 bg-white border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-100 transition text-sm">
                    لغو
                </button>
            </div>
        </form>
    </div>

    <style>
        /* استایل خاص برای اینپوت‌های ساعت که آیکون ساعت مرورگر رو حذف میکنه یا تمیزش میکنه */
        input[type="time"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }
        .ltr-input {
            direction: ltr;
            text-align: right; /* ساعت سمت راست باشه اما جهت تایپ ال‌تی‌آر */
        }
    </style>
</div>
