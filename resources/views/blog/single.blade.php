@extends('blog.layout')
@section('main')
<!-- Article Header & Cover -->
<header class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4 text-center relative z-10">
    <!-- Breadcrumb / Category -->
{{--    <div class="mb-6">--}}
{{--        <a href="/blog" class="text-indigo-600 font-bold text-sm bg-indigo-50 px-4 py-1.5 rounded-full hover:bg-indigo-100 transition-colors">مدیریت زمان</a>--}}
{{--    </div>--}}

    <!-- Title -->
    <h3 class="text-3xl md:text-5xl font-black text-slate-900 mb-4 leading-tight">{{ $blog->title }}</h3>

    <!-- Meta Info -->
    <div class="flex flex-wrap items-center justify-center gap-6 text-slate-500 text-sm mb-6">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs">س م</div>
            <span class="font-medium text-slate-700">سیدمحمد محمدی</span>
        </div>
        <div class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>{{\Morilog\Jalali\Jalalian::fromCarbon($blog->created_at)->format('%d %B %Y')}}</span>
        </div>
        <div class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>۸ دقیقه مطالعه</span>
        </div>
    </div>

    <!-- Featured Gradient Image -->
    <div class="w-full h-64 md:h-[440px] bg-gradient-to-tr from-emerald-400 to-teal-500 rounded-[2rem] relative overflow-hidden shadow-2xl shadow-emerald-200/50">
        <img src="{{ asset($blog->image) }}" alt="" class="w-full h-full object-cover">
    </div>
</header>

<!-- Main Article Content -->
<main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 relative z-10">

    <article class="article-content">
        {!! $blog->description !!}
    </article>

    <!-- Tags & Share -->
{{--    <div class="mt-12 pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-6">--}}
{{--        <div class="flex flex-wrap gap-2">--}}
{{--            <span class="text-sm font-bold text-slate-900 ml-2">برچسب‌ها:</span>--}}
{{--            <a href="#" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-sm transition-colors">بهره‌وری</a>--}}
{{--            <a href="#" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-sm transition-colors">تمرکز</a>--}}
{{--            <a href="#" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-sm transition-colors">تسک پلنر</a>--}}
{{--        </div>--}}
{{--        <div class="flex gap-2">--}}
{{--            <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-indigo-600 hover:text-white transition-colors" title="اشتراک در لینکدین">--}}
{{--                in--}}
{{--            </button>--}}
{{--            <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-blue-400 hover:text-white transition-colors" title="اشتراک در توییتر">--}}
{{--                X--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!-- Author Box -->
{{--    <div class="mt-12 bg-white border border-slate-100 p-6 md:p-8 rounded-3xl flex flex-col md:flex-row items-center md:items-start gap-6 shadow-xl shadow-slate-200/30">--}}
{{--        <div class="w-20 h-20 shrink-0 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-2xl">م‌ر</div>--}}
{{--        <div class="text-center md:text-right">--}}
{{--            <h4 class="text-xl font-bold text-slate-900 mb-2">درباره محمد رضایی</h4>--}}
{{--            <p class="text-slate-600 text-sm leading-relaxed">محمد مدیر محصول در تسک پلنر است. او عاشق بررسی روش‌های جدید افزایش بهره‌وری و کمک به تیم‌ها برای رسیدن به اهدافشان در سریع‌ترین زمان ممکن است. در اوقات فراغت به کوهنوردی می‌پردازد.</p>--}}
{{--        </div>--}}
{{--    </div>--}}

</main>

<!-- Related Articles -->
<section class="bg-slate-100 py-16 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-2xl font-black text-slate-900 mb-8 text-center">مطالب پیشنهادی</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        @foreach($related as $blog)
                <article class="bg-white rounded-3xl overflow-hidden shadow-lg shadow-slate-200/40 border border-slate-100 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300">
                    <div class="h-40 bg-gradient-to-bl from-blue-500 to-indigo-600 relative">
                        <img src="{{ asset($blog->image) }}" alt="" class="w-full h-full object-cover">
{{--                        <span class="absolute top-4 right-4 bg-white/90 backdrop-blur text-blue-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">بروزرسانی‌ها</span>--}}
                    </div>
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $blog->title }}</h4>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2">{{ $blog->summary }}</p>
                        <a href="{{ route('blog.single',$blog->id) }}" class="text-indigo-600 font-bold text-sm flex items-center gap-1 group/link">
                            مطالعه مقاله
                            <svg class="w-4 h-4 transform group-hover/link:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                    </div>
                </article>
        @endforeach
        </div>
    </div>
</section>
@endsection
