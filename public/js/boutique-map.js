const map = L.map('map').setView([6.37, 2.39], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

let marker = null;

/**
 * Met à jour le marker et les champs cachés
 */
function setMarker(lat, lng) {
    if (marker) map.removeLayer(marker);

    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

/**
 * Clic manuel sur la carte
 */
map.on('click', function (e) {
    setMarker(e.latlng.lat, e.latlng.lng);
    document.getElementById('address-error').classList.add('d-none');
});

/**
 * Recherche par adresse (Nominatim)
 */
document.getElementById('searchAddress').addEventListener('click', function () {

    const address = document.getElementById('adresse').value;
    const errorBox = document.getElementById('address-error');

    errorBox.classList.add('d-none');

    if (!address.trim()) return;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                errorBox.classList.remove('d-none');
                return;
            }

            const lat = parseFloat(data[0].lat);
            const lon = parseFloat(data[0].lon);

            setMarker(lat, lon);
        })
        .catch(() => {
            errorBox.classList.remove('d-none');
        });
});
