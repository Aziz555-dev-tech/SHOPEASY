@extends('layouts.client')

@section('content')

    <form method="POST" action="{{ route('client.livraison.store') }}">
        @csrf

        <input type="text" name="adresse" id="adresse" placeholder="Adresse de livraison">

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <div id="map"></div>

        <button>Enrégistrer</button>
    </form>


{{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> --}}

<script>
    const map = L.map('map').setView([6.37, 2.39], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
        .addTo(map);

    let marker;

    map.on('click', function (e) {
        if (marker) map.removeLayer(marker);

        marker = L.marker(e.latlng).addTo(map);

        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });
</script>

<style>
    #map { height: 400px; margin-top: 10px; }
</style>


@endsection

