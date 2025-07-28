<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class ActionPlan extends Component
{

    public $title = '';
    public $priority = '';
    public $editId = null;
    public $editTitle = '';

    public function mount()
    {
    }

    public function addTask()
    {
        $this->validate([
            'title' => 'required|string',
            'priority' => 'required|string|in:high,medium,low',
        ]);
        if ($this->title && $this->priority) {
            Task::create([
                'title' => $this->title,
                'priority' => $this->priority,
                'done' => false
            ]);
        }

        $this->reset(['title', 'priority']);
        $this->dispatch('formReset');
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

    public function render()
    {
        return view('livewire.action-plan', [
            'tasks' => Task::where('done', false)->get()
        ]);
    }
}
