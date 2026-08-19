<div>
    <label class="block text-gray-700 text-sm font-semibold mb-2">دسته‌بندی</label>
    <div class="flex items-center gap-3">
        <select name="category_id" class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
            <option value="">بدون دسته‌بندی</option>
            @foreach($this->categories as $category)
                <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="button"
                wire:click="openModal"
                class="whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors duration-200 text-sm">
            <i class="fas fa-plus ml-1"></i> جدید
        </button>
    </div>
    @if($this->categories->isEmpty())
        <p class="text-xs text-gray-400 mt-1">هنوز دسته‌بندی‌ای وجود ندارد.</p>
    @endif

    @if($showModal)
        <div class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800">دسته‌بندی جدید</h3>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full p-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="px-6 py-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">نام دسته‌بندی</label>
                            <input type="text"
                                   wire:model="newCategoryName"
                                   wire:keydown.enter="saveCategory"
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm font-medium placeholder:text-slate-400"
                                   placeholder="مثلاً: آموزشی، اخبار، معرفی...">
                            @error('newCategoryName')
                                <span class="flex items-center gap-1 text-xs text-red-500 mt-2 font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                            <button wire:click="saveCategory"
                                    class="flex-1 inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="saveCategory">
                                    ذخیره
                                </span>
                                <span wire:loading wire:target="saveCategory" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                            </button>
                            <button wire:click="closeModal"
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
