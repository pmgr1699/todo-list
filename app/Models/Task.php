<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'completed', 'user_id'];

    protected $casts = [
        'completed' => 'boolean',
    ];

    // Relação com o utilizador
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
