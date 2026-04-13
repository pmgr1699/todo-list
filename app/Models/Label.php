<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = ['name', 'color', 'user_id'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
