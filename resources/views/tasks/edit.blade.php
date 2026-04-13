@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Editar Tarefa</h2>

        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="title"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('title') ? 'border-red-400' : '' }}"
                    value="{{ old('title', $task->title) }}">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('description') ? 'border-red-400' : '' }}">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" name="completed" id="completed" class="w-4 h-4 text-blue-600"
                    {{ $task->completed ? 'checked' : '' }}>
                <label for="completed" class="text-sm text-gray-700">Marcar como concluída</label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Atualizar
                </button>
                <a href="{{ route('tasks.index') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-600 text-sm rounded hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
