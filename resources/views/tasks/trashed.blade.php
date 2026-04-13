@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🗑 Lixeira</h1>
        <a href="{{ route('tasks.index') }}"
            class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 text-gray-600">
            ← Voltar
        </a>
    </div>

    @forelse ($tasks as $task)
        <div class="bg-white rounded-lg shadow-sm border border-red-200 mb-3 p-4 flex justify-between items-center">
            <div>
                <h5 class="font-medium line-through text-gray-400">{{ $task->title }}</h5>
                @if ($task->description)
                    <p class="text-sm text-gray-400 mt-1">{{ $task->description }}</p>
                @endif
                <p class="text-xs text-red-400 mt-1">
                    Eliminada em {{ $task->deleted_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <form action="{{ route('tasks.restore', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button class="px-3 py-1 text-sm border border-green-400 text-green-600 rounded hover:bg-green-50">
                    ♻️ Restaurar
                </button>
            </form>
        </div>
    @empty
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
            A lixeira está vazia.
        </div>
    @endforelse

    <div class="mt-4">{{ $tasks->links() }}</div>
@endsection
