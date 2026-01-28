@extends('layouts.auth')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* ==== Couleur principale ==== */
    :root {
        --yellow-main: #f4c542;       
        --yellow-main-hover: #d9a92c; 
    }
    
    /* ==== Boutons personnalisés ==== */
    .btn-dark {
        background-color: var(--yellow-main) !important;
        border-color: var(--yellow-main) !important;
        color: #000 !important;
        font-weight: 600;
    }
    
    .btn-dark:hover {
        background-color: var(--yellow-main-hover) !important;
        border-color: var(--yellow-main-hover) !important;
        color: #000 !important;
    }
    
    /* Outline version */
    .btn-outline-dark {
        border-color: var(--yellow-main) !important;
        color: var(--yellow-main) !important;
    }
    
    .btn-outline-dark:hover {
        background-color: var(--yellow-main) !important;
        color: #000 !important;
    }
    
    /* ==== Inputs ==== */
    .form-control {
        border: 2px solid #ccc;
        transition: .25s ease;
    }
    
    .form-control:hover {
        border-color: var(--yellow-main);
    }
    
    .form-control:focus {
        border-color: var(--yellow-main);
        box-shadow: 0 0 0 0.2rem rgba(244, 197, 66, 0.25);
    }
    
    /* ==== Checkbox (conditions) ==== */
    .form-check-input:checked {
        background-color: var(--yellow-main) !important;
        border-color: var(--yellow-main) !important;
    }
    
    .form-check-input:focus {
        border-color: var(--yellow-main) !important;
        box-shadow: 0 0 0 0.2rem rgba(244, 197, 66, 0.25);
    }

    /* Marges automatiques sur petits écrans */
    @media (max-width: 991px) { 
        .auth-box {
            margin: 20px;
            border-radius: 12px;
        }
    }

</style>
    
<section class="py-5" style="background-color:#e5f0ff; min-height:100vh; display:flex; align-items:center;">
    <div class="container">
        <div class="row bg-white shadow rounded overflow-hidden auth-box" style="min-height:500px;">


            <!-- Colonne gauche -->
            <div class="col-lg-6 p-5">
                <h2 class="text-center mb-1">Créer un compte</h2>
                <p class="text-center text-muted mb-4">Remplissez les informations suivantes pour vous inscrire</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="m-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nom -->
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Nom" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Prénom -->
                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="surname"
                               class="form-control @error('surname') is-invalid @enderror"
                               value="{{ old('surname') }}" placeholder="Prénom" required>
                        @error('surname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="Email professionnel">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Téléphone -->
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="number" name="telephone"
                               class="form-control @error('telephone') is-invalid @enderror"
                               value="{{ old('telephone') }}" placeholder="Votre numéro" required>
                        @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mot de passe" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Confirmation -->
                    <div class="mb-3">
                        <label class="form-label">Confirmer mot de passe</label>
                        <input type="password" name="password_confirmation"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               placeholder="Confirmez le mot de passe" required>
                        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Conditions -->
                    <div class="form-check mb-3">
                        <input class="form-check-input @error('terms') is-invalid @enderror"
                               type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les conditions d'utilisation
                        </label>
                        @error('terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Photo -->
                    <div class="mb-3">
                        <label class="form-label">Photo de profil (optionnelle)</label>
                        <input type="file" name="profil"
                               class="form-control @error('profil') is-invalid @enderror"
                               accept="image/*">
                        @error('profil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Boutons -->
                    <button class="btn btn-dark w-100 mb-2" type="submit">S'inscrire</button>

                    <a href="{{ route('login') }}" class="btn btn-outline-dark w-100">
                        Se connecter
                    </a>
                </form>
            </div>

            <!-- Colonne droite -->
            <div class="col-lg-6 p-0 d-none d-lg-block">
                <img src="{{ asset('assets/images/register.jpg') }}" class="w-100 h-100" style="">
            </div>

        </div>
    </div>
</section>

@endsection
