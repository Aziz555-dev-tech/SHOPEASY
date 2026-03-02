const livraisonId = document.getElementById('livraison-id').value;
const boutiqueLat = document.getElementById('boutique-lat').value;
const boutiqueLng = document.getElementById('boutique-lng').value;

// Carte
const map = L.map('map').setView([boutiqueLat, boutiqueLng], 13);

// Fond de carte
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Marker boutique
L.marker([boutiqueLat, boutiqueLng])
    .addTo(map)
    .bindPopup('🏬 Boutique')
    .openPopup();

let livreurMarker = null;

// Rafraîchir position livreur
async function updateLivreurPosition() {
    try {
        const response = await fetch(
            `/api/livraisons/${livraisonId}/livreur-position`,
            {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }
        );

        if (!response.ok) return;

        const data = await response.json();

        if (livreurMarker) {
            livreurMarker.setLatLng([data.latitude, data.longitude]);
        } else {
            livreurMarker = L.marker([data.latitude, data.longitude], {
                icon: L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                    iconSize: [32, 32]
                })
            }).addTo(map)
              .bindPopup('Livreur');
        }

    } catch (e) {
        console.error('Erreur tracking livreur');
    }
}

// Toutes les 5 secondes
setInterval(updateLivreurPosition, 5000);
updateLivreurPosition();
