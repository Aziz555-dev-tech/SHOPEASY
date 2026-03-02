@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">


@section('content')

    <!-- Section utilisateurs -->
    
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger">
                {{ session('danger') }}
            </div>
        @endif

        <div class="card-header bg-primary text-white">Utilisateurs récents</div>
        <div class="card-body">
            @if($users->count() > 0)
                <table class="table table-hover table-sm table-middle">
                    <thead class="table-primary">
                        <tr class="justify-content-between">
                            <td><strong>Nom</strong></td>
                            <td><strong>Prénom</strong></td>
                            <td><strong>Email</strong></td>
                            <td><strong>Téléphone</strong></td>
                            <td><strong>Role</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="justify-content-between">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->surname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telephone }}</td>
                            <td>{{ $user->role }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <img src="/images/empty-users.png" alt="Aucun utilisateur">
                    <p>Aucun utilisateur pour l’instant</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Styles de base de la table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            min-width: 600px; /* pour éviter que la table ne se rétrécisse trop */
        }

        thead tr {
            background-color: #007BFF;
            color: white;
            text-align: left;
        }

        thead td {
            padding: 10px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }

        tbody tr:hover {
            background-color: rgba(0, 80, 120, 0.6);
            color: #000;
            font-weight: bold;
            cursor: pointer;
        }

        tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid #dee2e6;
        }

        /* Container responsive pour la table */
        .card-body {
            overflow-x: auto; /* ajout du scroll horizontal si écran trop petit */
        }

        /* Amélioration responsive pour petits écrans */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
            thead td, tbody td {
                padding: 6px 8px;
            }
        }

    </style>

@endsection