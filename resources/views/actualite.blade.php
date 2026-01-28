@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/actualite.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">
@section('title', 'Actualités')

@section('content')

<div class="blog-container">
    <div class="blog-header">
        <h2>Nos Actualités</h2>
    </div>

    @if(isset($query))
        <h4>Résultats pour « {{ $query }} »</h4>
    @endif

    <div class="blog-layout">
        <!-- SECTION DES ARTICLES -->
        <div class="blog-grid">
            @foreach ($posts as $post)
                <div class="blog-card">
                    <div class="image-wrapper">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->titre }}">
                    </div>

                    <!-- Logo circulaire au centre -->
                    <div class="blog-logo-circle">
                        <img src="{{ asset('assets/images/logo_sahashop.png') }}" alt="SIS SARL">
                    </div>

                    <div class="blog-card-body">
                        <h5>{{ $post->titre }}</h5>
                        <p>{{ Str::limit($post->resume, 100) }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="view-btn">
                            Voir plus <span>&raquo;&raquo;&raquo;</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="search-box mb-4">
                <form action="{{ route('blog.search') }}" method="GET" class="d-flex">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Rechercher un article..." 
                        value="{{ request('q') }}" 
                        class="form-control me-2"
                    >
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <div class="recent-posts">
                <h4>Articles récents</h4>
                @foreach ($recentPosts as $recent)
                    <a href="{{ route('blog.show', $recent->slug) }}">{{ $recent->titre }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
