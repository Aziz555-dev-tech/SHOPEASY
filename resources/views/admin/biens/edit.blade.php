@extends('layouts.admin');

@section('content')
        <form action="{{ route('admin.biens.update', $bien->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
  
            <!-- TITRE -->
            <div class="mb-3">
              <label class="form-label">Titre du bien</label>
              <input type="text" name="titre" class="form-control"
                     value="{{ old('titre', $bien->titre) }}" required>
            </div>
  
            <!-- DESCRIPTION -->
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="4">
                {{ old('description', $bien->description) }}
              </textarea>
            </div>
  
            <!-- PROPRIÉTAIRE -->
            <div class="mb-3">
              <label class="form-label">Propriétaire</label>
              <select name="proprietaire_id" class="form-select" required>
                <option value="" disabled>-- Sélectionnez un propriétaire --</option>
  
                @foreach($proprietaires as $proprio)
                  <option value="{{ $proprio->id }}"
                    {{ $bien->proprietaire_id == $proprio->id ? 'selected' : '' }}>
                      {{ $proprio->name }} {{ $proprio->surname }}
                  </option>
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
  
  
            <!-- PRIX -->
            <div class="mb-3">
              <label class="form-label">Prix (FCFA)</label>
              <input type="number" step="0.01" name="prix"
                     class="form-control"
                     value="{{ old('prix', $bien->prix) }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Stock</label>
              <input type="number" step="1" name="stock"
                     class="form-control"
                     value="{{ old('stock', $bien->stock) }}" required>
            </div>


  
            <div class="mb-3">
              <label class="form-label">Adresse (Facultatif)</label>
              <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $bien->adresse) }}" required>
            </div>
  
            <!-- MEDIAS EXISTANTS -->
            <div class="mb-3">
              <label class="form-label">Médias existants</label>
  
              <div class="d-flex flex-wrap gap-2">
  
                @foreach($bien->medias as $media)
                  <div class="position-relative">
  
                    @if($media->type === 'image')
                      <img src="{{ asset('storage/'.$media->path) }}" width="120" class="img-thumbnail">
                    @elseif($media->type === 'video')
                      <video width="120" controls>
                        <source src="{{ asset('storage/'.$media->path) }}" type="video/mp4">
                      </video>
                    @endif
  
                    <div class="form-check mt-1">
                      <input type="checkbox" name="delete_medias[]" value="{{ $media->id }}"> Supprimer
                    </div>
  
                  </div>
                @endforeach
  
              </div>
  
              <small class="text-muted">Cochez les médias à supprimer.</small>
            </div>
  
            <!-- AJOUTER DES MEDIAS -->
            <div class="mb-3">
              <label class="form-label">Ajouter des médias</label>
              <input type="file" name="medias[]" class="form-control" accept="image/*,video/*" multiple>
            </div>
  
            <!-- VALIDATION -->
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Mettre à jour</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
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