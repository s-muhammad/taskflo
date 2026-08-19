@extends('blog.layout')
@section('main')

<!-- Article Header -->
<header class="relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 right-0 w-[500px] h-[500px] bg-indigo-100/40 rounded-full blur-3xl"></div>
        <div class="absolute top-10 left-0 w-[300px] h-[300px] bg-purple-50/50 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6 relative z-10">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">صفحه اصلی</a>
            <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ url('/blog') }}" class="hover:text-indigo-600 transition-colors">وبلاگ</a>
            <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-gray-600 font-medium truncate max-w-[200px]">{{ $blog->title }}</span>
        </div>

        <!-- Title -->
        <div class="flex items-center gap-3 mb-6 flex-wrap">
            <h1 class="text-2xl md:text-4xl lg:text-3xl font-black text-gray-900 leading-tight">{{ $blog->title }}</h1>
            @if($blog->featured)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-full shadow-md shadow-indigo-600/20 shrink-0">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                پیشنهاد سردبیر
            </span>
            @endif
        </div>

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center gap-5 text-gray-500 text-sm mb-8">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-lg shadow-indigo-500/20">س‌م</div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">سیدمحمد محمدی</p>
                    <p class="text-xs text-gray-400">مدیر سایت</p>
                </div>
            </div>
            <span class="w-px h-5 bg-gray-200"></span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-medium">{{\Morilog\Jalali\Jalalian::fromCarbon($blog->created_at)->format('%d %B %Y')}}</span>
            </div>
            <span class="w-px h-5 bg-gray-200"></span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ $blog->reading_time }} دقیقه مطالعه</span>
            </div>
        </div>

        <!-- Cover Image -->
        <div class="w-full h-64 md:h-[400px] rounded-2xl relative overflow-hidden shadow-2xl shadow-gray-300/30 border border-gray-100">
            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
        </div>
    </div>
</header>

<!-- Content + Sidebar -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex flex-col lg:flex-row gap-10">

        <!-- Article (Left) -->
        <div class="flex-1 min-w-0">
            <article class="article-content mt-4 text-gray-700 leading-loose text-[16px]">
                {!! $blog->description !!}
            </article>

            <!-- Share Bar (mobile - below article) -->
            <div class="mt-10 pt-8 border-t border-gray-100 lg:hidden">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-gray-700">اشتراک‌گذاری:</span>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.single', $blog->id)) }}&text={{ urlencode($blog->title) }}" target="_blank" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-900 hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.single', $blog->id)) }}" target="_blank" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); this.classList.add('bg-green-500','text-white'); this.querySelector('span').textContent='کپی شد';" class="h-10 px-4 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-indigo-500 hover:text-white transition-all duration-300 text-sm font-bold gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span>کپی لینک</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Sidebar (Right) -->
        <aside class="w-full lg:w-[320px] shrink-0">
            <div class="lg:sticky lg:top-24 space-y-6">

                <!-- Share -->
                <div class="hidden lg:block bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        اشتراک‌گذاری
                    </h4>
                    <div class="flex items-center gap-2">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.single', $blog->id)) }}&text={{ urlencode($blog->title) }}" target="_blank" class="flex-1 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-gray-900 hover:text-white transition-all duration-300" title="توییتر">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.single', $blog->id)) }}" target="_blank" class="flex-1 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition-all duration-300" title="لینکدین">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); this.classList.add('bg-green-500','text-white'); this.querySelector('span').textContent='کپی شد';" class="flex-1 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-indigo-500 hover:text-white transition-all duration-300 text-xs font-bold gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span>کپی لینک</span>
                        </button>
                    </div>
                </div>

                <!-- Author Box -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        درباره نویسنده
                    </h4>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 shrink-0 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/20">س‌م</div>
                        <div>
                            <h5 class="font-bold text-gray-900 text-sm">سیدمحمد محمدی</h5>
                            <p class="text-xs text-gray-400 mt-0.5">مدیر سایت</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs leading-relaxed mt-4">علاقه‌مند به بررسی روش‌های جدید افزایش بهره‌وری و کمک به تیم‌ها برای رسیدن به اهدافشان.</p>
                </div>

                <!-- Related Articles -->
                @if($related->count() > 0)
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            مطالب پیشنهادی
                        </h4>
                        <div class="space-y-4">
                            @foreach($related as $rel)
                                <a href="{{ route('blog.single', $rel->id) }}" class="flex gap-3 group">
                                    <div class="w-20 h-16 shrink-0 rounded-xl overflow-hidden bg-gray-100">
                                        <img src="{{ asset($rel->image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-relaxed">{{ $rel->title }}</h5>
                                        <span class="text-xs text-gray-400 mt-1 block">{{ \Morilog\Jalali\Jalalian::fromCarbon($rel->created_at)->format('%d %B %Y') }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </aside>
    </div>
</main>

@endsection
