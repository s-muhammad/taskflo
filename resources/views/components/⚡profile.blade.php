<?php

use Livewire\Component;
use Illuminate\Support\Facades\Hash;

new class extends Component {

    public $name;
    public $phone;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->phone = auth()->user()->phone;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|min:3',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'لطفا نام خود را وارد کنید.',
            'password.confirmed' => 'رمز عبور با تکرار آن مطابقت ندارد.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
        ]);

        $data = ['name' => $this->name];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        auth()->user()->update($data);

        $this->reset(['password', 'password_confirmation']);

        session()->flash('message', 'تغییرات با موفقیت ذخیره شد .');

        return redirect(request()->header('Referer'));
    }
};
?>

<div class="w-full p-3 md:p-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 auto-rows-[minmax(130px,auto)] gap-4">

        {{-- کارت اصلی: اطلاعات پایه + امنیت --}}
        <form wire:submit.prevent="updateProfile"
              class="lg:col-span-2 lg:row-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-soft p-5 md:p-6 flex flex-col">

            <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                <span class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <i class="fas fa-user-cog"></i>
                </span>
                اطلاعات کاربری
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">نام کامل</label>
                    <input type="text" wire:model="name"
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm font-medium text-slate-900 dark:text-white">
                    @error('name') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">شماره موبایل</label>
                    <input type="tel" disabled value="{{$phone}}" dir="ltr"
                           class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-500 dark:text-slate-400 font-medium cursor-not-allowed">
                </div>
            </div>

            <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6 pt-4 mb-4 flex items-center gap-3 pb-3 border-t border-b border-slate-100 dark:border-slate-800">
                <span class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <i class="fas fa-shield-alt"></i>
                </span>
                امنیت و رمز عبور
            </h2>
            
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-indigo-600"></i>
                    فقط در صورت قصد تغییر رمز عبور، فیلدها را پر کنید.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">رمز عبور جدید</label>
                        <input type="password" wire:model="password"
                               class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 focus:border-indigo-500 rounded-xl focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm text-slate-900 dark:text-white"
                               placeholder="••••••••">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">تکرار رمز عبور</label>
                        <input type="password" wire:model="password_confirmation"
                               class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 focus:border-indigo-500 rounded-xl focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm text-slate-900 dark:text-white"
                               placeholder="••••••••">
                    </div>
                </div>
                @error('password') <span class="text-rose-600 text-xs mt-3 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-5 mt-auto shrink-0 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="submit" wire:loading.attr="disabled"
                        class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-soft active:scale-95 disabled:opacity-70 text-sm">
                    <span wire:loading.remove>ذخیره تغییرات</span>
                    <span wire:loading>در حال پردازش...</span>
                </button>
            </div>
        </form>

        {{-- کارت‌های دسترسی سریع --}}
        <a href="{{ route('help') }}" wire:navigate
           class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-soft p-4
              flex flex-col items-center justify-center text-center gap-2 cursor-pointer hover:border-indigo-300 dark:hover:border-indigo-700 transition-all">
            <span class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shadow-soft">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="font-bold text-sm text-slate-900 dark:text-white">آموزش و راهنما</span>
        </a>

        <a href="{{ route('support') }}" wire:navigate
           class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-soft p-4
              flex flex-col items-center justify-center text-center gap-2 cursor-pointer hover:border-indigo-300 dark:hover:border-indigo-700 transition-all">
            <span class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shadow-soft">
                <i class="fas fa-shield"></i>
            </span>
            <span class="font-bold text-sm text-slate-900 dark:text-white">پشتیبانی</span>
        </a>

    </div>
</div>
