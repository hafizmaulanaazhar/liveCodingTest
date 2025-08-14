<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Task;
use Carbon\Carbon;

class Sidebar extends Component
{
    public $activeFilter = 'today';

    public function setFilter($filter)
    {
        $this->activeFilter = $filter;
        $this->emit('filterChanged', $filter);
    }

    public function getTodayCountProperty()
    {
        return Task::whereDate('due_date', Carbon::today())
            ->where('done', 0)
            ->count();
    }

    public function getNext7DaysCountProperty()
    {
        return Task::whereBetween('due_date', [Carbon::tomorrow(), Carbon::today()->addDays(7)])
            ->where('done', 0)
            ->count();
    }

    public function getCategoriesProperty()
    {
        return Task::select('category')
            ->selectRaw('COUNT(*) as total')
            ->where('done', 0)
            ->groupBy('category')
            ->get();
    }

    public function getDoneCountProperty()
    {
        return Task::where('done', 1)->count();
    }

    public function render()
    {
        return view('livewire.sidebar', [
            'tasks' => Task::latest()->get()
        ]);
    }
}
