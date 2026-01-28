@extends('layouts.admin')

@section('title', 'Changer mot de passe')

@section('content')
<div class="max-w-md mx-auto mt-12 p-6 bg-[#FAF9F6] shadow-lg rounded-xl border border-[#005078]">
    <h2 class="text-2xl font-bold text-center text-[#005078] mb-6">
        Changement du mot de passe
    </h2>

    @if ($errors->any())
        <div class="mb-4 text-red-700">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#005078]">Mot de passe actuel</label>
            <input type="password" name="current_password" required
                class="w-full border rounded-lg p-2 mt-1 focus:ring-[#912838] focus:border-[#912838]">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#005078]">Nouveau mot de passe</label>
            <input type="password" name="new_password" required
                class="w-full border rounded-lg p-2 mt-1 focus:ring-[#912838] focus:border-[#912838]">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#005078]">Confirmer le nouveau mot de passe</label>
            <input type="password" name="new_password_confirmation" required
                class="w-full border rounded-lg p-2 mt-1 focus:ring-[#912838] focus:border-[#912838]">
        </div>

        <button type="submit"
            class="w-full py-2 px-4 bg-[#005078] text-white rounded-lg hover:bg-[#912838]">
            Mettre à jour
        </button>
    </form>
</div>
@endsection
