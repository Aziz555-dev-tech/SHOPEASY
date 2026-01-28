@extends('layouts.auth')

@section('title', 'Changer mot de passe')

@section('content')
<div class="container mt-5">
    <h3>Changer votre mot de passe</h3>
    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <input id="password" type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmer mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
    </form>
</div>
@endsection
