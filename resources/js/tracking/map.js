let marker = null;

function refreshTracking(url, map) {
    fetch(url)
        .then(res => res.json())
        .then(data => {
            let { latitude, longitude } = data.livreur;

            if (!marker) {
                marker = L.marker([latitude, longitude]).addTo(map);
            } else {
                marker.setLatLng([latitude, longitude]);
            }

            map.setView([latitude, longitude]);
        });
}

setInterval(() => {
    refreshTracking(TRACKING_URL, map);
}, 5000);
