document.addEventListener('DOMContentLoaded', () => {
    // Coordenadas base: Hospital de Clínicas, Montevideo
    const coordsHospital = [-34.8883, -56.1561];
    
    // Inicializar el mapa Leaflet
    const map = L.map('mapaRuta').setView(coordsHospital, 14);

    // Cargar capa de mapas (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Marcador en el Hospital de Clínicas
    L.marker(coordsHospital)
        .addTo(map)
        .bindPopup('<b>Hospital de Clínicas</b><br>Punto de Origen')
        .openPopup();

    const selectTraslado = document.getElementById('selectTraslado');

    if (selectTraslado) {
        selectTraslado.addEventListener('change', (e) => {
            const val = e.target.value;
            if (val === '1') {
                const coordsDestino = [-34.8872, -56.1520];
                
                // Agregar marcador de la unidad/destino
                const markerDestino = L.marker(coordsDestino).addTo(map);
                markerDestino.bindPopup('<b>Unidad de Ambulancia</b><br>Destino: Av. Italia 2870').openPopup();

                // Trazar línea de ruta
                const latlngs = [coordsHospital, coordsDestino];
                const polyline = L.polyline(latlngs, { color: '#006eff', weight: 5 }).addTo(map);

                // Ajustar el zoom para ver toda la ruta
                map.fitBounds(polyline.getBounds());
            }
        });
    }
});