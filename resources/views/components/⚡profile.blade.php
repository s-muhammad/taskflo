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

<div class="w-full p-4 md:p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 auto-rows-[minmax(160px,auto)] gap-4">

        {{-- کارت اصلی: اطلاعات پایه + امنیت --}}
        <form wire:submit.prevent="updateProfile"
              class="lg:col-span-2 lg:row-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5 md:p-6 flex flex-col">

            <h2 class="text-base md:text-lg font-bold text-slate-800 mb-5 flex items-center gap-3 pb-3 border-b border-slate-50">
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                    <i class="fas fa-user-cog"></i>
                </span>
                اطلاعات پایه
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600">نام کامل</label>
                    <div class="relative flex items-center">
                        <i class="fas fa-user absolute right-4 text-slate-400"></i>
                        <input type="text" wire:model="name"
                               class="w-full pr-12 pl-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none font-medium text-slate-700">
                    </div>
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600">شماره موبایل (غیرقابل تغییر)</label>
                    <div class="relative flex items-center">
                        <i class="fas fa-mobile-alt absolute right-4 text-slate-400 opacity-50"></i>
                        <input type="tel" disabled value="{{$phone}}" dir="ltr"
                               class="w-full pr-12 pl-4 py-3.5 bg-slate-100 border border-slate-200 rounded-xl text-left text-slate-500 font-medium cursor-not-allowed select-none">
                    </div>
                </div>
            </div>

            <h2 class="text-base md:text-lg font-bold text-slate-800 mt-8 pt-5 mb-5 flex items-center gap-3 pb-3 border-t border-b border-slate-100">
                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="fas fa-shield-alt"></i>
                </span>
                امنیت و رمز عبور
            </h2>
            <div class="bg-indigo-50/50 rounded-2xl p-5 border border-indigo-100/50">
                <p class="text-sm text-indigo-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    فقط در صورتی که قصد تغییر رمز عبور را دارید، فیلدهای زیر را پر کنید.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">رمز عبور جدید</label>
                        <input type="password" wire:model="password"
                               class="w-full px-4 py-3.5 bg-white border border-slate-200 focus:border-indigo-500 rounded-xl focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm placeholder:text-slate-400"
                               placeholder="••••••••">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">تکرار رمز عبور جدید</label>
                        <input type="password" wire:model="password_confirmation"
                               class="w-full px-4 py-3.5 bg-white border border-slate-200 focus:border-indigo-500 rounded-xl focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm placeholder:text-slate-400"
                               placeholder="••••••••">
                    </div>
                </div>
                @error('password') <span class="text-red-500 text-xs mt-2 block bg-red-50 p-2 rounded">{{ $message }}</span> @enderror
            </div>

            <div class="pt-5 mt-auto shrink-0 border-t border-slate-50 flex justify-end">
                <button type="submit" wire:loading.attr="disabled"
                        class="w-full md:w-auto flex items-center justify-center gap-3 bg-slate-900 hover:bg-indigo-700 text-white px-8 py-4 md:py-3 rounded-xl font-bold transition-all duration-300 shadow-md hover:shadow-indigo-600/20 active:scale-95 disabled:opacity-70 text-sm md:text-base">
                    <span wire:loading.remove class="flex items-center gap-2">
                         <i class="fas fa-save"></i>
                         ذخیره تغییرات
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                         در حال پردازش...
                    </span>
                </button>
            </div>
        </form>

        {{-- کارت آموزش --}}
        <a href="{{ route('help') }}" wire:navigate
           class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5
              flex flex-col items-center justify-center text-center gap-3 cursor-pointer">
            <span class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm
                     group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-105 transition-all">
                <i class="fas fa-book-open"></i>
            </span>
            <span>
                <span class="block font-bold text-sm text-slate-800">آموزش و راهنما</span>
                <span class="block text-xs text-slate-400 mt-1">راهنمای استفاده</span>
            </span>
        </a>

        {{-- کارت پشتیبانی --}}
        <a href="{{ route('support') }}" wire:navigate
           class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5
              flex flex-col items-center justify-center text-center gap-3 cursor-pointer">
            <span class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm
                     group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-105 transition-all">
                <i class="fas fa-shield"></i>
            </span>
            <span>
                <span class="block font-bold text-sm text-slate-800">پشتیبانی</span>
                <span class="block text-xs text-slate-400 mt-1">ارسال تیکت و پیگیری</span>
            </span>
        </a>

    </div>
</div>
