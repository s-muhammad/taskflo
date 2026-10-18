@php
    use Morilog\Jalali\Jalalian;
    use App\Models\SeoKeyword;
    use App\Models\SeoTask;

    $keywordStatusColors = [
        'idea' => 'bg-gray-100 text-gray-700',
        'planned' => 'bg-blue-100 text-blue-700',
        'writing' => 'bg-amber-100 text-amber-700',
        'published' => 'bg-green-100 text-green-700',
        'needs_improvement' => 'bg-red-100 text-red-700',
    ];
    $difficultyColors = [
        'low' => 'bg-green-100 text-green-700',
        'medium' => 'bg-amber-100 text-amber-700',
        'high' => 'bg-red-100 text-red-700',
    ];
    $priorityColors = [
        'low' => 'bg-gray-100 text-gray-700',
        'medium' => 'bg-amber-100 text-amber-700',
        'high' => 'bg-red-100 text-red-700',
    ];
    $taskStatusColors = [
        'todo' => 'bg-gray-100 text-gray-700',
        'in_progress' => 'bg-blue-100 text-blue-700',
        'done' => 'bg-green-100 text-green-700',
    ];
@endphp

<div>
    @if(session('seo_success'))
        <div class="bg-green-50 border-r-4 border-green-500 text-green-700 p-4 rounded-lg mb-8 shadow-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-xl ml-3"></i>
                <p class="font-semibold text-sm">{{ session('seo_success') }}</p>
            </div>
        </div>
    @endif

    {{-- عنوان صفحه --}}
    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
        <h2 class="text-3xl font-extrabold text-gray-800">
            <i class="fas fa-magnifying-glass-chart text-blue-600 ml-3"></i>
            دفترچه سئو
        </h2>
    </div>

    {{-- تب‌ها --}}
    <div class="flex gap-2 mb-6">
        <button wire:click="setTab('keywords')"
                class="px-6 py-3 rounded-xl font-bold transition duration-200 flex items-center gap-2
                {{ $tab === 'keywords' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/40' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-key"></i>
            کلمات کلیدی
        </button>
        <button wire:click="setTab('tasks')"
                class="px-6 py-3 rounded-xl font-bold transition duration-200 flex items-center gap-2
                {{ $tab === 'tasks' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/40' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i class="fas fa-list-check"></i>
            کارها
        </button>
    </div>

    @if($tab === 'keywords')
        {{-- نوار ابزار کلمات کلیدی --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex flex-col sm:flex-row gap-3 flex-1">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.live.debounce.400ms="kSearch"
                               placeholder="جستجوی کلمه کلیدی..."
                               class="w-full pr-11 pl-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <select wire:model.live="kStatus"
                            class="px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">همه وضعیت‌ها</option>
                        @foreach(SeoKeyword::STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="createKeyword"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg
                        shadow-blue-500/50 transition duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    کلمه کلیدی جدید
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-right divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm font-semibold tracking-wider">
                            <th class="py-4 px-4 rounded-r-xl">کلمه کلیدی</th>
                            <th class="py-4 px-4">حجم جستجو</th>
                            <th class="py-4 px-4 text-center">سختی</th>
                            <th class="py-4 px-4 text-center">قصد</th>
                            <th class="py-4 px-4 text-center">وضعیت</th>
                            <th class="py-4 px-4 text-center">کارهای باز</th>
                            <th class="py-4 px-4 text-center">رتبه</th>
                            <th class="py-4 px-4 rounded-l-xl text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($keywords as $kw)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200" wire:key="kw-{{ $kw->id }}">
                                <td class="py-4 px-4 max-w-xs">
                                    <div class="font-semibold text-gray-800">{{ $kw->keyword }}</div>
                                    @if($kw->target_url)
                                        <a href="{{ $kw->target_url }}" target="_blank"
                                           class="text-xs text-blue-500 hover:text-blue-700 break-all" dir="ltr">{{ $kw->target_url }}</a>
                                    @endif
                                    @if($kw->notes)
                                        <div class="text-xs text-gray-400 mt-1 truncate" title="{{ $kw->notes }}">{{ $kw->notes }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-gray-600">
                                    @fa($kw->search_volume !== null ? number_format($kw->search_volume) : '—')
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($kw->difficulty)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $difficultyColors[$kw->difficulty] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ SeoKeyword::DIFFICULTY_LABELS[$kw->difficulty] ?? $kw->difficulty }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center text-sm text-gray-600">
                                    {{ $kw->intent ? (SeoKeyword::INTENT_LABELS[$kw->intent] ?? $kw->intent) : '—' }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <select wire:change="updateKeywordStatus({{ $kw->id }}, $event.target.value)"
                                            class="text-xs font-semibold rounded-full px-3 py-2 border-0 cursor-pointer focus:ring-2 focus:ring-blue-400 {{ $keywordStatusColors[$kw->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        @foreach(SeoKeyword::STATUS_LABELS as $value => $label)
                                            <option value="{{ $value }}" @selected($kw->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $kw->open_tasks_count > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $kw->open_tasks_count }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center text-gray-600">
                                    {{ $kw->current_rank !== null ? $kw->current_rank : '—' }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="editKeyword({{ $kw->id }})" title="ویرایش"
                                                class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition duration-200">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteKeyword({{ $kw->id }})"
                                                wire:confirm="آیا مطمئن هستید که می‌خواهید این کلمه کلیدی را حذف کنید؟"
                                                title="حذف"
                                                class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-200">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-gray-500 text-lg">
                                    <i class="fas fa-key mb-3 text-3xl text-gray-300"></i>
                                    <p>هیچ کلمه کلیدی‌ای یافت نشد.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-center">
                {{ $keywords->links() }}
            </div>
        </div>
    @else
        {{-- نوار ابزار کارها --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex flex-col sm:flex-row gap-3 flex-1">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.live.debounce.400ms="tSearch"
                               placeholder="جستجوی کار..."
                               class="w-full pr-11 pl-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <select wire:model.live="tStatus"
                            class="px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">همه وضعیت‌ها</option>
                        @foreach(SeoTask::STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="tPriority"
                            class="px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">همه اولویت‌ها</option>
                        @foreach(SeoTask::PRIORITY_LABELS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="createTask"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg
                        shadow-blue-500/50 transition duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    کار جدید
                </button>
            </div>

            <div class="space-y-3">
                @forelse($tasks as $task)
                    @php
                        $isDone = $task->status === 'done';
                        $isOverdue = $task->due_date && ! $isDone && $task->due_date->lt(today());
                    @endphp
                    <div wire:key="task-{{ $task->id }}"
                         class="flex items-start gap-4 p-4 rounded-xl border transition duration-200
                         {{ $isDone ? 'opacity-60 bg-gray-50 border-gray-200' : ($isOverdue ? 'bg-red-50 border-red-300' : 'bg-white border-gray-200 hover:border-blue-300') }}">
                        <button wire:click="toggleTask({{ $task->id }})" title="تغییر وضعیت انجام"
                                class="mt-1 w-6 h-6 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition
                                {{ $isDone ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 text-transparent hover:border-green-500' }}">
                            <i class="fas fa-check text-xs"></i>
                        </button>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-semibold text-gray-800 {{ $isDone ? 'line-through' : '' }}">{{ $task->title }}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ SeoTask::PRIORITY_LABELS[$task->priority] ?? $task->priority }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $taskStatusColors[$task->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ SeoTask::STATUS_LABELS[$task->status] ?? $task->status }}
                                </span>
                                @if($isOverdue)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <i class="fas fa-triangle-exclamation"></i> دیرکرد
                                    </span>
                                @endif
                            </div>

                            @if($task->description)
                                <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                            @endif

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-xs text-gray-500">
                                @if($task->due_date)
                                    <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                        <i class="fas fa-calendar-day"></i>
                                        @fa(Jalalian::fromCarbon($task->due_date)->format('Y/m/d'))
                                    </span>
                                @endif
                                @if($task->keyword)
                                    <span><i class="fas fa-key"></i> {{ $task->keyword->keyword }}</span>
                                @endif
                                @if($task->related_url)
                                    <a href="{{ $task->related_url }}" target="_blank" class="text-blue-500 hover:text-blue-700" dir="ltr">
                                        <i class="fas fa-link"></i> {{ $task->related_url }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button wire:click="editTask({{ $task->id }})" title="ویرایش"
                                    class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="deleteTask({{ $task->id }})"
                                    wire:confirm="آیا مطمئن هستید که می‌خواهید این کار را حذف کنید؟"
                                    title="حذف"
                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-200">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-500 text-lg">
                        <i class="fas fa-list-check mb-3 text-3xl text-gray-300"></i>
                        <p>هیچ کاری یافت نشد.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- فرم کلمه کلیدی --}}
    @if($showKeywordForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:key="keyword-modal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-extrabold text-gray-800">
                        <i class="fas fa-key text-blue-600 ml-2"></i>
                        {{ $keywordId ? 'ویرایش کلمه کلیدی' : 'کلمه کلیدی جدید' }}
                    </h3>
                    <button wire:click="cancelKeyword" class="text-gray-400 hover:text-gray-600 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveKeyword" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">کلمه کلیدی <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="keyword"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('keyword') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">حجم جستجو</label>
                            <input type="number" min="0" wire:model="searchVolume"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('searchVolume') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">سختی</label>
                            <select wire:model="difficulty"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">—</option>
                                @foreach(SeoKeyword::DIFFICULTY_LABELS as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('difficulty') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">قصد جستجو</label>
                            <select wire:model="intent"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">—</option>
                                @foreach(SeoKeyword::INTENT_LABELS as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('intent') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select wire:model="kFormStatus"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                @foreach(SeoKeyword::STATUS_LABELS as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('kFormStatus') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">رتبه فعلی</label>
                            <input type="number" min="1" wire:model="currentRank"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('currentRank') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">آدرس هدف</label>
                            <input type="text" dir="ltr" wire:model="targetUrl" placeholder="/blog/example"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('targetUrl') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">یادداشت</label>
                        <textarea wire:model="notes" rows="3"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="cancelKeyword"
                                class="px-6 py-3 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            انصراف
                        </button>
                        <button type="submit"
                                class="px-8 py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/40 transition">
                            ذخیره
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- فرم کار --}}
    @if($showTaskForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:key="task-modal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-extrabold text-gray-800">
                        <i class="fas fa-list-check text-blue-600 ml-2"></i>
                        {{ $taskId ? 'ویرایش کار' : 'کار جدید' }}
                    </h3>
                    <button wire:click="cancelTask" class="text-gray-400 hover:text-gray-600 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveTask" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">توضیحات</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">اولویت <span class="text-red-500">*</span></label>
                            <select wire:model="tFormPriority"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                @foreach(SeoTask::PRIORITY_LABELS as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tFormPriority') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select wire:model="tFormStatus"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                @foreach(SeoTask::STATUS_LABELS as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tFormStatus') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">تاریخ سررسید (شمسی)</label>
                            <input type="text" dir="ltr" wire:model="dueDate" placeholder="1403/05/12"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center">
                            @error('dueDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">کلمه کلیدی مرتبط</label>
                            <select wire:model="seoKeywordId"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">— بدون کلمه کلیدی —</option>
                                @foreach($keywordOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->keyword }}</option>
                                @endforeach
                            </select>
                            @error('seoKeywordId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">آدرس مرتبط</label>
                            <input type="text" dir="ltr" wire:model="relatedUrl" placeholder="/blog/example"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('relatedUrl') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="cancelTask"
                                class="px-6 py-3 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            انصراف
                        </button>
                        <button type="submit"
                                class="px-8 py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/40 transition">
                            ذخیره
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
