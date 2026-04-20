@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Labels</h1>
                <p class="text-sm text-gray-400 mt-1">{{ $labels->count() }} labels criadas</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="text-sm text-gray-400 hover:text-gray-600">
                ← Voltar às tarefas
            </a>
        </div>

        {{-- Nova label --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-4">Nova label</p>
            <form action="{{ route('labels.store') }}" method="POST">
                @csrf
                <div class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Nome</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Backend"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent {{ $errors->has('name') ? 'border-red-400' : '' }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Cor</label>
                        <div class="relative">
                            <input type="color" name="color" value="{{ old('color', '#3B82F6') }}" class="sr-only peer"
                                id="color-new">
                            <label for="color-new"
                                class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 text-sm text-gray-600">
                                <span class="w-4 h-4 rounded-full border border-gray-200 inline-block color-preview"
                                    style="background: {{ old('color', '#3B82F6') }}"></span>
                                Escolher cor
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Criar
                    </button>
                </div>
            </form>
        </div>

        {{-- Lista de labels --}}
        @if ($labels->count())
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Labels existentes</p>

                @foreach ($labels as $label)
                    <div
                        class="flex justify-between items-center py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                        {{-- Info --}}
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full flex-shrink-0"
                                style="background-color: {{ $label->color }}"></span>
                            <span class="text-sm font-mediu"
                                style="color: var(--color-text-primary)">{{ $label->name }}</span>
                            <span class="text-xs text-gray-400">{{ $label->tasks_count }} tarefa(s)</span>
                        </div>

                        {{-- Ações --}}
                        <div class="flex items-center gap-2">
                            <form action="{{ route('labels.update', $label) }}" method="POST"
                                class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $label->name }}"
                                    class="border border-gray-200 rounded-lg px-2 py-1.5 text-xs w-28 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                                <div class="relative">
                                    <input type="color" name="color" value="{{ $label->color }}" class="sr-only peer"
                                        id="color-{{ $label->id }}">
                                    <label for="color-{{ $label->id }}"
                                        class="flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 text-xs text-gray-600">
                                        <span class="w-3 h-3 rounded-full border border-gray-200 inline-block color-preview"
                                            style="background: {{ $label->color }}"></span>
                                        Cor
                                    </label>
                                </div>
                                <button
                                    class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                                    Guardar
                                </button>
                            </form>

                            <form action="{{ route('labels.destroy', $label) }}" method="POST"
                                onsubmit="return confirm('Eliminar label?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="px-3 py-1.5 text-xs border border-red-200 rounded-lg text-red-500 hover:bg-red-50">
                                    🗑
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm">
                Ainda não tens labels. Cria a primeira!
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('input[type="color"]').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`);
            const preview = label?.querySelector('.color-preview');

            input.addEventListener('input', () => {
                if (preview) preview.style.background = input.value;
            });
        });
    </script>
@endpush
