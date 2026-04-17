<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'completed', 'user_id', 'start_date', 'due_date', 'priority'];

    protected $casts = [
        'completed'  => 'boolean',
        'start_date' => 'date',
        'due_date'   => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function priorityConfig(): array
    {
        return match ($this->priority) {
            'low'    => ['label' => 'Baixa',   'color' => 'bg-gray-400',  'icon' => '🔽'],
            'medium' => ['label' => 'Média',   'color' => 'bg-blue-500',  'icon' => '🔸'],
            'high'   => ['label' => 'Alta',    'color' => 'bg-orange-500', 'icon' => '🔺'],
            'urgent' => ['label' => 'Urgente', 'color' => 'bg-red-600',   'icon' => '🚨'],
            default  => ['label' => 'Média',   'color' => 'bg-blue-500',  'icon' => '🔸'],
        };
    }
}
