<?php

namespace App\Queries;

use App\Models\Task;
use Carbon\Carbon;

class SidebarQuery
{
    public static function countTodayTasks()
    {
        return Task::whereDate('due_date', Carbon::today())
            ->where('done', false)
            ->count();
    }

    public static function countNext7DaysTasks()
    {
        return Task::whereBetween('due_date', [Carbon::tomorrow(), Carbon::today()->addDays(7)])
            ->where('done', false)
            ->count();
    }

    public static function countMainJobTasks()
    {
        return Task::where('category', 'Main Job')
            ->where('done', false)
            ->count();
    }

    public static function countSideJobTasks()
    {
        return Task::where('category', 'Side Job')
            ->where('done', false)
            ->count();
    }

    public static function countDoneTasks()
    {
        return Task::where('done', true)->count();
    }

    public static function getCategories()
    {
        return Task::select('category')
            ->selectRaw('COUNT(*) as total')
            ->where('done', false)
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();
    }
}
