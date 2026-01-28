@extends($layout)

@section('title', 'Paramètres')

@section('content')
<div class="container py-5">
    <h4 class="fw-bold mb-4" style="color: #005078;">Paramètres du profil</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                @if($user->profil)
                    <img src="{{ asset('storage/' . $user->profil) }}" 
                         alt="Photo de profil" 
                         class="rounded-circle border" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <img src="{{ asset('assets/images/default_user.png') }}" 
                         alt="Photo de profil" 
                         class="rounded-circle border" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                @endif
            </div>

            <form action="{{ route('parametres.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" name="profil" class="form-control" required>
                    @error('profil')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Changer la photo</button>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    document.querySelector('input[name="profil"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const img = document.querySelector('.card img');
                img.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
