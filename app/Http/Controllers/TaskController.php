<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Listar todas as tarefas
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    // Mostrar formulário de criação
    public function create()
    {
        return view('tasks.create');
    }

    // Guardar nova tarefa
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'nullable|max:1000',
        ]);

        Task::create($request->only('title', 'description'));

        return redirect()->route('tasks.index')
                         ->with('success', 'Tarefa criada com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // Atualizar tarefa existente
    public function update(Request $request, Task $task)
    {
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

    // Apagar tarefa
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
                         ->with('success', 'Tarefa eliminada!');
    }

    public function toggle(Task $task)
    {
        $task->update([
            'completed' => !$task->completed,
        ]);

        return redirect()->route('tasks.index')
                        ->with('success', $task->completed ? 'Tarefa concluída!' : 'Tarefa reaberta!');
    }
}