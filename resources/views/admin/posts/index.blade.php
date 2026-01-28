@extends('layouts.admin')

@section('title', 'Liste des articles')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Articles</h3>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ Nouvel article</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Publié</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->titre }}</td>
                <td>{{ $post->categorie?->nom ?? 'Aucune' }}</td>
                <td>
                    @if($post->publie)
                        <span class="badge bg-success">Oui</span>
                    @else
                        <span class="badge bg-secondary">Non</span>
                    @endif
                </td>
                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet article ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Aucun article trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $posts->links() }}
</div>
@endsection
