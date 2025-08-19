<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Carbon\Carbon;

class Sidebar extends Component
{
    public $activeFilter = 'today';

    public function setFilter($filter)
    {
        $this->activeFilter = $filter;
        $this->dispatch('filterChanged', filter: $filter);
    }

    public function getTodayCountProperty()
    {
        return Task::whereDate('due_date', Carbon::today())
            ->where('done', false)
            ->count();
    }

    public function getNext7DaysCountProperty()
    {
        return Task::whereBetween('due_date', [Carbon::tomorrow(), Carbon::today()->addDays(7)])
            ->where('done', false)
            ->count();
    }

    public function getMainJobProperty()
    {
        return Task::where('category', 'Main Job')
            ->where('done', false)
            ->count();
    }

    public function getSideJobProperty()
    {
        return Task::where('category', 'Side Job')
            ->where('done', false)
            ->count();
    }

    public function getDoneCountProperty()
    {
        return Task::where('done', true)->count();
    }

    public function getCategoriesProperty()
    {
        return Task::select('category')
            ->selectRaw('COUNT(*) as total')
            ->where('done', false)
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}
