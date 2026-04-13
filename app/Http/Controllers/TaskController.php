<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks()->latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'nullable|max:1000',
        ]);

        auth()->user()->tasks()->create($request->only('title', 'description'));

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Task $task)
    {
        $this->authorize($task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize($task);

        $request->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'nullable|max:1000',
        ]);

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'completed'   => $request->has('completed'),
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa atualizada!');
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
        $tasks = auth()->user()->tasks()->onlyTrashed()->latest()->paginate(10);
        return view('tasks.trashed', compact('tasks'));
    }

    public function restore($id)
    {
        auth()->user()->tasks()->onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('tasks.trashed')
            ->with('success', 'Tarefa restaurada!');
    }

    // Garante que o utilizador só acede às suas tarefas
    private function authorize(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
