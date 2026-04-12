@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">As minhas tarefas</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Nova tarefa</a>
    </div>

    @forelse ($tasks as $task)
    <div class="card mb-2 {{ $task->completed ? 'border-success' : '' }}">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-0 {{ $task->completed ? 'text-decoration-line-through text-muted' : '' }}">
                    {{ $task->title }}
                </h5>
                @if ($task->description)
                    <small class="text-muted">{{ $task->description }}</small>
                @endif
            </div>

            <div class="d-flex gap-2">

                {{-- Botão de conclusão rápida --}}
                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm {{ $task->completed ? 'btn-success' : 'btn-outline-success' }}">
                        {{ $task->completed ? '✅ Concluída' : '⬜ Concluir' }}
                    </button>
                </form>

                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">
                    ✏️ Editar
                </a>

                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                      onsubmit="return confirm('Tens a certeza?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">🗑 Apagar</button>
                </form>

            </div>
        </div>
    </div>
    @empty
        <div class="alert alert-info">Ainda não tens tarefas. Cria a primeira!</div>
    @endforelse

    <div class="mt-3">
        {{ $tasks->links() }}
    </div>
@endsection