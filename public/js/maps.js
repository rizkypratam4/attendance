document.addEventListener('DOMContentLoaded', () => {
    const locations = Array.isArray(window.locationData) ? window.locationData : [];

    if (!locations.length || !document.getElementById('map')) return;

    const map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const markers = [];

    locations.forEach(loc => {
        if(loc.latitude && loc.longitude){
            const marker = L.marker([parseFloat(loc.latitude), parseFloat(loc.longitude)])
                .addTo(map)
                .bindPopup(`<b>${loc.name}</b><br>${loc.address}<br>${loc.location_type}`);
            markers.push(marker);
        }
    });

    if(markers.length > 0){
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.2));
    }
});