<?php

namespace App\Livewire;

use App\Models\SeoKeyword;
use App\Models\SeoTask;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Throwable;

class SeoNotebook extends Component
{
    use WithPagination;

    public $tab = 'keywords';

    // فیلترهای کلمات کلیدی
    public $kSearch = '';

    public $kStatus = '';

    // فرم کلمه کلیدی
    public $showKeywordForm = false;

    public $keywordId = null;

    public $keyword = '';

    public $searchVolume = null;

    public $difficulty = '';

    public $intent = '';

    public $kFormStatus = 'idea';

    public $targetUrl = '';

    public $currentRank = null;

    public $notes = '';

    // فیلترهای کارها
    public $tSearch = '';

    public $tStatus = '';

    public $tPriority = '';

    // فرم کار
    public $showTaskForm = false;

    public $taskId = null;

    public $title = '';

    public $description = '';

    public $tFormPriority = 'medium';

    public $tFormStatus = 'todo';

    public $dueDate = '';

    public $seoKeywordId = '';

    public $relatedUrl = '';

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->is_admin, 403);
    }

    public function setTab($tab): void
    {
        $this->tab = $tab === 'tasks' ? 'tasks' : 'keywords';
        $this->resetPage();
    }

    public function updatingKSearch(): void
    {
        $this->resetPage();
    }

    public function updatingKStatus(): void
    {
        $this->resetPage();
    }

    /* ---------------------------- کلمات کلیدی ---------------------------- */

    public function createKeyword(): void
    {
        $this->resetKeywordForm();
        $this->showKeywordForm = true;
    }

    public function editKeyword($id): void
    {
        $kw = SeoKeyword::findOrFail($id);

        $this->keywordId = $kw->id;
        $this->keyword = $kw->keyword;
        $this->searchVolume = $kw->search_volume;
        $this->difficulty = $kw->difficulty ?? '';
        $this->intent = $kw->intent ?? '';
        $this->kFormStatus = $kw->status ?: 'idea';
        $this->targetUrl = $kw->target_url ?? '';
        $this->currentRank = $kw->current_rank;
        $this->notes = $kw->notes ?? '';
        $this->showKeywordForm = true;
    }

    public function cancelKeyword(): void
    {
        $this->showKeywordForm = false;
        $this->resetKeywordForm();
    }

    public function saveKeyword(): void
    {
        $this->keyword = trim($this->keyword);
        $this->difficulty = $this->difficulty ?: null;
        $this->intent = $this->intent ?: null;

        $data = $this->validate([
            'keyword' => ['required', 'string', 'max:190', Rule::unique('seo_keywords', 'keyword')->ignore($this->keywordId)],
            'searchVolume' => ['nullable', 'integer', 'min:0'],
            'difficulty' => ['nullable', Rule::in(SeoKeyword::DIFFICULTIES)],
            'intent' => ['nullable', Rule::in(SeoKeyword::INTENTS)],
            'kFormStatus' => ['required', Rule::in(SeoKeyword::STATUSES)],
            'targetUrl' => ['nullable', 'string', 'max:190'],
            'currentRank' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ], $this->keywordMessages(), $this->keywordAttributes());

        $payload = [
            'keyword' => $data['keyword'],
            'search_volume' => $this->searchVolume,
            'difficulty' => $this->difficulty,
            'intent' => $this->intent,
            'status' => $data['kFormStatus'],
            'target_url' => $this->targetUrl ?: null,
            'current_rank' => $this->currentRank,
            'notes' => $this->notes ?: null,
        ];

        $isEdit = (bool) $this->keywordId;

        if ($isEdit) {
            SeoKeyword::whereKey($this->keywordId)->update($payload);
        } else {
            SeoKeyword::create($payload);
        }

        $this->showKeywordForm = false;
        $this->resetKeywordForm();
        session()->flash('seo_success', $isEdit ? 'کلمه کلیدی ویرایش شد.' : 'کلمه کلیدی جدید ثبت شد.');
    }

    public function updateKeywordStatus($id, $status): void
    {
        if (! in_array($status, SeoKeyword::STATUSES, true)) {
            return;
        }

        SeoKeyword::whereKey($id)->update(['status' => $status]);
    }

    public function deleteKeyword($id): void
    {
        SeoKeyword::whereKey($id)->delete();
        session()->flash('seo_success', 'کلمه کلیدی حذف شد.');
    }

    protected function resetKeywordForm(): void
    {
        $this->reset(['keywordId', 'keyword', 'searchVolume', 'difficulty', 'intent', 'targetUrl', 'currentRank', 'notes']);
        $this->kFormStatus = 'idea';
        $this->resetValidation();
    }

    /* ------------------------------- کارها ------------------------------- */

    public function createTask(): void
    {
        $this->resetTaskForm();
        $this->showTaskForm = true;
    }

    public function editTask($id): void
    {
        $task = SeoTask::findOrFail($id);

        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description ?? '';
        $this->tFormPriority = $task->priority ?: 'medium';
        $this->tFormStatus = $task->status ?: 'todo';
        $this->dueDate = $task->due_date ? Jalalian::fromCarbon($task->due_date)->format('Y/m/d') : '';
        $this->seoKeywordId = $task->seo_keyword_id ?? '';
        $this->relatedUrl = $task->related_url ?? '';
        $this->showTaskForm = true;
    }

    public function cancelTask(): void
    {
        $this->showTaskForm = false;
        $this->resetTaskForm();
    }

    public function saveTask(): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'tFormPriority' => ['required', Rule::in(SeoTask::PRIORITIES)],
            'tFormStatus' => ['required', Rule::in(SeoTask::STATUSES)],
            'dueDate' => ['bail', 'nullable', 'regex:/^\d{4}\/\d{2}\/\d{2}$/', $this->validJalaliDate(...)],
            'seoKeywordId' => ['nullable', Rule::exists('seo_keywords', 'id')],
            'relatedUrl' => ['nullable', 'string', 'max:190'],
        ], $this->taskMessages(), $this->taskAttributes());

        $due = $this->dueDate ? Jalalian::fromFormat('Y/m/d', $this->dueDate)->toCarbon() : null;

        $isEdit = (bool) $this->taskId;
        $isDone = $data['tFormStatus'] === 'done';

        $payload = [
            'title' => $data['title'],
            'description' => $this->description ?: null,
            'priority' => $data['tFormPriority'],
            'status' => $data['tFormStatus'],
            'due_date' => $due,
            'seo_keyword_id' => $this->seoKeywordId ?: null,
            'related_url' => $this->relatedUrl ?: null,
        ];

        if ($isEdit) {
            $task = SeoTask::findOrFail($this->taskId);
            $payload['completed_at'] = $isDone ? ($task->completed_at ?? now()) : null;
            $task->update($payload);
        } else {
            $payload['completed_at'] = $isDone ? now() : null;
            SeoTask::create($payload);
        }

        $this->showTaskForm = false;
        $this->resetTaskForm();
        session()->flash('seo_success', $isEdit ? 'کار ویرایش شد.' : 'کار جدید ثبت شد.');
    }

    public function toggleTask($id): void
    {
        $task = SeoTask::find($id);

        if (! $task) {
            return;
        }

        if ($task->status === 'done') {
            $task->status = 'todo';
            $task->completed_at = null;
        } else {
            $task->status = 'done';
            $task->completed_at = now();
        }

        $task->save();
    }

    public function deleteTask($id): void
    {
        SeoTask::whereKey($id)->delete();
        session()->flash('seo_success', 'کار حذف شد.');
    }

    protected function resetTaskForm(): void
    {
        $this->reset(['taskId', 'title', 'description', 'dueDate', 'seoKeywordId', 'relatedUrl']);
        $this->tFormPriority = 'medium';
        $this->tFormStatus = 'todo';
        $this->resetValidation();
    }

    protected function validJalaliDate(string $attribute, $value, callable $fail): void
    {
        if (! preg_match('/^(\d{4})\/(\d{2})\/(\d{2})$/', (string) $value, $matches)) {
            return;
        }

        [, $year, $month, $day] = $matches;

        try {
            $normalized = Jalalian::fromFormat('Y/m/d', $value)->format('Y/m/d');
        } catch (Throwable $e) {
            $fail('تاریخ وارد شده معتبر نیست (نمونه: 1403/05/12).');

            return;
        }

        if ($normalized !== sprintf('%04d/%02d/%02d', (int) $year, (int) $month, (int) $day)) {
            $fail('تاریخ وارد شده معتبر نیست (نمونه: 1403/05/12).');
        }
    }

    /* ------------------------------ پیام‌ها ------------------------------ */

    protected function keywordMessages(): array
    {
        return [
            'keyword.required' => 'وارد کردن کلمه کلیدی الزامی است.',
            'keyword.max' => 'کلمه کلیدی نباید بیشتر از ۱۹۰ نویسه باشد.',
            'keyword.unique' => 'این کلمه کلیدی قبلاً ثبت شده است.',
            'searchVolume.integer' => 'حجم جستجو باید عدد باشد.',
            'searchVolume.min' => 'حجم جستجو نمی‌تواند منفی باشد.',
            'difficulty.in' => 'مقدار سختی نامعتبر است.',
            'intent.in' => 'مقدار قصد جستجو نامعتبر است.',
            'kFormStatus.required' => 'انتخاب وضعیت الزامی است.',
            'kFormStatus.in' => 'وضعیت انتخاب‌شده نامعتبر است.',
            'currentRank.integer' => 'رتبه فعلی باید عدد باشد.',
            'currentRank.min' => 'رتبه فعلی باید حداقل ۱ باشد.',
        ];
    }

    protected function keywordAttributes(): array
    {
        return [
            'keyword' => 'کلمه کلیدی',
            'searchVolume' => 'حجم جستجو',
            'difficulty' => 'سختی',
            'intent' => 'قصد جستجو',
            'kFormStatus' => 'وضعیت',
            'targetUrl' => 'آدرس هدف',
            'currentRank' => 'رتبه فعلی',
            'notes' => 'یادداشت',
        ];
    }

    protected function taskMessages(): array
    {
        return [
            'title.required' => 'وارد کردن عنوان کار الزامی است.',
            'title.max' => 'عنوان کار نباید بیشتر از ۱۹۰ نویسه باشد.',
            'tFormPriority.required' => 'انتخاب اولویت الزامی است.',
            'tFormPriority.in' => 'اولویت انتخاب‌شده نامعتبر است.',
            'tFormStatus.required' => 'انتخاب وضعیت الزامی است.',
            'tFormStatus.in' => 'وضعیت انتخاب‌شده نامعتبر است.',
            'dueDate.regex' => 'تاریخ باید به شکل 1403/05/12 وارد شود.',
            'seoKeywordId.exists' => 'کلمه کلیدی انتخاب‌شده نامعتبر است.',
        ];
    }

    protected function taskAttributes(): array
    {
        return [
            'title' => 'عنوان',
            'description' => 'توضیحات',
            'tFormPriority' => 'اولویت',
            'tFormStatus' => 'وضعیت',
            'dueDate' => 'تاریخ سررسید',
            'seoKeywordId' => 'کلمه کلیدی مرتبط',
            'relatedUrl' => 'آدرس مرتبط',
        ];
    }

    public function render()
    {
        $keywords = SeoKeyword::query()
            ->when($this->kSearch, fn ($q) => $q->where('keyword', 'like', '%'.trim($this->kSearch).'%'))
            ->when($this->kStatus, fn ($q) => $q->where('status', $this->kStatus))
            ->withCount(['tasks as open_tasks_count' => fn ($q) => $q->where('status', '!=', 'done')])
            ->latest()
            ->paginate(10);

        $tasks = SeoTask::query()
            ->with('keyword')
            ->when($this->tSearch, fn ($q) => $q->where('title', 'like', '%'.trim($this->tSearch).'%'))
            ->when($this->tStatus, fn ($q) => $q->where('status', $this->tStatus))
            ->when($this->tPriority, fn ($q) => $q->where('priority', $this->tPriority))
            ->orderByRaw("status = 'done'")
            ->orderByRaw('due_date is null')
            ->orderBy('due_date')
            ->get();

        return view('livewire.seo-notebook', [
            'keywords' => $keywords,
            'tasks' => $tasks,
            'keywordOptions' => SeoKeyword::orderBy('keyword')->get(['id', 'keyword']),
        ]);
    }
}
