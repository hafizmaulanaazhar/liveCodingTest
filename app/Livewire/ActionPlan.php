<?php

namespace App\Livewire;

use Livewire\Component;
use App\Queries\ActionPlanQuery;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ActionPlan extends Component
{
    public $title = '';
    public $priority = '';
    public $editId = null;
    public $editTitle = '';
    public $due_date = '';
    public $category = '';
    public $categories = [];
    public $newCategory = '';
    public $detail_agenda = '';
    public $showCategoryForm = false;
    public $page = 3;
    public $loadingMore = false;
    public $selectedTask = null;
    public $showModal = false;
    public $filter = 'today';


    public function mount()
    {
        $this->categories = Category::all();
    }

    #[On('filterChanged')]
    public function applyFilter($filter)
    {
        $this->filter = $filter;
    }

    #[Computed]
    public function tasks()
    {
        return ActionPlanQuery::getTasks($this->filter, $this->page);
    }

    #[Computed]
    public function totalTasks()
    {
        return ActionPlanQuery::countPendingTasks();
    }

    public function addTask()
    {
        $this->validate([
            'title' => 'required|string',
            'priority' => 'required|string|in:high,medium,low',
            'due_date' => 'nullable|date',
            'category' => 'nullable|string',
            'detail_agenda' => 'nullable|string',
        ]);

        ActionPlanQuery::createTask([
            'title' => $this->title,
            'priority' => $this->priority,
            'done' => false,
            'due_date' => $this->due_date && $this->due_date !== '' ? $this->due_date : now()->toDateString(),
            'category' => $this->category ?: null,
            'detail_agenda' => $this->detail_agenda ?: null,
        ]);

        $this->reset(['title', 'priority', 'due_date', 'category', 'detail_agenda']);
        $this->dispatch('formReset');
    }

    public function markDone($id)
    {
        ActionPlanQuery::markTaskDone($id);
        $this->editId = null;
        $this->editTitle = '';
    }

    public function saveEdit()
    {
        if ($this->editId && $this->editTitle !== '') {
            ActionPlanQuery::updateTaskTitle($this->editId, $this->editTitle);
            $this->editId = null;
            $this->editTitle = '';
        }
    }

    public function deleteTask($id)
    {
        ActionPlanQuery::deleteTask($id);
    }

    public function saveDetail()
    {
        if ($this->selectedTask) {
            $this->validate([
                'title' => 'required|string',
                'priority' => 'required|string|in:high,medium,low',
                'due_date' => 'required|date',
                'category' => 'nullable|string',
                'detail_agenda' => 'nullable|string',
            ]);

            ActionPlanQuery::updateTaskDetail($this->selectedTask->id, [
                'title' => $this->title,
                'priority' => $this->priority,
                'due_date' => $this->due_date ?: null,
                'category' => $this->category ?: null,
                'detail_agenda' => $this->detail_agenda ?: null,
            ]);

            $this->closeModal();
        }
    }


    public function render()
    {
        return view('livewire.action-plan');
    }
}
