@php
    $currentRoute = request()->routeIs('admin.index') ? 'admin.index' : 'admin.user.index';
@endphp
<aside id="sidebar"
       class="fixed inset-y-0 start-0 w-64 bg-white dark:bg-slate-900 border-e border-slate-200 dark:border-slate-800 p-4 shadow-soft z-50
              md:relative md:block hidden transition-all duration-300 ease-in-out">
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-soft text-white flex-shrink-0">
            <img src="{{ setting('site_logo') }}" alt="" class="w-6 h-6 object-contain">
        </div>
        <div class="text-xl font-bold text-slate-900 dark:text-white truncate">
            <a href="{{url('/')}}" target="_blank">
                {{ setting('site_title') }}
            </a>
        </div>
    </div>
    <nav class="space-y-1">
        @php
            $links = [
                'admin.' => ['label' => 'داشبورد', 'icon' => 'fas fa-chart-line'],
                'admin.user.index' => ['label' => 'کاربران', 'icon' => 'fas fa-users'],
                'admin.comments.index' => ['label' => 'نظرات', 'icon' => 'fas fa-file-alt'],
                'admin.blog.index' => ['label' => 'مقالات', 'icon' => 'fas fa-newspaper'],
                'admin.seo.index' => ['label' => 'سئو', 'icon' => 'fas fa-magnifying-glass-chart'],
                'admin.ticket.index' => ['label' => 'تیکت', 'icon' => 'fas fa-ticket'],
                'admin.settings.index' => ['label' => 'تنظیمات', 'icon' => 'fas fa-cog'],
            ];
        @endphp
        @foreach($links as $route => $data)
            @php
                $isActive = request()->routeIs($route);
            @endphp
            <a href="{{ route($route) }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 group text-sm
                      {{ $isActive
                         ? 'bg-indigo-600 text-white shadow-soft'
                         : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100'
                      }}">
                <i class="{{ $data['icon'] }} w-5 text-center transition-transform group-hover:scale-110"></i>
                <span class="font-medium">{{ $data['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <div class="mt-auto pt-4 border-t border-slate-200 dark:border-slate-800">
        <a href="#" onclick="document.getElementById('form').submit();"
           class="flex items-center gap-3 p-2.5 rounded-xl border border-rose-200/60 dark:border-rose-900/40 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-all text-sm font-medium group w-full"
        >
            <i class="fas fa-sign-out-alt w-5 text-center transition-transform group-hover:scale-110"></i>
            <span class="font-medium">خروج از پنل</span>
        </a>
    </div>
    <form action="{{route('logout')}}" method="post" id="form" style="display: none">
        @csrf
    </form>
</aside>
