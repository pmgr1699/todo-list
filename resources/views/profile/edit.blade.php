@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto space-y-6">

        {{-- Atualizar informação do perfil --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informação do Perfil</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" name="name"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('email', $user->email) }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        Guardar
                    </button>
                    @if (session('status') === 'profile-updated')
                        <span class="text-sm text-green-600">Guardado!</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Alterar password --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Alterar Password</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password atual</label>
                    <input type="password" name="current_password"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nova password</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar nova password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        Atualizar password
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="text-sm text-green-600">Atualizada!</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Eliminar conta --}}
        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
            <h2 class="text-lg font-bold text-red-700 mb-2">Eliminar Conta</h2>
            <p class="text-sm text-gray-500 mb-4">
                Ao eliminar a conta todos os dados serão apagados permanentemente.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}"
                onsubmit="return confirm('Tens a certeza? Esta ação é irreversível.')">
                @csrf
                @method('DELETE')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Confirma a tua password para continuar
                    </label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('password', 'userDeletion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                    Eliminar conta
                </button>
            </form>
        </div>

    </div>
@endsection
