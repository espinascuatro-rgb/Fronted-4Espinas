document.addEventListener('DOMContentLoaded', () => {
    let coordsOrigen = [-34.8883, -56.1561];
    let coordsDestino = [-34.8812, -56.1420];

    // Iconos personalizados del proyecto
    const iconoMapo1 = L.icon({
        iconUrl: '../mape/mapo2.png',
        iconSize: [38, 38],
        iconAnchor: [19, 38],
        popupAnchor: [0, -38]
    });

    const iconoMapo2 = L.icon({
        iconUrl: '../mape/mapo1.png',
        iconSize: [38, 38],
        iconAnchor: [19, 38],
        popupAnchor: [0, -38]
    });

    // Inicializar mapa Leaflet
    const map = L.map('mapaRuta').setView(coordsOrigen, 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Botón de pantalla completa personalizado
    const zoomContainer = document.querySelector('.leaflet-control-zoom');
    if (zoomContainer) {
        const btnFS = document.createElement('a');
        btnFS.className = 'leaflet-control-fullscreen-btn';
        btnFS.href = '#';
        btnFS.title = 'Pantalla Completa';
        btnFS.innerHTML = '⛶';

        btnFS.addEventListener('click', (e) => {
            e.preventDefault();
            const mapaBox = document.getElementById('mapaBox');
            if (!document.fullscreenElement) {
                if (mapaBox.requestFullscreen) mapaBox.requestFullscreen();
                else if (mapaBox.webkitRequestFullscreen) mapaBox.webkitRequestFullscreen();
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        });

        zoomContainer.appendChild(btnFS);
    }

    document.addEventListener('fullscreenchange', () => {
        setTimeout(() => map.invalidateSize(), 200);
    });

    // Marcadores con Iconos personalizados y Arrastrables (Draggable)
    const markerOrigen = L.marker(coordsOrigen, { icon: iconoMapo1, draggable: true }).addTo(map);
    markerOrigen.bindPopup('<b>Punto de Origen</b><br>Hospital de Clínicas').openPopup();

    const markerDestino = L.marker(coordsDestino, { icon: iconoMapo2, draggable: true }).addTo(map);
    markerDestino.bindPopup('<b>Punto de Destino</b><br>Arrastra para mover');

    // Capa de polígonos para la ruta por calles
    let capaRutaCalle = null;

    // Función que consulta el servicio OSRM por FETCH directo para evitar bloqueos
    async function actualizarRuta() {
        const posO = markerOrigen.getLatLng();
        const posD = markerDestino.getLatLng();

        // Eliminar capa anterior si existe
        if (capaRutaCalle !== null) {
            map.removeLayer(capaRutaCalle);
            capaRutaCalle = null;
        }

        // URL del servicio público OSRM (lon,lat)
        const urlOSRM = `https://router.project-osrm.org/route/v1/driving/${posO.lng},${posO.lat};${posD.lng},${posD.lat}?overview=full&geometries=geojson`;

        try {
            const respuesta = await fetch(urlOSRM);
            const datos = await respuesta.json();

            if (datos.code === 'Ok' && datos.routes && datos.routes.length > 0) {
                const rutaGeoJSON = datos.routes[0].geometry;
                const distanciaMetros = datos.routes[0].distance;

                // Trazar la línea GeoJSON CONTINUA sobre las calles (sin dashArray)
                capaRutaCalle = L.geoJSON(rutaGeoJSON, {
                    style: {
                        color: '#1840dd',
                        weight: 6,
                        opacity: 0.9,
                        lineCap: 'round',
                        lineJoin: 'round'
                    }
                }).addTo(map);

                // Actualizar distancia real por carretera
                const distKm = (distanciaMetros / 1000).toFixed(2);
                const elemDist = document.getElementById('txtDistancia');
                if (elemDist) elemDist.textContent = `${distKm} km`;

            } else {
                trazarRutaDirectaFallback(posO, posD);
            }
        } catch (error) {
            console.warn('Servidor de enrutamiento no disponible, mostrando ruta directa continua:', error);
            trazarRutaDirectaFallback(posO, posD);
        }
    }

    // Trazado de respaldo con línea continua fija si no hay conexión a la API
    function trazarRutaDirectaFallback(posO, posD) {
        capaRutaCalle = L.polyline([posO, posD], {
            color: '#1840dd',
            weight: 6,
            opacity: 0.9
        }).addTo(map);

        const distM = posO.distanceTo(posD);
        const distKm = (distM / 1000).toFixed(2);
        const elemDist = document.getElementById('txtDistancia');
        if (elemDist) elemDist.textContent = `${distKm} km`;
    }

    // Eventos al soltar los marcadores arrastrables
    markerOrigen.on('dragend', actualizarRuta);
    markerDestino.on('dragend', actualizarRuta);
    actualizarRuta();

    // Selección Dinámica de Traslado desde la Base de Datos
    const selectTraslado = document.getElementById('selectTraslado');
    if (selectTraslado) {
        selectTraslado.addEventListener('change', (e) => {
            const op = e.target.options[e.target.selectedIndex];
            if (!op.value) return;

            const id = op.value;
            const origen = op.getAttribute('data-origen');
            const destino = op.getAttribute('data-destino');
            const estado = op.getAttribute('data-estado');
            const lat = parseFloat(op.getAttribute('data-lat'));
            const lng = parseFloat(op.getAttribute('data-lng'));
            const salida = op.getAttribute('data-salida');
            const estimada = op.getAttribute('data-estimada');
            const ambulancia = op.getAttribute('data-ambulancia');

            document.getElementById('txtId').textContent = '#' + id;
            document.getElementById('txtOrigen').textContent = origen;
            document.getElementById('txtDestino').textContent = destino;
            document.getElementById('txtSalida').textContent = salida;
            document.getElementById('txtEstimada').textContent = estimada;
            document.getElementById('txtAmbulancia').textContent = 'Unidad N° ' + ambulancia;

            const txtEstado = document.getElementById('txtEstado');
            txtEstado.textContent = estado.replace('_', ' ').toUpperCase();
            txtEstado.className = 'badge-estado badge-' + estado;

            if (!isNaN(lat) && !isNaN(lng)) {
                markerDestino.setLatLng([lat, lng]);
                actualizarRuta();
                map.fitBounds(L.latLngBounds([markerOrigen.getLatLng(), [lat, lng]]), { padding: [50, 50] });
            }
        });
    }
});