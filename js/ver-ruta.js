document.addEventListener('DOMContentLoaded', () => {
    // Coordenadas iniciales: Hospital de Clínicas, Montevideo (-34.8883, -56.1561)
    const coordsHospital = [-34.8883, -56.1561];
    
    const map = L.map('mapaRuta').setView(coordsHospital, 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let markerOrigen = L.marker(coordsHospital).addTo(map)
        .bindPopup('<b>Hospital de Clínicas</b><br>Punto de Origen').openPopup();

    let markerChofer = null;
    let lineaRuta = null;

    const selectTraslado = document.getElementById('selectTraslado');

    if (selectTraslado) {
        selectTraslado.addEventListener('change', (e) => {
            const op = e.target.options[e.target.selectedIndex];
            if (!op.value) return;

            // Obtener atributos de datos de la opción seleccionada
            const id = op.value;
            const origen = op.getAttribute('data-origen');
            const destino = op.getAttribute('data-destino');
            const estado = op.getAttribute('data-estado');
            const lat = parseFloat(op.getAttribute('data-lat'));
            const lng = parseFloat(op.getAttribute('data-lng'));
            const salida = op.getAttribute('data-salida');
            const estimada = op.getAttribute('data-estimada');
            const ambulancia = op.getAttribute('data-ambulancia');

            // Actualizar interfaz textual
            document.getElementById('txtId').textContent = '#' + id;
            document.getElementById('txtOrigen').textContent = origen;
            document.getElementById('txtDestino').textContent = destino;
            document.getElementById('txtSalida').textContent = salida;
            document.getElementById('txtEstimada').textContent = estimada;
            document.getElementById('txtAmbulancia').textContent = 'Unidad N° ' + ambulancia;

            const txtEstado = document.getElementById('txtEstado');
            txtEstado.textContent = estado.replace('_', ' ').toUpperCase();
            txtEstado.className = 'badge-estado badge-' + estado;

            // Limpiar capas previas si existen
            if (markerChofer) map.removeLayer(markerChofer);
            if (lineaRuta) map.removeLayer(lineaRuta);

            if (!isNaN(lat) && !isNaN(lng)) {
                const coordsChofer = [lat, lng];

                // Agregar marcador del chofer / ambulancia
                markerChofer = L.marker(coordsChofer).addTo(map);
                markerChofer.bindPopup(`<b>Ambulancia (Chofer)</b><br>Destino: ${destino}`).openPopup();

                // Trazar línea de ruta
                lineaRuta = L.polyline([coordsHospital, coordsChofer], {
                    color: '#006eff',
                    weight: 5,
                    dashArray: '8, 8'
                }).addTo(map);

                // Ajustar vista para encuadrar la ruta
                map.fitBounds(lineaRuta.getBounds(), { padding: [40, 40] });
            }
        });
    }
});