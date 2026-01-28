@extends('layouts.admin')

@section('content')
<form action="{{ route('admin.biens.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="modal-body">

        <!-- TITRE -->
        <div class="mb-3">
            <label for="titre" class="form-label">Titre du bien</label>
            <input type="text" name="titre" id="titre" class="form-control" required>
        </div>

        <!-- DESCRIPTION -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <!-- PROPRIETAIRE -->
        <div class="mb-3">
            <label for="proprietaire_id" class="form-label">Propriétaire</label>
            <select name="proprietaire_id" id="proprietaire_id" class="form-select" required>
                <option value="" disabled selected>-- Sélectionnez un propriétaire --</option>
                @foreach($proprietaires as $proprio)
                    <option value="{{ $proprio->id }}">{{ $proprio->name ." ". $proprio->surname }}</option>
                @endforeach
            </select>
        </div>

        <select id="categorySelect" name="categorie_id" class="form-control">
          <option value="">-- Choisir une catégorie --</option>
          @foreach ($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
          @endforeach
        </select>
        
        <select id="sousCategorySelect" name="sous_categorie_id" class="form-control" disabled>
            <option value="">-- Sélectionnez une catégorie d'abord --</option>
        </select>
        
        <select id="subTypeSelect" name="sub_type_id" class="form-control" disabled>
            <option value="">-- Sélectionnez une sous-catégorie d'abord --</option>
        </select>
                                               
      
      <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="number" step="0.01" name="prix" id="prix" class="form-control">
      </div>

      <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" step="1" name="stock" id="stock" class="form-control">
      </div>

      <div class="mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse') }}" required>
        @error('adresse')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
      
        <!-- MEDIA -->
        <div class="mb-3">
            <label for="medias" class="form-label">Médias du bien (images / vidéos)</label>
            <input type="file" name="medias[]" id="medias" class="form-control" accept="image/*,video/*" multiple>
            <small class="text-muted">Formats acceptés : jpg, png, mp4...</small>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.biens.index') }}" class="btn btn-secondary">⬅ Annuler</a>
    </div>

</form>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    
        const categorySelect = document.getElementById('categorySelect');
        const sousCategorySelect = document.getElementById('sousCategorySelect');
        const subTypeSelect = document.getElementById('subTypeSelect');
    
        // Quand une catégorie change => charger sous catégories
        categorySelect.addEventListener('change', async function () {
            const categoryId = this.value;
    
            sousCategorySelect.innerHTML = '<option value="">Chargement...</option>';
            subTypeSelect.innerHTML = '<option value="">-- Sélectionnez une sous-catégorie d\'abord --</option>';
            subTypeSelect.disabled = true;
    
            if (!categoryId) return;
    
            const response = await fetch(`/api/categories/${categoryId}/souscategories`);
            const data = await response.json();
    
            sousCategorySelect.innerHTML = `<option value="">-- Choisir --</option>`;
    
            data.forEach(item => {
                sousCategorySelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
    
            sousCategorySelect.disabled = false;
        });
    
        // Quand une sous catégorie change => charger types
        sousCategorySelect.addEventListener('change', async function () {
            const sousCategoryId = this.value;
    
            subTypeSelect.innerHTML = '<option value="">Chargement...</option>';
    
            if (!sousCategoryId) return;
    
            const response = await fetch(`/api/souscategories/${sousCategoryId}/subtypes`);
            const data = await response.json();
    
            subTypeSelect.innerHTML = `<option value="">-- Choisir --</option>`;
    
            data.forEach(item => {
                subTypeSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
    
            subTypeSelect.disabled = false;
        });
    
    });
  </script>
@endsection