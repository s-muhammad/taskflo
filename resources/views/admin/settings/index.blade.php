@extends('admin.layout.app')
@section('main')
    @php
        $translation_keys = [
            'site_title' => 'عنوان سایت',
            'site_description' => 'توضیحات سایت',
            'site_logo' => 'لوگوی سایت',
            'site_favicon' => 'فاوآیکون سایت',
            'site_email' => 'ایمیل سایت',
            'site_phone' => 'شماره تلفن',
            'site_address' => 'آدرس سایت',
            'site_active' => 'وضعیت سایت',
            'maintenance_msg' => 'پیغام خطا',
            'seo_meta_keywords' => 'کلمات کلیدی سئو',
            'seo_meta_author' => 'نویسنده سئو',
            'seo_og_image' => 'تصویر Open Graph',
            'social_github'=>'لینک گیت هاب',
            'social_linkedin'=>'لینک لینکدین',
            'social_instagram'=>'لینک اینستاگرام',
            'social_youtube'=>'لینک یوتیوب',
        ];

        $translation_groups = [
            'general' => ['name' => 'تنظیمات عمومی', 'icon' => 'fas fa-cogs', 'color' => 'blue'],
            'social' => ['name' => 'شبکه‌های اجتماعی', 'icon' => 'fas fa-share-alt', 'color' => 'purple'],
            'seo' => ['name' => 'تنظیمات سئو', 'icon' => 'fas fa-chart-line', 'color' => 'green'],
        ];

        // تعیین تب فعال اولیه (مثلاً 'general')
        $active_tab = request()->get('tab', 'general');
    @endphp

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 dark:bg-slate-950 p-6 md:p-10">

        {{-- عنوان صفحه --}}
        <div class="flex items-center mb-8 justify-between">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                مدیریت تنظیمات سایت
            </h2>
        </div>

        {{-- اعلان موفقیت --}}
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 p-4 rounded-2xl mb-8 shadow-soft">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i>
                    <p class="font-semibold text-sm">تنظیمات با موفقیت ذخیره شد.</p>
                </div>
            </div>
        @endif

        {{-- کانتینر اصلی فرم --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-soft border border-slate-200 dark:border-slate-800 p-0 overflow-hidden">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('post')

                {{-- ناوبری تب‌ها --}}
                <div class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                    <nav class="flex space-x-1 space-x-reverse px-6 pt-4" aria-label="Tabs">
                        @foreach($translation_groups as $key => $group_data)
                            @php
                                $isActive = $key === $active_tab;
                                $baseClasses = 'px-5 py-3 font-bold text-sm rounded-t-2xl transition-all duration-200 flex items-center gap-2';
                                $activeClasses = 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-900 border-t border-x border-slate-200 dark:border-slate-800';
                                $inactiveClasses = 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/50 dark:hover:bg-slate-800/50';
                            @endphp
                            <a href="?tab={{ $key }}"
                               class="{{ $baseClasses }} {{ $isActive ? $activeClasses : $inactiveClasses }}">
                                <i class="{{ $group_data['icon'] }}"></i>
                                {{ $group_data['name'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                {{-- محتوای تب‌ها --}}
                <div class="p-8">
                    @foreach($settings as $group => $items)
                        <div id="tab-{{ $group }}" class="{{ $group === $active_tab ? 'block' : 'hidden' }} space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($items as $setting)
                                    @php
                                        $label = $translation_keys[$setting->key] ?? $setting->key;
                                    @endphp
                                    <div class="{{ $setting->type === 'textarea' ? 'md:col-span-2' : '' }}">
                                        <label for="{{ $setting->key }}" class="block text-slate-700 dark:text-slate-300 text-sm font-medium mb-2">{{ $label }}</label>

                                        @if($setting->type === 'text')
                                            <input type="text" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition duration-300">

                                        @elseif($setting->type === 'image')
                                            <div class="flex items-center gap-4">
                                                @if($setting->value)
                                                    <img src="{{ asset($setting->value) }}" alt="{{ $label }}" class="w-16 h-16 object-contain rounded-xl border border-slate-200 dark:border-slate-700 p-1 bg-white">
                                                @else
                                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-400 border border-dashed border-slate-300">
                                                        <i class="fas fa-image text-xl"></i>
                                                    </div>
                                                @endif
                                                <input type="file" id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 cursor-pointer">
                                            </div>

                                        @elseif($setting->type === 'textarea')
                                            <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" rows="4"
                                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition duration-300">{{ $setting->value }}</textarea>

                                        @elseif($setting->type === 'boolean')
                                            <div class="relative">
                                                <select id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                        class="block w-full px-4 py-3 appearance-none rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition duration-300">
                                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>غیرفعال</option>
                                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>فعال</option>
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- دکمه ذخیره --}}
                <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end rounded-b-3xl">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8
                            rounded-xl shadow-soft transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-save"></i> ذخیره کلی تنظیمات
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
