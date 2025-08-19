<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
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
        $query = Task::query();

        if (str_starts_with($this->filter, 'category:')) {
            $category = str_replace('category:', '', $this->filter);
            $query->where('category', $category);
            if ($this->filter !== 'done') {
                $query->where('done', false);
            }
        } else {
            switch ($this->filter) {
                case 'today':
                    $query->whereDate('due_date', today())
                        ->where('done', false);
                    break;
                case 'next7':
                    $query->whereBetween('due_date', [today()->addDay(), today()->addDays(7)])
                        ->where('done', false);
                    break;
                case 'done':
                    $query->where('done', true);
                    break;
                default:
                    $query->where('done', false);
                    break;
            }
        }

        return $query->latest()
            ->paginate($this->page);
    }

    #[Computed()]
    public function totalTasks()
    {
        return Task::where('done', false)->count();
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

        Task::create([
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


    public function saveCategory()
    {
        $this->validate([
            'newCategory' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $this->newCategory,
        ]);

        $this->newCategory = '';
        $this->categories = Category::all();
        $this->dispatch('categoryAdded');
    }

    public function markDone($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->done = true;
            $task->save();
        }

        $this->editId = null;
        $this->editTitle = '';
    }

    public function startEdit($id)
    {
        $task = Task::find($id);
        $this->editId = $id;
        $this->editTitle = $task->title ?? '';
    }

    public function saveEdit()
    {
        if ($this->editId && $this->editTitle !== '') {
            $task = Task::find($this->editId);
            $task->title = $this->editTitle;
            $task->save();

            $this->editId = null;
            $this->editTitle = '';
        }
    }

    public function deleteTask($id)
    {
        Task::destroy($id);
    }

    public function loadMorePage()
    {
        // $this->loadingMore = true;
        // usleep(500000);
        $this->page += 5;
        // $this->loadingMore = false;
    }

    public function showTaskDetail($id)
    {
        $task = Task::find($id);
        if ($task) {
            $this->selectedTask = $task;
            $this->title = $task->title;
            $this->priority = $task->priority;
            $this->due_date = $task->due_date;
            $this->category = $task->category;
            $this->detail_agenda = $task->detail_agenda;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedTask = null;
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

            $task = Task::find($this->selectedTask->id);
            if ($task) {
                $task->update([
                    'title' => $this->title,
                    'priority' => $this->priority,
                    'due_date' => $this->due_date ?: null,
                    'category' => $this->category ?: null,
                    'detail_agenda' => $this->detail_agenda ?: null,
                ]);
            }

            $this->closeModal();
        }
    }

    public function render()
    {
        // return view('livewire.action-plan', [
        //     'tasks' => Task::where('done', false)
        //         ->latest()
        //         ->take($this->page)
        //         ->get(),
        //     'totalTasks' => Task::where('done', false)->count()
        // ]);
        return view('livewire.action-plan');
    }
}
