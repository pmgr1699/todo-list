@php $task = $task ?? null; @endphp

{{-- Informação básica --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-4">Informação básica</p>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Título *</label>
        <input type="text" name="title" placeholder="Ex: Corrigir bug de autenticação"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent {{ $errors->has('title') ? 'border-red-400' : '' }}"
            value="{{ old('title', $task->title ?? '') }}">
        @error('title')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Descrição</label>
        <textarea name="description" rows="3" placeholder="Descreve a tarefa em detalhe..."
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-y {{ $errors->has('description') ? 'border-red-400' : '' }}">{{ old('description', $task->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Prioridade --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-4">Prioridade</p>
    <div class="grid grid-cols-4 gap-3">
        @foreach ([
        'low' => ['label' => 'Baixa', 'icon' => '🔽'],
        'medium' => ['label' => 'Média', 'icon' => '🔸'],
        'high' => ['label' => 'Alta', 'icon' => '🔺'],
        'urgent' => ['label' => 'Urgente', 'icon' => '🚨'],
    ] as $value => $config)
            <label class="cursor-pointer">
                <input type="radio" name="priority" value="{{ $value }}" class="sr-only peer"
                    {{ old('priority', $task->priority ?? 'medium') === $value ? 'checked' : '' }}>
                <div
                    class="text-center py-3 px-2 rounded-lg border-2 border-gray-100
                            peer-checked:border-blue-400 peer-checked:bg-blue-50
                            hover:bg-gray-50 transition-all">
                    <div class="text-lg mb-1">{{ $config['icon'] }}</div>
                    <div class="text-xs font-medium text-gray-500">{{ $config['label'] }}</div>
                </div>
            </label>
        @endforeach
    </div>
    @error('priority')
        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>

{{-- Datas --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-4">Datas</p>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">
                Data de início
                <span class="text-gray-400 font-normal">(por defeito: hoje)</span>
            </label>
            <input type="date" name="start_date"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                value="{{ old('start_date', $task?->start_date?->format('Y-m-d') ?? '') }}">
            @error('start_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Deadline</label>
            <input type="date" name="due_date"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                value="{{ old('due_date', $task?->due_date?->format('Y-m-d') ?? '') }}">
            @error('due_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

{{-- Labels --}}
@if (isset($labels) && $labels->count())
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <div class="flex justify-between items-center mb-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Labels</p>
            <a href="{{ route('labels.index') }}" class="text-xs text-blue-500 hover:text-blue-700">
                + Gerir labels
            </a>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach ($labels as $label)
                <label class="cursor-pointer">
                    <input type="checkbox" name="labels[]" value="{{ $label->id }}" class="sr-only peer"
                        {{ in_array($label->id, old('labels', $task ? $task->labels->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs border-2
                             text-gray-500 border-gray-200 transition-all"
                        data-color="{{ $label->color }}"
                        data-text-color="{{ $label->textColor() }}">
                        {{ $label->name }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
@endif

{{-- Concluída (só aparece na edição) --}}
@if ($task?->id)
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="completed" class="w-4 h-4 rounded text-blue-600"
                {{ $task->completed ? 'checked' : '' }}>
            <span class="text-sm font-medium text-gray-600">Marcar como concluída</span>
        </label>
    </div>
@endif

@pushOnce('scripts')
    <script>
        document.querySelectorAll('input[name="labels[]"]').forEach(checkbox => {
            const span = checkbox.nextElementSibling;
            const color = span.dataset.color;
            const textColor = span.dataset.textColor;

            function update() {
                if (checkbox.checked) {
                    span.style.background = color;
                    span.style.borderColor = color;
                    span.style.color = textColor;
                } else {
                    span.style.background = '';
                    span.style.borderColor = '#e5e7eb';
                    span.style.color = '#6b7280';
                }
            }

            update();
            checkbox.addEventListener('change', update);
        });
    </script>
@endPushOnce
