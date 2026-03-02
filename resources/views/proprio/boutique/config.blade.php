@extends('layouts.auth')

@section('title', 'Configuration - Boutique')

@section('content')
<div class="container mt-5">
    <h3>Finaliser la configuration de votre boutique</h3>

    <form method="POST"
          action="{{ route('proprietaire.boutique.configuration.update', $boutique->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nom de votre boutique</label>
            <input type="text" name="nom" class="form-control"
                   value="{{ old('nom', $boutique->nom) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control"
                   value="{{ old('slug', $boutique->slug) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $boutique->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">
            Enregistrer
        </button>
    </form>
</div>
@endsection
