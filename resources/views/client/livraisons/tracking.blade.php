
@extends('layouts.client')

@section('content')

<h4 class="mb-3"> Suivi de votre livraison</h4>

<div id="map" style="height: 400px; border-radius: 8px;"></div>

{{-- Données JS --}}
<input type="hidden" id="livraison-id" value="{{ $livraison->id }}">
<input type="hidden" id="boutique-lat" value="{{ $livraison->boutique->latitude }}">
<input type="hidden" id="boutique-lng" value="{{ $livraison->boutique->longitude }}">

@endsection

@push('styles')
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/client-tracking.js') }}"></script>

    {{-- <script>
        const TRACKING_URL = "{{ route('client.livraisons.tracking', $livraison) }}";
        <script src="{{ asset('js/tracking/map.js') }}"></script>
    </script> --}}
@endpush
