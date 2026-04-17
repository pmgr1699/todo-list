@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('tasks.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Voltar</a>
            <h1 class="text-2xl font-semibold text-gray-800">Editar tarefa</h1>
        </div>

        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            @include('tasks._form')
            <div class="flex gap-3 justify-end">
                <a href="{{ route('tasks.index') }}"
                    class="px-5 py-2 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Atualizar tarefa
                </button>
            </div>
        </form>

    </div>
@endsection
