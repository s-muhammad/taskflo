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

        <form wire:submit.prevent="updateProfile" class="contents">

            {{-- کارت اطلاعات پایه --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5 md:p-6">
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
                            <i class="fas fa-envelope absolute right-4 text-slate-400 opacity-50"></i>
                            <input type="email" disabled value="{{$phone}}"
                                   class="w-full pr-12 pl-4 py-3.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 font-medium cursor-not-allowed select-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- کارت ارتقا — عمودی و بلند (دو ردیف) --}}
            <div class="lg:col-span-1 lg:row-span-2 relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-900 text-white shadow-xl p-6 flex flex-col justify-between min-h-[320px] lg:min-h-0">

                <div class="absolute top-0 right-0 w-full h-full overflow-hidden pointer-events-none opacity-50">
                    <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-to-br from-indigo-500/30 to-purple-500/30 blur-3xl rounded-full mix-blend-overlay animate-pulse-slow"></div>
                    <i class="fas fa-rocket absolute top-10 left-10 text-white/5 text-8xl transform -rotate-12"></i>
                </div>

                <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-indigo-500/20 px-3 py-1.5 rounded-lg text-indigo-200 text-sm font-medium mb-4 border border-indigo-500/30">
                            <i class="fas fa-star text-yellow-400"></i> پیشنهاد ویژه
                        </div>
                        <h2 class="text-2xl font-black mb-3 tracking-tight leading-tight">
                            ارتقا به <span class="text-indigo-300">نسخه حرفه‌ای</span>
                        </h2>
                        <p class="text-indigo-100 text-sm leading-relaxed opacity-90 mb-5">
                            با حذف محدودیت‌ها، پتانسیل واقعی خود را آزاد کنید و بهره‌وری را به حداکثر برسانید.
                        </p>

                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-medium text-indigo-50 bg-white/5 p-2 rounded-lg">
                                <i class="fas fa-check-circle text-green-400"></i>
                                <span>تسک و پروژه‌های نامحدود</span>
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium text-indigo-50 bg-white/5 p-2 rounded-lg">
                                <i class="fas fa-check-circle text-green-400"></i>
                                <span>یادآور پیامکی و ایمیلی</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-md border border-white/10 group hover:bg-white/15 transition-all cursor-pointer">
                        <div class="flex items-end gap-2 mb-4">
                            <span class="text-3xl font-black text-white">۴۹,۰۰۰</span>
                            <span class="text-indigo-200 text-xs mb-1.5 opacity-80">تومان / ماهانه</span>
                        </div>
                        <button type="button" class="w-full py-3.5 bg-white text-slate-900 rounded-xl font-black text-base hover:bg-indigo-50 transition-all duration-300 shadow-xl flex items-center justify-center gap-2 active:scale-95">
                            ارتقا حساب کاربری <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- کارت امنیت و رمز عبور --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5 md:p-6 flex flex-col">
                <h2 class="text-base md:text-lg font-bold text-slate-800 mb-5 flex items-center gap-3 pb-3 border-b border-slate-50">
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
                        <div>
                            <input type="password" wire:model="password"
                                   class="w-full px-4 py-3.5 bg-white border border-slate-200 focus:border-indigo-500 rounded-xl focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm placeholder:text-slate-400"
                                   placeholder="رمز عبور جدید...">
                        </div>
                        <div>
                            <input type="password" wire:model="password_confirmation"
                                   class="w-full px-4 py-3.5 bg-white border border-slate-200 focus:border-indigo-500 rounded-xl focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none text-sm placeholder:text-slate-400"
                                   placeholder="تکرار رمز عبور جدید...">
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
            </div>
        </form>

        {{-- سه کارت کوچک دسترسی سریع --}}
        <a href="{{ route('reports') }}" wire:navigate
           class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-5
              flex flex-col items-center justify-center text-center gap-3 cursor-pointer">
            <span class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm
                     group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-105 transition-all">
                <i class="fas fa-chart-line"></i>
            </span>
            <span>
                <span class="block font-bold text-sm text-slate-800">نمودار عملکرد</span>
                <span class="block text-xs text-slate-400 mt-1">گزارش‌ها و آمار فعالیت</span>
            </span>
        </a>

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
