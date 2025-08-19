<?php

namespace App\Livewire;

use Livewire\Component;
use App\Queries\SidebarQuery;

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
        return SidebarQuery::countTodayTasks();
    }

    public function getNext7DaysCountProperty()
    {
        return SidebarQuery::countNext7DaysTasks();
    }

    public function getMainJobProperty()
    {
        return SidebarQuery::countMainJobTasks();
    }

    public function getSideJobProperty()
    {
        return SidebarQuery::countSideJobTasks();
    }

    public function getDoneCountProperty()
    {
        return SidebarQuery::countDoneTasks();
    }

    public function getCategoriesProperty()
    {
        return SidebarQuery::getCategories();
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}
