<?php

namespace App\Livewire;

use App\Models\BlogCategory;
use Livewire\Component;

class BlogCategorySelect extends Component
{
    public $selectedCategoryId = null;
    public $showModal = false;
    public $newCategoryName = '';
    public $editCategoryId = null;

    public function mount($selectedCategoryId = null)
    {
        $this->selectedCategoryId = $selectedCategoryId;
    }

    public function getCategoriesProperty()
    {
        return BlogCategory::orderBy('name')->get();
    }

    public function openModal()
    {
        $this->editCategoryId = null;
        $this->newCategoryName = '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editCategoryId = null;
        $this->newCategoryName = '';
        $this->resetValidation();
    }

    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:2|max:50|unique:blog_categories,name' . ($this->editCategoryId ? ',' . $this->editCategoryId : ''),
        ], [
            'newCategoryName.required' => 'نام دسته‌بندی الزامی است.',
            'newCategoryName.min' => 'نام باید حداقل ۲ حرف باشد.',
            'newCategoryName.unique' => 'این دسته‌بندی قبلاً ایجاد شده است.',
        ]);

        if ($this->editCategoryId) {
            BlogCategory::find($this->editCategoryId)->update(['name' => $this->newCategoryName]);
        } else {
            $category = BlogCategory::create(['name' => $this->newCategoryName]);
            $this->selectedCategoryId = $category->id;
        }

        $this->closeModal();
    }

    public function deleteCategory($id)
    {
        BlogCategory::find($id)?->delete();
        if ($this->selectedCategoryId == $id) {
            $this->selectedCategoryId = null;
        }
    }

    public function render()
    {
        return view('livewire.blog-category-select');
    }
}
