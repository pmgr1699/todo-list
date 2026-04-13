@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Nova Tarefa</h2>

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="title"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('title') ? 'border-red-400' : '' }}"
                    value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('description') ? 'border-red-400' : '' }}">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if ($labels->count())
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Labels</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($labels as $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="labels[]" value="{{ $label->id }}" class="rounded"
                                    {{ in_array($label->id, old('labels', [])) ? 'checked' : '' }}>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs text-white"
                                    style="background-color: {{ $label->color }}">
                                    {{ $label->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Guardar
                </button>
                <a href="{{ route('tasks.index') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-600 text-sm rounded hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
