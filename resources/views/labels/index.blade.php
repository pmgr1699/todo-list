@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🏷 Etiquetas</h1>
        <a href="{{ route('tasks.index') }}"
            class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 text-gray-600">
            ← Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Nova Etiqueta</h2>
        <form action="{{ route('labels.store') }}" method="POST" class="flex gap-3 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
                <input type="color" name="color" value="{{ old('color', '#3B82F6') }}"
                    class="h-9 w-16 border border-gray-300 rounded cursor-pointer">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                Criar
            </button>
        </form>
    </div>

    {{-- Lista de labels --}}
    @forelse ($labels as $label)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-3 p-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="w-5 h-5 rounded-full inline-block" style="background-color: {{ $label->color }}"></span>
                <span class="font-medium text-gray-800">{{ $label->name }}</span>
                <span class="text-xs text-gray-400">{{ $label->tasks_count }} tarefa(s)</span>
            </div>

            <div class="flex gap-2">
                {{-- Editar inline --}}
                <form action="{{ route('labels.update', $label) }}" method="POST" class="flex gap-2 items-center">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $label->name }}"
                        class="border border-gray-300 rounded px-2 py-1 text-sm w-32 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="color" name="color" value="{{ $label->color }}"
                        class="h-8 w-10 border border-gray-300 rounded cursor-pointer">
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                        Guardar
                    </button>
                </form>

                <form action="{{ route('labels.destroy', $label) }}" method="POST"
                    onsubmit="return confirm('Eliminar etiqueta?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 text-sm border border-red-300 rounded text-red-600 hover:bg-red-50">
                        🗑
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
            Ainda não tens etiquetas. Cria a primeira!
        </div>
    @endforelse
@endsection
