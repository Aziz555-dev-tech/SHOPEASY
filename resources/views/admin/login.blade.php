@extends('layouts.auth')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* ==== Couleur principale ==== */
    :root {
        --yellow-main: #f4c542;       
        --yellow-main-hover: #d9a92c; 
        --blue-main: #005078;
    }

    /* ==== Boutons personnalisés ==== */
    .btn-dark {
        background-color: var(--yellow-main) !important;
        border-color: var(--yellow-main) !important;
        color: #000 !important;
        font-weight: 600;
    }

    .btn-primary {
        background-color: var(--yellow-main) !important;
        border-color: var(--yellow-main) !important;
        color: #f4c542 !important;
        font-weight: 600;
    }

    .btn-dark:hover {
        background-color: var(--yellow-main-hover) !important;
        border-color: var(--yellow-main-hover) !important;
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

    /* ==== Checkbox ==== */
    .form-check-input:checked {
        background-color: var(--yellow-main) !important;
        border-color: var(--yellow-main) !important;
    }

    /* Marges sur petits écrans */
    @media (max-width: 991px) {
        .auth-box {
            margin: 20px !important;
            border-radius: 12px;
        }
    }
</style>

@section('content')
<section class="login-section py-5" style="background-color:#e5f0ff; min-height:100vh; display:flex; align-items:center;">
    <div class="container">
        <div class="row justify-content-center shadow rounded auth-box bg-white" style="overflow:hidden;">

            <!-- Zone gauche -->
            <div class="col-lg-6 p-5">
                <h2 class="text-center mb-3" style="font-weight:600; color:var(--blue-main);">
                    Connexion Administrateur
                </h2>
                <p class="text-center text-muted mb-4">
                    Entrez vos informations pour vous connecter
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="m-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="Email professionnel" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mot de passe" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>

                    {{-- Bouton --}}
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-dark" style="background-color:var(--yellow-main) !important; border-color:var(--blue-main) !important;">
                            Se connecter
                        </button>
                    </div>

                    <div class="text-center">
                        {{-- <a href="{{ route('admin.password.change') }}" class="text-primary">
                            Mot de passe oublié ?
                        </a> --}}
                    </div>
                </form>
            </div>

            <!-- Zone droite -->
            <div class="col-lg-6 p-0 d-none d-lg-block">
                <img src="{{ asset('assets/images/admin.jpeg') }}"
                     alt="Illustration Admin"
                     style="width:100%; height:100%; object-fit:cover;">
            </div>

        </div>
    </div>
</section>
@endsection
