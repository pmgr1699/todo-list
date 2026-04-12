@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">🗑 Lixeira</h1>
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">← Voltar</a>
    </div>

    @forelse ($tasks as $task)
        <div class="card mb-2 border-danger">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-decoration-line-through text-muted">
                        {{ $task->title }}
                    </h5>
                    @if ($task->description)
                        <small class="text-muted">{{ $task->description }}</small>
                    @endif
                    <br>
                    <small class="text-danger">
                        Eliminada em {{ $task->deleted_at->format('d/m/Y H:i') }}
                    </small>
                </div>

                <form action="{{ route('tasks.restore', $task->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm btn-outline-success">♻️ Restaurar</button>
                </form>
            </div>
        </div>
    @empty
        <div class="alert alert-info">A lixeira está vazia.</div>
    @endforelse

    <div class="mt-3">{{ $tasks->links() }}</div>
@endsection
