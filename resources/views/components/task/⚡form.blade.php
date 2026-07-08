<?php

use App\Models\Category;
use App\Models\Task;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use App\Traits\HasJalaliCalendar;

new class extends Component {
    use HasJalaliCalendar;

    public $taskId;
    public $title;
    public $description;
    public $routine = 'none';
    public $time;
    public $due_date;
    public $reminder = false;
    public $showDatePicker = false;
    public $category_id = null;
    public $showCategoryPicker = false;
    public $returnUrl;

    public function toggleCategoryPicker()
    {
        $this->showCategoryPicker = !$this->showCategoryPicker;
    }

    public function selectCategory($id)
    {
        $this->category_id = $id;
        $this->showCategoryPicker = false;
    }

    public function mount()
    {
        $this->returnUrl = url()->previous(route('task'));

        $date = request()->query('date');
        $id = request()->query('id');

        if ($id) {
            $task = Task::find($id);
            if ($task) {
                $this->taskId = $task->id;
                $this->title = $task->title;
                $this->description = $task->description;
                $this->routine = $task->routine ?? 'none';
                $this->time = $task->time;
                $this->due_date = Jalalian::fromCarbon($task->due_date)->format('Y/m/d');
                $this->reminder = $task->reminder;
                $this->category_id = $task->category_id;
            }
        } elseif ($date) {
            $this->due_date = $date;
        } else {
            $this->due_date = Jalalian::now()->format('Y/m/d');
        }
    }

    public function toggleDatePicker()
    {
        $this->showDatePicker = !$this->showDatePicker;
        if ($this->showDatePicker && $this->due_date) {
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

    #[Computed]
    public function selectedCategoryName()
    {
        $category = $this->selectedCategory;
        return $category ? $category->name : null;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where('user_id', auth()->id()),
            ],
        ]);

        $taskData = [
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description ?? null,
            'time' => $this->time,
            'reminder' => $this->reminder ?? false,
        ];

        // اگر حالت ویرایش است، تسک قبلی را حذف می‌کنیم و دوباره می‌سازیم (برای روتین‌ها)
        if ($this->taskId) {
            $oldTask = Task::find($this->taskId);
            if ($oldTask) {
                $oldTask->delete();
            }
        }

        match ($this->routine) {
            'none'  => Task::create(array_merge($taskData, ['due_date' => $this->due_date, 'routine' => 'none'])),
            'daily' => $this->createRoutineTasks($taskData, 'daily'),
            'even'  => $this->createRoutineTasks($taskData, 'even', [0, 2, 4]),
            'odd'   => $this->createRoutineTasks($taskData, 'odd', [1, 3, 5]),
            default => null,
        };

        session()->flash('message', $this->taskId ? 'تسک با موفقیت بروزرسانی شد!' : 'تسک جدید با موفقیت اضافه شد!');
        return redirect($this->returnUrl);
    }

    private function createRoutineTasks(array $taskData, string $routine, ?array $allowedDaysOfWeek = null): void
    {
        try {
            $startDate = Jalalian::fromFormat('Y/m/d', $this->due_date);
            $daysInMonth = $startDate->getMonthDays();

            for ($day = $startDate->getDay(); $day <= $daysInMonth; $day++) {
                $dateString = sprintf('%04d/%02d/%02d', $startDate->getYear(), $startDate->getMonth(), $day);

                if ($allowedDaysOfWeek !== null) {
                    $dayOfWeek = Jalalian::fromFormat('Y/m/d', $dateString)->getDayOfWeek();
                    if (!in_array($dayOfWeek, $allowedDaysOfWeek)) continue;
                }

                Task::create(array_merge($taskData, ['due_date' => $dateString, 'routine' => $routine]));
            }
        } catch (\Exception $e) {
            Task::create(array_merge($taskData, ['due_date' => $this->due_date, 'routine' => $routine]));
        }
    }
};
?>

<div class="flex-1 overflow-y-auto p-6 bg-[#f8fafc]">
    <div class="max-w-6xl mx-auto">
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- هدر صفحه: بردکرامب + دکمه‌ها -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <!-- بردکرامب -->
                <nav class="text-sm text-gray-500">
                    <ol class="flex items-center gap-2">
                        <li>
                            <a href="{{ route('dashboard') }}" class="hover:text-gray-800 transition">
                                داشبورد
                            </a>
                        </li>
                        <li class="text-gray-400">/</li>
                        <li>
                            <a href="{{ route('task') }}" class="hover:text-gray-800 transition">
                                تسک‌ها
                            </a>
                        </li>
                        <li class="text-gray-400">/</li>
                        <li class="font-medium text-gray-800">
                            {{ $taskId ? 'ویرایش تسک' : 'ساخت تسک جدید' }}
                        </li>
                    </ol>
                </nav>

                <!-- نوار کنترل فرم -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('task') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        انصراف
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-[#4f46e5] hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm shadow-indigo-500/30 transition-all">
                        {{ $taskId ? 'بروزرسانی تسک' : 'ذخیره تسک' }}
                    </button>
                </div>
            </div>

            <!-- کارت اصلی -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.02)]">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- ستون سمت راست: عنوان، توضیحات و روتین -->
                    <div class="lg:col-span-8 space-y-5">
                        <!-- عنوان تسک -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 text-right">عنوان تسک</label>
                            <input type="text" placeholder="مثلاً: طراحی پروتوتایپ صفحه لاگین" wire:model="title"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all text-sm text-gray-700 bg-gray-50/50">
                            @error('title') <span
                                class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <!-- توضیحات تکمیلی -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 text-right">توضیحات تکمیلی</label>
                            <textarea rows="4" placeholder="جزئیات مربوط به این تسک را اینجا بنویسید..."
                                      wire:model="description"
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all text-sm text-gray-700 resize-none bg-gray-50/50"></textarea>
                        </div>
                    </div>
                    <!-- ستون سمت چپ: تاریخ، ساعت، دسته‌بندی و یادآوری -->
                    <div class="lg:col-span-4 space-y-4">
                        <!-- ردیف اول: تاریخ و ساعت -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 text-right">📅 تاریخ</label>
                            <div class="relative">
                                <div class="relative cursor-pointer" wire:click="toggleDatePicker">
                                    <div
                                        class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-calendar-day text-xs"></i>
                                    </div>
                                    <input type="text" readonly value="{{ $due_date }}" dir="ltr"
                                           class="w-full bg-gray-50 border border-gray-200 hover:border-indigo-300 focus:border-indigo-500 cursor-pointer
                                                      rounded-lg pr-7 pl-2 py-2 text-sm text-gray-700 font-medium outline-none transition shadow-sm">
                                </div>

                                @if($showDatePicker)
                                    <div
                                        class="absolute z-50 right-0 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-4"
                                        style="top: 100%; margin-top: 0.5rem;"
                                        x-transition.origin.top.right
                                        @click.outside="$wire.set('showDatePicker', false)">
                                        <div class="flex items-center justify-between mb-4">
                                            <button type="button" wire:click="prevMonth"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-indigo-600 transition">
                                                <i class="fas fa-chevron-right text-xs"></i>
                                            </button>
                                            <span
                                                class="text-sm font-bold text-gray-700">{{ $this->monthName }} {{ $this->calendarYear }}</span>
                                            <button type="button" wire:click="nextMonth"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-indigo-600 transition">
                                                <i class="fas fa-chevron-left text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-7 mb-2 text-center">
                                            @foreach(['ش','ی','د','س','چ','پ','ج'] as $dayName)
                                                <span class="text-[10px] text-gray-400 font-bold">{{ $dayName }}</span>
                                            @endforeach
                                        </div>
                                        <div class="grid grid-cols-7 gap-1">
                                            @foreach($this->calendarDays as $day)
                                                @if(is_null($day))
                                                    <span></span>
                                                @else
                                                    @php
                                                        $isToday = $day == Morilog\Jalali\Jalalian::now()->getDay() &&
                                                                   $this->calendarMonth == Morilog\Jalali\Jalalian::now()->getMonth() &&
                                                                   $this->calendarYear == Morilog\Jalali\Jalalian::now()->getYear();
                                                        $isSelected = $due_date == (new Morilog\Jalali\Jalalian($this->calendarYear, $this->calendarMonth, $day))->format('Y/m/d');
                                                    @endphp
                                                    <button type="button"
                                                            wire:click="selectDate({{ $this->calendarYear }}, {{ $this->calendarMonth }}, {{ $day }})"
                                                            class="w-full aspect-square flex items-center justify-center rounded-lg text-xs font-medium transition duration-200
                                                                {{ $isSelected ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : ($isToday ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'text-gray-600 hover:bg-gray-100') }}">
                                                        {{ $day }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 text-right">⏰ ساعت</label>
                            <div class="relative">
                                <input type="time" placeholder="--:--" dir="ltr" wire:model="time"
                                       class="w-full bg-gray-50 border border-gray-200 hover:border-indigo-300 focus:border-indigo-500 focus:bg-white
                                                  rounded-lg px-2 py-2 text-sm text-gray-700 font-medium outline-none transition shadow-sm ltr-input">
                                <div
                                    class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-gray-400">
                                    <i class="far fa-clock text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ردیف دوم: دسته‌بندی -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 text-right">🏷️ دسته‌بندی</label>
                            <div class="relative">
                                <div class="relative cursor-pointer" wire:click="toggleCategoryPicker">
                                    <div
                                        class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-tags text-xs"></i>
                                    </div>
                                    <input type="text" readonly
                                           value="{{ $this->selectedCategoryName ?? 'انتخاب دسته‌بندی' }}"
                                           class="w-full bg-gray-50 border border-gray-200 hover:border-indigo-300 focus:border-indigo-500 cursor-pointer
                                                  rounded-lg pr-7 pl-2 py-2 text-xs text-gray-700 font-medium outline-none transition shadow-sm">
                                </div>

                                @if($showCategoryPicker)
                                    <div
                                        class="absolute z-50 left-0 right-0 bg-white rounded-xl shadow-lg border border-gray-100 p-1.5"
                                        style="top: 100%; margin-top: 0.25rem; max-height: 12rem; overflow-y: auto;"
                                        x-transition.origin.top
                                        @click.outside="$wire.set('showCategoryPicker', false)">
                                        <div class="flex flex-col">
                                            <button type="button"
                                                    wire:click="$dispatchTo('category-modal', 'open-modal')"
                                                    class="px-3 py-1.5 rounded-md text-right text-xs transition-all duration-150 text-indigo-600 hover:bg-indigo-50 font-medium">
                                                + افزودن دسته بندی جدید
                                            </button>
                                            @foreach($this->categories as $cat)
                                                <button type="button"
                                                        wire:click="selectCategory({{ $cat->id }})"
                                                        class="px-3 py-1.5 rounded-md text-right text-xs transition-all duration-150
                                                   {{ $category_id == $cat->id ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                                                    {{ $cat->name }}
                                                </button>
                                            @endforeach
                                            @if($this->categories->isEmpty())
                                                <div class="text-center py-3 text-xs text-gray-400">
                                                    هیچ دسته‌بندی وجود ندارد
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-4">
                    <div class="lg:col-span-8 space-y-5">
                        @if(!$taskId)
                            <label class="block text-sm font-bold text-gray-700 mb-3 text-right">روتین تکرار</label>
                            <div class="grid grid-cols-4 gap-2">
                                <label class="cursor-pointer relative">
                                    <input type="radio" wire:model="routine" value="none" class="peer sr-only">
                                    <div
                                        class="w-full py-2 px-2 text-center text-xs font-medium text-gray-600 bg-gray-50/50 border border-gray-200 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-all">
                                        بدون تکرار
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" wire:model="routine" value="daily" class="peer sr-only">
                                    <div
                                        class="w-full py-2 px-2 text-center text-xs font-medium text-gray-600 bg-gray-50/50 border border-gray-200 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-all">
                                        روزانه
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" wire:model="routine" value="even" class="peer sr-only">
                                    <div
                                        class="w-full py-2 px-2 text-center text-xs font-medium text-gray-600 bg-gray-50/50 border border-gray-200 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-all">
                                        روزهای زوج
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" wire:model="routine" value="odd" class="peer sr-only">
                                    <div
                                        class="w-full py-2 px-2 text-center text-xs font-medium text-gray-600 bg-gray-50/50 border border-gray-200 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-all">
                                        روزهای فرد
                                    </div>
                                </label>
                            </div>
                        @endif
                    </div>
                    <div class="lg:col-span-4 space-y-5">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 text-right">🔔 اعلان</label>
                        <label
                            class="flex items-center justify-between bg-indigo-50/50 border border-indigo-100 p-2.5 rounded-lg cursor-pointer hover:bg-indigo-50 transition">
                            <span class="text-sm font-medium text-indigo-800">فعال‌سازی اعلان</span>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" class="sr-only peer"
                                       wire:model="reminder">
                                <div
                                    class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-indigo-500 transition-colors"></div>
                                <div
                                    class="absolute right-[2px] top-[2px] bg-white w-4 h-4 rounded-full transition-transform peer-checked:-translate-x-5 shadow-sm"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
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
        text-align: right;
    }

    .z-50 {
        z-index: 50;
    }
</style>
