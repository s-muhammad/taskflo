<?php

use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $categoryId = null;
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

        if ($category && $category->exists) {
            $this->categoryId = $category->id;
            $this->newCategoryName = $category->name;
            $this->newCategoryColor = array_key_exists($category->color, $this->colorsMap)
                ? $category->color
                : 'green';
        } else {
            $this->reset(['newCategoryName', 'newCategoryColor', 'categoryId']);
            $this->newCategoryColor = 'indigo';
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
            $category = Category::find($this->categoryId);
            if ($category && $category->user_id == auth()->id()) {
                $category->update([
                    'name' => $this->newCategoryName,
                    'color' => $this->newCategoryColor,
                ]);
            }
        } else {
            Category::create([
                'user_id' => auth()->id(),
                'name' => $this->newCategoryName,
                'color' => $this->newCategoryColor,
            ]);
        }

        $this->closeCategoryModal();
        $this->dispatch('refresh');
    }
};
?>

<div>
    @if($categoryModal)
        <div class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div wire:click="closeCategoryModal"
                 class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-right shadow-soft-lg transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-800">

                        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                {{ $categoryId ? 'ویرایش دسته‌بندی' : 'دسته‌بندی جدید' }}
                            </h3>
                            <button wire:click="closeCategoryModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-2 rounded-xl transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-6">

                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase">
                                    نام دسته‌بندی
                                </label>
                                <input type="text"
                                       wire:model="newCategoryName"
                                       class="block w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm font-medium placeholder:text-slate-400"
                                       placeholder="مثلاً: پروژه‌های شخصی...">
                                @error('newCategoryName')
                                <span class="flex items-center gap-1 text-xs text-rose-600 mt-2 font-medium">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-3 uppercase">
                                    انتخاب رنگ
                                </label>

                                <div class="grid grid-cols-5 gap-3 justify-items-center">
                                    @foreach($colorsMap as $name => $hex)
                                        <button type="button"
                                                wire:click="$set('newCategoryColor', '{{ $name }}')"
                                                class="relative w-8 h-8 rounded-full cursor-pointer transition-transform hover:scale-110 focus:outline-none"
                                                style="background-color: {{ $hex }};">

                                            @if($newCategoryColor === $name)
                                                <span class="absolute inset-0 flex items-center justify-center">
                                                    <i class="fas fa-check text-white text-xs drop-shadow-md"></i>
                                                </span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 flex gap-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                            <button wire:click="saveCategory"
                                    class="flex-1 inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-soft transition-all active:scale-95">
                                <span wire:loading.remove target="saveCategory">
                                    {{ $categoryId ? 'ثبت تغییرات' : 'ذخیره' }}
                                </span>
                                <span wire:loading target="saveCategory" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                            </button>

                            <button wire:click="closeCategoryModal"
                                    class="flex-1 inline-flex justify-center px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                انصراف
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
