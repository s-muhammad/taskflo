<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <!-- ویجت شروع سریع -->
    <div
        class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30 ml-4">
                <i class="fas fa-rocket text-white text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">شروع سریع</h3>
        </div>
        <p class="text-slate-600 mb-5 text-sm">آموزش‌های اولیه برای شروع کار با تسک پلنر</p>
        <div class="space-y-3">
            @foreach($blogs->where('category_id',1) as $post)
                <a href="{{ route('blog.single',$post->id) }}"
                   class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                    <span class="text-slate-700 group-hover:text-indigo-600">{{ $post->title }}</span>
                    <i class="fas fa-arrow-left text-slate-400 group-hover:text-indigo-500"></i>
                </a>
            @endforeach
        </div>
    </div>

    <!-- ویجت نکات حرفه‌ای -->
    <div
        class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 ml-4">
                <i class="fas fa-lightbulb text-white text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">آخرین مقالات سایت</h3>
        </div>
        <p class="text-slate-600 mb-5 text-sm">راهکارهای پیشرفته برای افزایش بهره وری</p>
        <div class="space-y-3">
            @foreach($blogs as $blog)
                <a href="{{ route('blog.single',$blog->id) }}"
                   class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition group">
                    <span class="text-slate-700 group-hover:text-indigo-600">{{ $blog->title }}</span>
                    <i class="fas fa-arrow-left text-slate-400 group-hover:text-indigo-500"></i>
                </a>
            @endforeach
        </div>
    </div>

    <!-- ویجت ویدیو آموزشی -->
{{--    <div--}}
{{--        class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-shadow duration-300">--}}
{{--        <div class="flex items-center mb-4">--}}
{{--            <div--}}
{{--                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30 ml-4">--}}
{{--                <i class="fas fa-video text-white text-xl"></i>--}}
{{--            </div>--}}
{{--            <h3 class="text-lg font-bold text-slate-800">آموزش ویدیویی</h3>--}}
{{--        </div>--}}
{{--        <p class="text-slate-600 mb-5 text-sm">آموزش‌های تصویری برای درک بهتر</p>--}}
{{--        <div class="space-y-4">--}}
{{--            <div--}}
{{--                class="relative rounded-lg overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 aspect-video flex items-center justify-center">--}}
{{--                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>--}}
{{--                <button--}}
{{--                    class="relative z-10 w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition">--}}
{{--                    <i class="fas fa-play text-white text-2xl"></i>--}}
{{--                </button>--}}
{{--                <div class="absolute bottom-4 right-4 z-10 text-white">--}}
{{--                    <span class="text-sm">آموزش مقدماتی - ۱۵ دقیقه</span>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <a href="#" class="block text-center text-indigo-600 font-medium hover:text-indigo-700">--}}
{{--                مشاهده همه ویدیوها--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
