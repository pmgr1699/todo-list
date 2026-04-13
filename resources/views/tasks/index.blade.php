@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">As minhas tarefas</h1>
        <div class="flex gap-2">
            <a href="{{ route('tasks.trashed') }}"
                class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 text-gray-600">
                🗑 Lixeira
            </a>
            <a href="{{ route('tasks.create') }}" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                + Nova tarefa
            </a>
        </div>
    </div>

    @forelse ($tasks as $task)
        <div
            class="bg-white rounded-lg shadow-sm border {{ $task->completed ? 'border-green-300' : 'border-gray-200' }} mb-3 p-4 flex justify-between items-center">
            <div>
                <h5 class="font-medium {{ $task->completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                    {{ $task->title }}
                </h5>

                @if ($task->labels->count())
                    <div class="flex flex-wrap gap-1 mt-1">
                        @foreach ($task->labels as $label)
                            <span class="px-2 py-0.5 rounded-full text-xs text-white"
                                style="background-color: {{ $label->color }}">
                                {{ $label->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                @if ($task->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                @endif
            </div>

            <div class="flex gap-2">
                {{-- Conclusão rápida --}}
                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button
                        class="px-3 py-1 text-sm rounded border {{ $task->completed ? 'bg-green-500 text-white border-green-500' : 'border-green-500 text-green-600 hover:bg-green-50' }}">
                        {{ $task->completed ? '✅ Concluída' : '⬜ Concluir' }}
                    </button>
                </form>

                <a href="{{ route('tasks.edit', $task) }}"
                    class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    ✏️ Editar
                </a>

                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                    onsubmit="return confirm('Tens a certeza?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 text-sm border border-red-300 rounded text-red-600 hover:bg-red-50">
                        🗑 Apagar
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
            Ainda não tens tarefas. Cria a primeira!
        </div>
    @endforelse

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
@endsection
