<?php

namespace App\Queries;

use App\Models\Task;

class SidebarQuery
{
    public static function countTodayTasks()
    {
        return Task::baseQuery(['today' => true, 'done' => false])->count();
    }

    public static function countNext7DaysTasks()
    {
        return Task::baseQuery(['next7days' => true, 'done' => false])->count();
    }

    public static function countMainJobTasks()
    {
        return Task::baseQuery(['category' => 'Main Job', 'done' => false])->count();
    }

    public static function countSideJobTasks()
    {
        return Task::baseQuery(['category' => 'Side Job', 'done' => false])->count();
    }

    public static function countDoneTasks()
    {
        return Task::baseQuery(['done' => true])->count();
    }

    public static function getCategories()
    {
        return Task::baseQuery(['done' => false])
            ->select('category')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();
    }
}
