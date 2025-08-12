<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'priority',
        'done',
        'due_date',
        'categoty',
        'detail_agenda',

    ];
}
