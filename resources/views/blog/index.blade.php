@extends('blog.layout')
@section('main')

<!-- Hero Section -->
<section class="relative pt-10 pb-12 overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-indigo-100/60 rounded-full blur-3xl"></div>
        <div class="absolute top-20 -left-20 w-[400px] h-[400px] bg-blue-50/80 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/3 w-[300px] h-[300px] bg-purple-50/50 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Badge -->
{{--            <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-600 text-sm font-bold mb-6">--}}
{{--                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>--}}
{{--                مجله تخصصی--}}
{{--            </div>--}}

            <h1 class="text-2xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-5 leading-tight">
                <span class="bg-gradient-to-l from-indigo-600 via-purple-600 to-indigo-700 bg-clip-text text-transparent">مجله بهره‌وری</span>
            </h1>
            <p class="text-lg text-gray-500 leading-relaxed max-w-xl mx-auto">
                جدیدترین مقالات آموزشی، اخبار بروزرسانی‌ها و ترفندهای مدیریت زمان برای تیم‌های موفق.
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">

    <!-- Featured Post -->
    @if($blogs->where('featured', true)->count() > 0)
    @foreach($blogs->where('featured', true)->take(1) as $blog)
    <section class="mb-16">
        <a href="{{route('blog.single',$blog->id)}}" class="block group">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-gray-200/40 border border-gray-100 hover:border-indigo-100 hover:shadow-indigo-100/30 transition-all duration-500 flex flex-col md:flex-row">
                <!-- Image -->
                <div class="md:w-[55%] h-72 md:h-auto relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 z-10"></div>
                    <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Badge -->
                    <div class="absolute top-5 right-5 z-20 flex items-center gap-2">
                        <span class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-full shadow-lg shadow-indigo-600/30 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            پیشنهاد سردبیر
                        </span>
                    </div>
                </div>
                <!-- Content -->
                <div class="md:w-[45%] p-8 md:p-10 flex flex-col justify-center">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-gray-400 text-sm font-medium">{{\Morilog\Jalali\Jalalian::fromCarbon($blog->created_at)->format('%d %B %Y')}}</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span class="text-indigo-500 text-sm font-bold">{{ $blog->reading_time }} دقیقه مطالعه</span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 group-hover:text-indigo-600 transition-colors leading-snug">{{$blog->title}}</h2>
                    <p class="text-gray-500 mb-8 leading-relaxed line-clamp-3">{{$blog->summary}}</p>
                    <div class="mt-auto flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/20">س‌م</div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">سیدمحمد محمدی</p>
                            <p class="text-xs text-gray-400">مدیر سایت</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </section>
    @endforeach
    @endif

    <!-- Articles Grid -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-8 rounded-full bg-gradient-to-b from-indigo-500 to-purple-600"></div>
            <h2 class="text-2xl font-black text-gray-900">آخرین مقالات</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($blogs as $blog)
            <a href="{{route('blog.single',$blog->id)}}" class="inline-flex items-center gap-2 text-indigo-600 font-bold text-sm group/link hover:gap-3 transition-all duration-300">
                <article class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-indigo-100 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-indigo-100/20 transition-all duration-500">
                <!-- Image -->
                <div class="h-52 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <!-- Reading Time Badge -->
                    <div class="absolute bottom-3 left-3 z-20 flex items-center gap-2">
                        @if($blog->featured)
                        <span class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            ویژه
                        </span>
                        @endif
                        <span class="px-3 py-1.5 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-bold rounded-lg shadow-sm flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $blog->reading_time }} دقیقه
                        </span>
                    </div>
                </div>
                <!-- Content -->
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs text-gray-400 font-medium">{{\Morilog\Jalali\Jalalian::fromCarbon($blog->created_at)->format('%d %B %Y')}}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors leading-relaxed line-clamp-2">{{$blog->title}}</h3>
                    <p class="text-gray-500 text-sm mb-5 line-clamp-2 leading-relaxed">{{$blog->summary}}</p>
{{--                        مطالعه مقاله--}}
{{--                        <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>--}}
                </div>
            </article>
            </a>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($blogs->hasPages())
        <div class="mt-16 flex justify-center gap-2">
            @if(!$blogs->onFirstPage())
            <a href="{{ $blogs->previousPageUrl() }}" class="w-11 h-11 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all">
                <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            @endif
            @foreach($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                @if($page == $blogs->currentPage())
                    <span class="w-11 h-11 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/25 flex items-center justify-center">{{ $page }}</span>
                @else
                    <a href="{{$url}}" class="w-11 h-11 rounded-xl bg-white border border-gray-200 text-gray-600 font-bold hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all flex items-center justify-center">{{ $page }}</a>
                @endif
            @endforeach
            @if($blogs->hasMorePages())
            <a href="{{ $blogs->nextPageUrl() }}" class="w-11 h-11 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            @endif
        </div>
    @endif
</main>
@endsection
