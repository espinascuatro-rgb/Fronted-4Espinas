document.addEventListener('DOMContentLoaded', () => {
    const latOrigenDefault = -34.8883;
    const lngOrigenDefault = -56.1561;
    const latDestinoDefault = -34.8812;
    const lngDestinoDefault = -56.1420;

    const contenedorMapa = document.getElementById('mapaRuta');
    if (!contenedorMapa) return;

    setTimeout(() => {
        const mapa = L.map('mapaRuta', {
            zoomControl: true,
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: 'topleft', 
                title: 'Ver en pantalla completa',
                titleCancel: 'Salir de pantalla completa'
            }
        }).setView([latOrigenDefault, lngOrigenDefault], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(mapa);

        mapa.invalidateSize();

        const iconoMapo2 = L.icon({
            iconUrl: '../mape/mapo2.png',
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -38]
        });

        const iconoMapo1 = L.icon({
            iconUrl: '../mape/mapo1.png',
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -38]
        });

        let controlRuta = null;

        controlRuta = L.Routing.control({
            waypoints: [
                L.latLng(latOrigenDefault, lngOrigenDefault),
                L.latLng(latDestinoDefault, lngDestinoDefault)
            ],
            routeWhileDragging: true,
            addWaypoints: false,
            draggableWaypoints: true, 
            show: false, 
            createMarker: function(i, wp, nWps) {
                let iconoUsar = (i === 0) ? iconoMapo2 : iconoMapo1;
                let tituloPopup = (i === 0) ? "<b>Origen (Arrastra para mover)</b>" : "<b>Destino (Arrastra para mover)</b>";
                
                return L.marker(wp.latLng, {
                    draggable: true,
                    icon: iconoUsar
                }).bindPopup(tituloPopup);
            },
            lineOptions: {
                styles: [
                    { color: '#2563eb', opacity: 0.9, weight: 7 } // Línea azul clásica elegante y gruesa
                ]
            }
        }).addTo(mapa);

        controlRuta.on('routesfound', function(e) {
            const resumen = e.routes[0].summary;
            const minutos = Math.round(resumen.totalTime / 60);
            const km = (resumen.totalDistance / 1000).toFixed(1);

            const elemDist = document.getElementById('txtDistancia');
            if (elemDist) {
                elemDist.textContent = `${km} km (${minutos} min)`;
            }
        });

        const selectTraslado = document.getElementById('selectTraslado');
        if (selectTraslado) {
            selectTraslado.addEventListener('change', (e) => {
                const op = e.target.options[e.target.selectedIndex];
                if (!op.value) return;

                const lat = parseFloat(op.getAttribute('data-lat'));
                const lng = parseFloat(op.getAttribute('data-lng'));

                document.getElementById('txtId').textContent = '#' + op.value;
                document.getElementById('txtOrigen').textContent = op.getAttribute('data-origen');
                document.getElementById('txtDestino').textContent = op.getAttribute('data-destino');
                document.getElementById('txtSalida').textContent = op.getAttribute('data-salida');
                document.getElementById('txtEstimada').textContent = op.getAttribute('data-estimada');
                document.getElementById('txtAmbulancia').textContent = 'Unidad N° ' + op.getAttribute('data-ambulancia');

                const estado = op.getAttribute('data-estado');
                const txtEstado = document.getElementById('txtEstado');
                if (txtEstado) {
                    txtEstado.textContent = estado.replace('_', ' ').toUpperCase();
                    txtEstado.className = 'badge-estado badge-' + estado;
                }

                if (!isNaN(lat) && !isNaN(lng)) {
                    controlRuta.setWaypoints([
                        L.latLng(latOrigenDefault, lngOrigenDefault),
                        L.latLng(lat, lng)
                    ]);
                    setTimeout(() => mapa.invalidateSize(), 150);
                }
            });
        }
    }, 300);
});