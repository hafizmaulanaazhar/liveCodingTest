<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'priority',
        'done',
        'due_date',
        'category',
        'detail_agenda',

    ];

    public static function baseQuery($filters = [])
    {
        return self::when(isset($filters['done']), function ($query) use ($filters) {
            return $query->where('done', $filters['done']);
        })
            ->when(isset($filters['category']), function ($query) use ($filters) {
                return $query->where('category', $filters['category']);
            })
            ->when(isset($filters['today']), function ($query) {
                return $query->whereDate('due_date', Carbon::today());
            })
            ->when(isset($filters['next7days']), function ($query) {
                return $query->whereBetween('due_date', [Carbon::tomorrow(), Carbon::today()->addDays(7)]);
            });
    }
}
