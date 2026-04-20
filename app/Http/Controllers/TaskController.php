<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private function user(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    public function index(Request $request)
    {
        $query = $this->user()->tasks()->with('labels');

        // Filtro de estado
        if ($request->filled('status')) {
            match ($request->status) {
                'pending'   => $query->where('completed', false),
                'completed' => $query->where('completed', true),
                default     => null,
            };
        }

        // Filtro de prioridade
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filtro de label
        if ($request->filled('label')) {
            $query->whereHas(
                'labels',
                fn($q) =>
                $q->where('labels.id', $request->label)
            );
        }

        // Filtro de deadline
        if ($request->filled('deadline')) {
            match ($request->deadline) {
                'overdue'   => $query->where('due_date', '<', today())->where('completed', false),
                'today'     => $query->whereDate('due_date', today()),
                'this_week' => $query->whereBetween('due_date', [today(), today()->endOfWeek()]),
                default     => null,
            };
        }

        // Filtro de data de início
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', $request->start_date);
        }

        // Ordenação
        match ($request->input('sort', 'priority')) {
            'priority'   => $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')"),
            'start_date' => $query->orderBy('start_date', $request->input('dir', 'asc')),
            'title'      => $query->orderBy('title', 'asc'),
            'completed'  => $query->orderBy('completed', $request->input('dir', 'asc')),
            default      => $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')"),
        };

        // Secundária: mais recente primeiro em caso de empate
        $query->latest();

        $tasks = $query
            ->paginate(10)
            ->withQueryString();

        $labels = $this->user()->labels()->get();

        return view('tasks.index', compact('tasks', 'labels'));
    }

    public function create()
    {
        $labels = $this->user()->labels()->get();
        return view('tasks.create', compact('labels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'nullable|max:1000',
            'start_date'  => 'nullable|date',
            'due_date'    => 'nullable|date|after_or_equal:start_date',
            'priority'    => 'required|in:low,medium,high,urgent',
            'labels'      => 'nullable|array',
            'labels.*'    => 'exists:labels,id',
        ]);

        $task = $this->user()->tasks()->create([
            ...$request->only('title', 'description', 'due_date', 'priority'),
            'start_date' => $request->filled('start_date')
                ? $request->start_date
                : now()->toDateString(),
        ]);

        $task->labels()->sync($request->input('labels', []));

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize($task);

        $request->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'nullable|max:1000',
            'start_date'  => 'nullable|date',
            'due_date'    => 'nullable|date|after_or_equal:start_date',
            'priority'    => 'required|in:low,medium,high,urgent',
            'labels'      => 'nullable|array',
            'labels.*'    => 'exists:labels,id',
        ]);

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'completed'   => $request->has('completed'),
            'priority'    => $request->priority,
            'start_date'  => $request->filled('start_date')
                ? $request->start_date
                : now()->toDateString(),
            'due_date'    => $request->due_date,
        ]);

        $task->labels()->sync($request->input('labels', []));

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa atualizada!');
    }

    public function edit(Task $task)
    {
        $this->authorize($task);
        $labels = $this->user()->labels()->get();
        return view('tasks.edit', compact('task', 'labels'));
    }


    public function destroy(Task $task)
    {
        $this->authorize($task);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa eliminada!');
    }

    public function toggle(Task $task)
    {
        $this->authorize($task);
        $task->update(['completed' => !$task->completed]);

        return redirect()->route('tasks.index')
            ->with('success', $task->completed ? 'Tarefa concluída!' : 'Tarefa reaberta!');
    }

    public function trashed()
    {
        $tasks = $this->user()->tasks()->onlyTrashed()->latest()->paginate(10);
        return view('tasks.trashed', compact('tasks'));
    }

    public function restore($id)
    {
        $this->user()->tasks()->onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('tasks.trashed')
            ->with('success', 'Tarefa restaurada!');
    }

    private function authorize(Task $task): void
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
