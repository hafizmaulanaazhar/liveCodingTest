<?php

namespace App\Queries;

use App\Models\Task;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActionPlanQuery
{
    public static function getTasks(string $filter, int $perPage = 5): LengthAwarePaginator
    {
        $query = Task::query();

        if (str_starts_with($filter, 'category:')) {
            $category = str_replace('category:', '', $filter);
            $query->where('category', $category)
                ->where('done', false);
        } else {
            switch ($filter) {
                case 'today':
                    $query->whereDate('due_date', Carbon::today())
                        ->where('done', false);
                    break;
                case 'next7':
                    $query->whereBetween('due_date', [Carbon::tomorrow(), Carbon::today()->addDays(7)])
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

        return $query->latest()->paginate($perPage);
    }

    public static function countPendingTasks(): int
    {
        return Task::where('done', false)->count();
    }

    public static function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public static function markTaskDone(int $id): ?Task
    {
        $task = Task::find($id);
        if ($task) {
            $task->done = true;
            $task->save();
        }
        return $task;
    }

    public static function updateTaskTitle(int $id, string $title): ?Task
    {
        $task = Task::find($id);
        if ($task) {
            $task->title = $title;
            $task->save();
        }
        return $task;
    }

    public static function deleteTask(int $id): void
    {
        Task::destroy($id);
    }

    public static function updateTaskDetail(int $id, array $data): ?Task
    {
        $task = Task::find($id);
        if ($task) {
            $task->update($data);
        }
        return $task;
    }
}
