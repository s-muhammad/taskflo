@extends('blog.layout')
@section('main')

<!-- Article Header -->
<header class="relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 right-0 w-[500px] h-[500px] bg-indigo-100/40 rounded-full blur-3xl"></div>
        <div class="absolute top-10 left-0 w-[300px] h-[300px] bg-purple-50/50 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6 relative z-10">
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
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 leading-tight">{{ $blog->title }}</h1>
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
        <div class="w-full h-64 md:h-[440px] rounded-2xl relative overflow-hidden shadow-2xl shadow-gray-300/30 border border-gray-100">
            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
        </div>
    </div>
</header>

<!-- Main Article Content -->
<main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">

    <!-- Article Body -->
    <article class="article-content mt-12 text-gray-700 leading-loose text-[16px]">
        {!! $blog->description !!}
    </article>

    <!-- Share Bar -->
    <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-gray-700">اشتراک‌گذاری:</span>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.single', $blog->id)) }}&text={{ urlencode($blog->title) }}" target="_blank" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-900 hover:text-white transition-all duration-300" title="اشتراک در توییتر">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.single', $blog->id)) }}" target="_blank" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition-all duration-300" title="اشتراک در لینکدین">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <button onclick="navigator.clipboard.writeText(window.location.href); this.classList.add('bg-green-500','text-white','border-green-500'); this.querySelector('span').textContent='کپی شد';" class="h-10 px-4 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-indigo-500 hover:text-white transition-all duration-300 text-sm font-bold gap-1.5 border border-transparent">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <span>کپی لینک</span>
            </button>
        </div>
    </div>

    <!-- Author Box -->
    <div class="mt-10 bg-gradient-to-br from-gray-50 to-indigo-50/30 border border-gray-100 p-6 md:p-8 rounded-2xl flex flex-col md:flex-row items-center md:items-start gap-5 shadow-sm">
        <div class="w-16 h-16 shrink-0 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/20">س‌م</div>
        <div class="text-center md:text-right">
            <p class="text-xs font-bold text-indigo-500 mb-1 tracking-wide">درباره نویسنده</p>
            <h4 class="text-lg font-bold text-gray-900 mb-2">سیدمحمد محمدی</h4>
            <p class="text-gray-500 text-sm leading-relaxed">مدیر سایت {{ setting('site_title') }}. علاقه‌مند به بررسی روش‌های جدید افزایش بهره‌وری و کمک به تیم‌ها برای رسیدن به اهدافشان در سریع‌ترین زمان ممکن.</p>
        </div>
    </div>

</main>

<!-- Related Articles -->
@if($related->count() > 0)
<section class="mt-8 py-16 bg-gradient-to-b from-gray-50 to-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-10 justify-center">
            <div class="w-1.5 h-8 rounded-full bg-gradient-to-b from-indigo-500 to-purple-600"></div>
            <h3 class="text-2xl font-black text-gray-900">مطالب پیشنهادی</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-7 max-w-4xl mx-auto">
            @foreach($related as $blog)
                <a href="{{ route('blog.single',$blog->id) }}">
                    <article class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-indigo-100 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-indigo-100/20 transition-all duration-500">
                        <div class="h-44 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs text-gray-400 font-medium">{{ \Morilog\Jalali\Jalalian::fromCarbon($blog->created_at)->format('%d %B %Y') }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="text-xs text-indigo-500 font-bold">{{ $blog->reading_time }} دقیقه</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors leading-relaxed line-clamp-2">{{ $blog->title }}</h4>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $blog->summary }}</p>
                            <span class="inline-flex items-center gap-2 text-indigo-600 font-bold text-sm group-hover:gap-3 transition-all duration-300">
                                مطالعه مقاله
                                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                        </div>
                    </article>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
