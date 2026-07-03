@php
    $currentRoute = request()->routeIs('admin.index') ? 'admin.index' : 'admin.user.index';
@endphp
<aside id="sidebar"
       class="fixed inset-y-0 right-0 w-64 bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6 shadow-2xl z-50
              md:relative md:block hidden transition-all duration-300 ease-in-out border-l border-gray-700/50">
    <div class="flex items-center justify-center mb-10 pb-4 border-b border-gray-700">
        <img src="{{ setting('site_logo') }}" alt="" width="35px" >
        <div class="text-3xl font-extrabold tracking-wide">
            <a href="{{url('/')}}" target="_blank" class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-300
             hover:from-blue-300 hover:to-blue-200 transition-colors duration-300">
                {{ setting('site_title') }}
            </a>
        </div>
    </div>
    <nav class="space-y-2.5">
        @php
            $links = [
                'admin.' => ['label' => 'داشبورد', 'icon' => 'fas fa-chart-line'],
                'admin.user.index' => ['label' => 'کاربران', 'icon' => 'fas fa-users'],
                'admin.comments.index' => ['label' => 'نظرات', 'icon' => 'fas fa-file-alt'],
                'admin.blog.index' => ['label' => 'مقالات', 'icon' => 'fas fa-newspaper'],
                'admin.ticket.index' => ['label' => 'تیکت', 'icon' => 'fas fa-ticket'],
                'admin.settings.index' => ['label' => 'تنظیمات', 'icon' => 'fas fa-cog'],
            ];
        @endphp
        @foreach($links as $route => $data)
            @php
                $isActive = request()->routeIs($route);
            @endphp
            <a href="{{ route($route) }}"
               class="flex items-center p-3 rounded-xl transition-all duration-200 ease-in-out group
                      {{ $isActive
                         ? 'bg-blue-600 shadow-md shadow-blue-500/50 text-white font-bold'
                         : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                      }}">
                <i class="{{ $data['icon'] }} text-lg ml-3 {{ $isActive ? 'text-white' : 'text-blue-400 group-hover:text-blue-300' }}
                group-hover:scale-110 transition-transform"></i>
                <span class="font-medium">{{ $data['label'] }}</span>
                @if($isActive)
                    <i class="fas fa-dot-circle text-xs mr-auto text-white"></i>
                @endif
            </a>
        @endforeach
    </nav>
    <div class="mt-8 pt-4 border-t border-gray-700">
        <a href="#" onclick="document.getElementById('form').submit();"
           class="flex items-center p-3 rounded-xl bg-red-700 hover:bg-red-600 transition-all duration-200 ease-in-out text-white font-bold
            shadow-lg shadow-red-700/50 group w-full"
        >
            <i class="fas fa-sign-out-alt text-lg ml-3 group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">خروج از پنل</span>
        </a>
    </div>
    <form action="{{route('logout')}}" method="post" id="form" style="display: none">
        @csrf
    </form>
</aside>
