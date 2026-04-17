@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">As minhas tarefas</h1>
            <p class="text-sm text-gray-400 mt-1">
                {{ $tasks->where('completed', false)->count() }} pendentes ·
                {{ $tasks->where('completed', true)->count() }} concluídas
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tasks.trashed') }}"
                class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600">
                Lixeira
            </a>
            <a href="{{ route('tasks.create') }}"
                class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                + Nova tarefa
            </a>
        </div>
    </div>

    @forelse ($tasks as $task)
        @php
            $priority = $task->priorityConfig();
            $borderColor = match ($task->priority) {
                'urgent' => '#E24B4A',
                'high' => '#EF9F27',
                'medium' => '#378ADD',
                'low' => '#888780',
                default => '#888780',
            };
        @endphp

        <div class="bg-white rounded-xl border border-gray-200 mb-3 overflow-hidden {{ $task->completed ? 'opacity-60' : '' }}"
            style="border-left: 3px solid {{ $borderColor }}">
            <div class="p-5 flex justify-between items-start gap-4">

                {{-- Conteúdo principal --}}
                <div class="flex-1 min-w-0">

                    {{-- Prioridade e labels --}}
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $priority['color'] }} text-white">
                            {{ $priority['icon'] }} {{ $priority['label'] }}
                        </span>
                        @foreach ($task->labels as $label)
                            <span class="px-2 py-0.5 rounded-full text-xs text-white"
                                style="background-color: {{ $label->color }}">
                                {{ $label->name }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Título --}}
                    <p
                        class="text-base font-semibold mb-1 {{ $task->completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                        {{ $task->title }}
                    </p>

                    {{-- Descrição --}}
                    @if ($task->description)
                        <p class="text-sm text-gray-500 mb-3">{{ $task->description }}</p>
                    @endif

                    {{-- Separador --}}
                    <div class="border-t border-gray-100 my-3"></div>

                    {{-- Datas e countdown --}}
                    <div class="flex gap-4 flex-wrap text-xs text-gray-400">
                        @if ($task->start_date)
                            <span>📅 Início: {{ $task->start_date->format('d/m/Y') }}</span>
                        @endif
                        @if ($task->due_date)
                            <span data-countdown="{{ $task->due_date->format('Y-m-d') }}"
                                data-completed="{{ $task->completed ? 'true' : 'false' }}"
                                class="countdown-badge font-medium px-2 py-0.5 rounded-full text-white text-xs">
                            </span>
                        @endif
                    </div>

                </div>

                {{-- Ações --}}
                <div class="flex flex-col gap-2 flex-shrink-0">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button
                            class="w-full px-3 py-1.5 text-xs rounded-lg border
                            {{ $task->completed
                                ? 'bg-green-50 border-green-300 text-green-700'
                                : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                            {{ $task->completed ? '✅ Concluída' : '⬜ Concluir' }}
                        </button>
                    </form>

                    <a href="{{ route('tasks.edit', $task) }}"
                        class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 text-center">
                        ✏️ Editar
                    </a>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                        onsubmit="return confirm('Tens a certeza?')">
                        @csrf
                        @method('DELETE')
                        <button
                            class="w-full px-3 py-1.5 text-xs rounded-lg border border-red-200 text-red-500 hover:bg-red-50">
                            🗑 Apagar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
            Ainda não tens tarefas. Cria a primeira!
        </div>
    @endforelse

    <div class="mt-4">{{ $tasks->links() }}</div>
@endsection
