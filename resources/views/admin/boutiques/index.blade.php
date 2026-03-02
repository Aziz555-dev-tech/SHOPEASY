@extends('layouts.admin')

@section('content')
<div class="container">
    <h4 class="mb-3">Gestion des Boutiques</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Nom</th>
                <th>Propriétaire</th>
                <th>Email</th>
                <th>Activité</th>
            </tr>
        </thead>
        <tbody>
        @foreach($boutiques as $boutique)
            <tr>
                <td>{{ $boutique->id }}</td>

                <td>
                    @if($boutique->logo)
                        <img src="{{ asset('storage/'.$boutique->logo) }}" width="50">
                    @else
                        —
                    @endif
                </td>

                <td>{{ $boutique->nom }}</td>

                <td>
                    {{ $boutique->proprietaire?->name }}
                    {{ $boutique->proprietaire?->surname }}
                </td>

                <td>{{ $boutique->email }}</td>

                <td>
                    <form action="{{ route('admin.boutiques.toggle', $boutique->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        @if($boutique->active)
                            <button class="btn btn-success btn-sm">
                                Activée
                            </button>
                        @else
                            <button class="btn btn-danger btn-sm">
                                Désactivée
                            </button>
                        @endif
                    </form>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
