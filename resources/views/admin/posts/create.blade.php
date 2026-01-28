@extends('layouts.admin')

@section('title', 'Nouvel article')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">Créer un nouvel article</h3>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">← Retour</a>
    </div>

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="titre" class="form-label fw-semibold">Titre</label>
            <input type="text" name="titre" id="titre" value="{{ old('titre') }}" class="form-control" required>
            @error('titre') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="categorie_id" class="form-label fw-semibold">Catégorie</label>
            <select name="categorie_id" id="categorie_id" class="form-select">
                <option value="" selected disabled>-- Aucune catégorie --</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
            @error('categorie_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="resume" class="form-label fw-semibold">Résumé</label>
            <textarea name="resume" id="resume" class="form-control" rows="3">{{ old('resume') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="contenu" class="form-label fw-semibold">Contenu</label>
            <textarea name="contenu" id="contenu" class="form-control" rows="7" required>{{ old('contenu') }}</textarea>
            @error('contenu') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label fw-semibold">Image de couverture</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="publie" id="publie" value="1" checked>
            <label class="form-check-label" for="publie">Publier cet article</label>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success px-4">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
