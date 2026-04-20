@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Cabeçalho --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">As minhas tarefas</h1>
                <p class="text-sm text-gray-400 mt-1">
                    {{ $tasks->where('completed', false)->count() }} pendentes ·
                    {{ $tasks->where('completed', true)->count() }} concluídas
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tasks.trashed') }}"
                    class="px-4 py-2 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                    Lixeira
                </a>
                <a href="{{ route('tasks.create') }}"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    + Nova tarefa
                </a>
            </div>
        </div>

        {{-- Barra de filtros --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
            <form method="GET" action="{{ route('tasks.index') }}">
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-4">

                    {{-- Estado --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Estado</label>
                        <select name="status"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Todas</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendentes
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluídas
                            </option>
                        </select>
                    </div>

                    {{-- Prioridade --}}
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Prioridade</label>
                        <select name="priority"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Todas</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🚨 Urgente
                            </option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>🔺 Alta</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>🔸 Média
                            </option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>🔽 Baixa
                            </option>
                        </select>
                    </div>

                    {{-- Label --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Label</label>
                        <select name="label"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Todas</option>
                            @foreach ($labels as $label)
                                <option value="{{ $label->id }}"
                                    {{ request('label') == $label->id ? 'selected' : '' }}>
                                    {{ $label->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Deadline --}}
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Deadline</label>
                        <select name="deadline"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Todas</option>
                            <option value="overdue" {{ request('deadline') === 'overdue' ? 'selected' : '' }}>Atrasadas
                            </option>
                            <option value="today" {{ request('deadline') === 'today' ? 'selected' : '' }}>Hoje
                            </option>
                            <option value="this_week" {{ request('deadline') === 'this_week' ? 'selected' : '' }}>Esta
                                semana</option>
                        </select>
                    </div>

                    {{-- Data de início --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Data de
                            início</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- Ordenação --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Ordenar
                            por</label>
                        <select name="sort"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="priority" {{ request('sort', 'priority') === 'priority' ? 'selected' : '' }}>
                                🔺 Prioridade</option>
                            <option value="start_date" {{ request('sort') === 'start_date' ? 'selected' : '' }}>📅 Data de
                                início
                            </option>
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>🔤
                                Título (A-Z)</option>
                            <option value="completed" {{ request('sort') === 'completed' ? 'selected' : '' }}>
                                ✅ Estado</option>
                        </select>
                    </div>

                </div>

                {{-- Filtros ativos + botões --}}
                <div class="flex justify-between items-center">

                    <div class="flex gap-2 flex-wrap">
                        @if (request('status'))
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">
                                {{ request('status') === 'pending' ? 'Pendentes' : 'Concluídas' }}
                            </span>
                        @endif
                        @if (request('priority'))
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">
                                {{ ['urgent' => '🚨 Urgente', 'high' => '🔺 Alta', 'medium' => '🔸 Média', 'low' => '🔽 Baixa'][request('priority')] }}
                            </span>
                        @endif
                        @if (request('label'))
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">
                                {{ $labels->firstWhere('id', request('label'))?->name }}
                            </span>
                        @endif
                        @if (request('deadline'))
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">
                                {{ ['overdue' => 'Atrasadas', 'today' => 'Hoje', 'this_week' => 'Esta semana'][request('deadline')] }}
                            </span>
                        @endif
                        @if (request('start_date'))
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">
                                Início: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                            </span>
                        @endif
                        @if (request('sort') && request('sort') !== 'priority')
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-purple-50 text-purple-600">
                                Ordem:
                                {{ ['start_date' => '📅 Data de início', 'title' => '🔤 Título (A-Z)', 'completed' => '✅ Estado'][request('sort')] }}
                            </span>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        @if (request()->hasAny(['status', 'priority', 'label', 'deadline', 'start_date']))
                            <a href="{{ route('tasks.index') }}"
                                class="px-4 py-2 text-sm border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50">
                                Limpar filtros
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Filtrar
                        </button>
                    </div>

                </div>
            </form>
        </div>

        {{-- Lista de tarefas --}}
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
                $priorityBg = match ($task->priority) {
                    'urgent' => '#FCEBEB',
                    'high' => '#FAEEDA',
                    'medium' => '#E6F1FB',
                    'low' => '#F1EFE8',
                    default => '#E6F1FB',
                };
                $priorityText = match ($task->priority) {
                    'urgent' => '#A32D2D',
                    'high' => '#854F0B',
                    'medium' => '#185FA5',
                    'low' => '#5F5E5A',
                    default => '#185FA5',
                };
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 mb-3 overflow-hidden {{ $task->completed ? 'opacity-60' : '' }}"
                style="border-left: 3px solid {{ $borderColor }}">
                <div class="p-5 flex justify-between items-start gap-4">

                    <div class="flex-1 min-w-0">

                        {{-- Prioridade e labels --}}
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                style="background: {{ $priorityBg }}; color: {{ $priorityText }}">
                                {{ $priority['icon'] }} {{ $priority['label'] }}
                            </span>
                            @foreach ($task->labels as $label)
                                <span class="px-2 py-0.5 rounded-full text-xs"
                                    style="background-color: {{ $label->color }}; color: {{ $label->textColor() }}">
                                    {{ $label->name }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Título --}}
                        <p class="text-base font-semibold mb-1"
                            style="{{ $task->completed ? 'text-decoration: line-through; color: #9ca3af;' : 'color: #1f2937;' }}">
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
                            <button class="w-full px-3 py-1.5 text-xs rounded-lg border"
                                style="{{ $task->completed
                                    ? 'background:#f0fdf4; border-color:#86efac; color:#15803d;'
                                    : 'border-color:#86efac; color:#16a34a;' }}">
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
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm">
                @if (request()->hasAny(['status', 'priority', 'label', 'deadline', 'start_date']))
                    Nenhuma tarefa encontrada com os filtros selecionados.
                @else
                    Ainda não tens tarefas. Cria a primeira!
                @endif
            </div>
        @endforelse

        <div class="mt-4">{{ $tasks->links() }}</div>

    </div>
@endsection
