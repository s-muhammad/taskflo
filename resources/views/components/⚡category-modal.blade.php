<?php

use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $categoryId = null; // برای نگهداری شناسه در حالت ویرایش
    public $newCategoryName = '';
    public $newCategoryColor = 'indigo';
    public $categoryModal = false;

    public $colorsMap = [
        'slate'  => '#64748b',
        'red'    => '#ef4444',
        'orange' => '#f97316',
        'yellow' => '#eab308',
        'green'  => '#22c55e',
        'cyan'   => '#06b6d4',
        'indigo' => '#6366f1',
        'purple' => '#a855f7',
        'pink'   => '#ec4899',
    ];

    #[On('open-modal')]
    public function showCategoryModal(Category $category = null)
    {
        $this->resetValidation();

        // اگر دسته‌بندی پاس داده شده بود، یعنی حالت ویرایش است
        if ($category && $category->exists) {
            $this->categoryId = $category->id;
            $this->newCategoryName = $category->name;
            // اگر رنگ قبلی از پالت جدید خارج شده، نزدیک‌ترین رنگ فعال را انتخاب می‌کنیم
            $this->newCategoryColor = array_key_exists($category->color, $this->colorsMap)
                ? $category->color
                : 'green';
        } else {
            // در غیر این صورت حالت ایجاد است و فیلدها ریست می‌شوند
            $this->reset(['newCategoryName', 'newCategoryColor', 'categoryId']);
            $this->newCategoryColor = 'indigo'; // مقدار پیش‌فرض
        }

        $this->categoryModal = true;
    }

    public function closeCategoryModal()
    {
        $this->categoryModal = false;
        $this->resetValidation();
        $this->reset(['newCategoryName', 'newCategoryColor', 'categoryId']);
    }

    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:3|max:50',
            'newCategoryColor' => 'required|in:' . implode(',', array_keys($this->colorsMap)),
        ], [
            'newCategoryName.required' => 'نام دسته‌بندی الزامی است.',
            'newCategoryName.min' => 'نام باید حداقل ۳ حرف باشد.',
            'newCategoryColor.required' => 'لطفاً یک رنگ انتخاب کنید.',
            'newCategoryColor.in' => 'رنگ انتخاب شده معتبر نیست.',
        ]);

        if ($this->categoryId) {
            // آپدیت
            $category = Category::find($this->categoryId);
            // چک کردن اینکه کتگوری متعلق به کاربر فعلی باشد (برای امنیت)
            if ($category && $category->user_id == auth()->id()) {
                $category->update([
                    'name' => $this->newCategoryName,
                    'color' => $this->newCategoryColor,
                ]);
            }
        } else {
            // ایجاد
            Category::create([
                'user_id' => auth()->id(),
                'name' => $this->newCategoryName,
                'color' => $this->newCategoryColor,
            ]);
        }

        $this->closeCategoryModal();
        $this->dispatch('refresh'); // تغییر نام ایونت به saved که کلی‌تر باشد
    }
};
?>

<div>
    @if($categoryModal)
        <div class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div wire:click="closeCategoryModal"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">

                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800">
                                {{ $categoryId ? 'ویرایش دسته‌بندی' : 'دسته‌بندی جدید' }}
                            </h3>
                            <button wire:click="closeCategoryModal" class="text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full p-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-6">

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    نام دسته‌بندی
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           wire:model="newCategoryName"
                                           class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm font-medium placeholder:text-slate-400"
                                           placeholder="مثلاً: پروژه‌های شخصی...">
                                </div>
                                @error('newCategoryName')
                                <span class="flex items-center gap-1 text-xs text-red-500 mt-2 font-medium">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">
                                    انتخاب رنگ
                                </label>

                                <div class="grid grid-cols-5 gap-3 justify-items-center">
                                    @foreach($colorsMap as $name => $hex)
                                        <button type="button"
                                                wire:click="$set('newCategoryColor', '{{ $name }}')"
                                                class="relative w-9 h-9 rounded-full cursor-pointer transition-transform hover:scale-110 focus:outline-none"
                                                style="background-color: {{ $hex }};">

                                            @if($newCategoryColor === $name)
                                                <span class="absolute inset-0 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                                <span class="absolute -inset-1 rounded-full border-2 border-indigo-500/30"></span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="text-xs text-slate-400">رنگ انتخاب شده: <span class="font-bold text-slate-600">{{ $newCategoryColor }}</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                            {{-- تغییر متد به saveCategory --}}
                            <button wire:click="saveCategory"
                                    class="flex-1 inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove target="saveCategory">
                                    {{ $categoryId ? 'ثبت تغییرات' : 'ذخیره' }}
                                </span>
                                <span wire:loading target="saveCategory" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                            </button>

                            <button wire:click="closeCategoryModal"
                                    class="flex-1 inline-flex justify-center px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                                انصراف
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
