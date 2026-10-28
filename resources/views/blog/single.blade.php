@extends('blog.layout')
@section('title', $blog->meta_title)
@section('meta_description', $blog->meta_description)
@section('main')

<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">صفحه اصلی</a>
        <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ url('/blog') }}" class="hover:text-indigo-600 transition-colors">وبلاگ</a>
        <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-600 font-medium truncate max-w-[200px]">{{ $blog->title }}</span>
    </div>
</div>

<!-- Content + Sidebar -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-12">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Article Card (Left) -->
        <div class="flex-1 min-w-0">
            <article class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6">
                    <!-- Title -->
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        <h1 class="text-xl md:text-3xl lg:text-2xl font-black text-gray-900 leading-tight">{{ $blog->title }}</h1>
                        @if($blog->featured)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-full shadow-md shadow-indigo-600/20 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            پیشنهاد سردبیر
                        </span>
                        @endif
                    </div>

                    <!-- Meta Info: تاریخ و زمان مطالعه -->
                    <div class="flex flex-wrap items-center gap-4 text-gray-500 text-sm mb-5 pb-5 border-b border-gray-100">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-medium">@fa(\Morilog\Jalali\Jalalian::fromCarbon($blog->created_at)->format('%d %B %Y'))</span>
                        </div>
                        <span class="w-px h-5 bg-gray-200"></span>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium">@fa($blog->reading_time) دقیقه مطالعه</span>
                        </div>
                    </div>

                    <!-- Cover Image (هم‌عرض متن مقاله) -->
                    <div class="w-full rounded-xl overflow-hidden border border-gray-100 shadow-lg shadow-gray-200/60 mb-4">
                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="block w-full h-auto max-w-full">
                    </div>

                    <!-- Article Body -->
                    <div class="article-content text-gray-700 leading-relaxed text-[15px]">
                        {!! $blog->description !!}
                    </div>
                </div>
            </article>

            <!-- Share Bar (mobile - below article) -->
            <div class="mt-8 pt-6 border-t border-gray-100 lg:hidden">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-gray-700">اشتراک‌گذاری:</span>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.single', $blog->slug)) }}&text={{ urlencode($blog->title) }}" target="_blank" class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-900 hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.single', $blog->slug)) }}" target="_blank" class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); this.classList.add('bg-green-500','text-white'); this.querySelector('span').textContent='کپی شد';" class="h-9 px-3.5 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-indigo-500 hover:text-white transition-all duration-300 text-sm font-bold gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span>کپی لینک</span>
                    </button>
                </div>
            </div>

            <!-- Comments -->
            <section id="comments" class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h2 class="text-base font-black text-gray-900 flex items-center gap-2 mb-5">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        نظرات کاربران
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 rounded-full px-2 py-0.5">@fa($comments->count())</span>
                    </h2>

                    @if(session('comment_success'))
                        <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium px-4 py-3">
                            {{ session('comment_success') }}
                        </div>
                    @endif

                    <div class="space-y-3">
                        @forelse($comments as $comment)
                            <div class="flex gap-3 rounded-xl border border-gray-100 bg-gray-50/50 p-3.5">
                                <div class="w-9 h-9 shrink-0 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-xs font-bold flex items-center justify-center">
                                    {{ mb_substr($comment->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2 flex-wrap mb-1">
                                        <span class="text-sm font-bold text-gray-800">{{ $comment->name }}</span>
                                        <span class="text-[11px] text-gray-400">@fa(\Morilog\Jalali\Jalalian::fromCarbon($comment->created_at)->format('%d %B %Y'))</span>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line break-words">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 bg-gray-50/60 rounded-xl border border-dashed border-gray-200 px-4 py-4 text-center">
                                هنوز نظری ثبت نشده است. اولین نظر را شما بنویسید.
                            </p>
                        @endforelse
                    </div>

                    <!-- Comment Form -->
                    <form action="{{ route('blog.comment', $blog->slug) }}" method="POST" class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                        @csrf
                        <h3 class="text-sm font-bold text-gray-900">نظر خود را بنویسید</h3>

                        <div>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="نام و نام خانوادگی"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50/60 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder:text-gray-400">
                            @error('name')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <textarea name="comment" rows="4" placeholder="نظر شما درباره این مقاله..."
                                      class="w-full rounded-xl border border-gray-200 bg-gray-50/60 px-4 py-2.5 text-sm text-gray-700 leading-relaxed resize-none outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder:text-gray-400">{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- بخش کپچا -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                سوال امنیتی:
                            </label>

                            <div class="text-base font-bold bg-gray-100 text-gray-800 p-3 rounded-xl text-center mb-2 tracking-wide">
                                {{ session('comment_captcha_question') }}
                            </div>

                            <input type="number" name="captcha_answer" inputmode="numeric"
                                   class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none transition focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400"
                                   placeholder="پاسخ را وارد کنید"
                                   value="{{ old('captcha_answer') }}"
                                   required>

                            @error('captcha_answer')
                                <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <p class="text-[11px] text-gray-400">نظر شما پس از تایید مدیریت نمایش داده می‌شود.</p>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-600/25 hover:bg-indigo-700 transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                ارسال نظر
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <!-- Sidebar (Right) -->
        <aside class="w-full lg:w-[320px] shrink-0">
            <div class="lg:sticky lg:top-24 space-y-4">

                <!-- Author Box -->
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        درباره نویسنده
                    </h4>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 shrink-0 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-base shadow-lg shadow-indigo-500/20">س‌م</div>
                        <div>
                            <h5 class="font-bold text-gray-900 text-sm">سیدمحمد محمدی</h5>
                            <p class="text-xs text-gray-400 mt-0.5">مدیر سایت</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs leading-relaxed mt-4">علاقه‌مند به بررسی روش‌های جدید افزایش بهره‌وری و کمک به تیم‌ها برای رسیدن به اهدافشان.</p>
                </div>

                <!-- Related Articles -->
                @if($related->count() > 0)
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            مطالب پیشنهادی
                        </h4>
                        <div class="space-y-3">
                            @foreach($related as $rel)
                                <a href="{{ route('blog.single', $rel->slug) }}" class="flex gap-3 group">
                                    <div class="w-16 h-14 shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="{{ asset($rel->image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-relaxed">{{ $rel->title }}</h5>
                                        <span class="text-xs text-gray-400 mt-1 block">@fa(\Morilog\Jalali\Jalalian::fromCarbon($rel->created_at)->format('%d %B %Y'))</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Share -->
                <div class="hidden lg:block bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368 2.684z"></path></svg>
                        اشتراک‌گذاری
                    </h4>
                    <div class="flex items-center gap-2">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.single', $blog->slug)) }}&text={{ urlencode($blog->title) }}" target="_blank" class="flex-1 h-9 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-gray-900 hover:text-white transition-all duration-300" title="توییتر">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.single', $blog->slug)) }}" target="_blank" class="flex-1 h-9 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition-all duration-300" title="لینکدین">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); this.classList.add('bg-green-500','text-white'); this.querySelector('span').textContent='کپی شد';" class="flex-1 h-9 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-indigo-500 hover:text-white transition-all duration-300 text-xs font-bold gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span>کپی لینک</span>
                        </button>
                    </div>
                </div>

                @php
                    $adActive = setting('sidebar_ad_active');
                    $adTitle = setting('sidebar_ad_title');
                    $adImage = setting('sidebar_ad_image');
                    $adLink = setting('sidebar_ad_link');
                    if ($adLink && !preg_match('#^https?://#i', $adLink)) {
                        $adLink = 'https://' . $adLink;
                    }
                @endphp

                @if($adActive && $adImage)
                    <!-- بنر تبلیغاتی سایدبار -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                        @if($adTitle)
                            <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.643A4.001 4.001 0 017 6h1.832c4.059 0 7.514 7.514 7.514 7.514a7.46 7.46 0 01-1.665 3.504M5.436 13.643V20.4c0 .354.177.687.47.89l3.09 2.06a.75.75 0 001.146-.638V13.64a.75.75 0 00-.98-.71l-3.24 1.02a.75.75 0 00-.486.693z"></path></svg>
                                {{ $adTitle }}
                            </h4>
                        @endif

                        @if($adLink)
                            <a href="{{ $adLink }}" target="_blank" rel="nofollow noopener"
                               class="block rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                                <img src="{{ asset($adImage) }}" alt="{{ $adTitle ?: 'تبلیغات' }}"
                                     class="block w-full h-auto max-w-full">
                            </a>
                        @else
                            <div class="rounded-xl overflow-hidden border border-gray-100">
                                <img src="{{ asset($adImage) }}" alt="{{ $adTitle ?: 'تبلیغات' }}"
                                     class="block w-full h-auto max-w-full">
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </aside>
    </div>
</main>

@endsection
