document.addEventListener('DOMContentLoaded', function () {
    console.log("Iniciando módulo de mapas y rutas (Modo Local XAMPP)...");


    let coordsOrigen = [-34.8883, -56.1561];
    let coordsDestino = [-34.8812, -56.1420];


    const contenedorMapa = document.getElementById('mapaRuta');
    if (!contenedorMapa) {
        console.error("Error crítico: No se encontró el elemento #mapaRuta en el DOM.");
        return;
    }


    let map = null;
    try {
        map = L.map('mapaRuta', {
            zoomControl: true,
            attributionControl: true
        }).setView(coordsOrigen, 14);
        console.log("Instancia de Leaflet creada correctamente.");
    } catch (e) {
        console.error("Error al inicializar Leaflet:", e);
        return;
    }


    const capaTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        minZoom: 10,
        subdomains: ['a', 'b', 'c'],
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    capaTiles.addTo(map);


    capaTiles.on('tileload', function() {

    });

    capaTiles.on('tileerror', function(errorOcurrido) {
        console.warn("Aviso de red: Error al cargar alguna tesela del mapa localmente.", errorOcurrido);
    });


    setTimeout(function () {
        if (map) {
            map.invalidateSize();
            console.log("Dimensiones del mapa recalculadas e invalidadas correctamente.");
        }
    }, 300);


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


    const markerOrigen = L.marker(coordsOrigen, { icon: iconoMapo1, draggable: true }).addTo(map);
    markerOrigen.bindPopup('<b>Punto de Origen</b><br>Hospital de Clínicas').openPopup();

    const markerDestino = L.marker(coordsDestino, { icon: iconoMapo2, draggable: true }).addTo(map);
    markerDestino.bindPopup('<b>Punto de Destino</b><br>Arrastra para mover el destino');

    let capaPolylineRuta = null;


    async function actualizarRuta() {
        const posO = markerOrigen.getLatLng();
        const posD = markerDestino.getLatLng();

        if (capaPolylineRuta !== null) {
            map.removeLayer(capaPolylineRuta);
            capaPolylineRuta = null;
        }

        const urlOSRM = `https://router.project-osrm.org/route/v1/driving/${posO.lng},${posO.lat};${posD.lng},${posD.lat}?overview=full&geometries=geojson`;

        try {
            console.log("Consultando servicio de enrutamiento OSRM...");
            const respuesta = await fetch(urlOSRM);
            const json = await respuesta.json();

            if (json.code === 'Ok' && json.routes && json.routes.length > 0) {
                const route = json.routes[0];
                const latLngs = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);


                capaPolylineRuta = L.polyline(latLngs, {
                    color: '#1840dd',
                    weight: 6,
                    opacity: 0.95,
                    lineCap: 'round',
                    lineJoin: 'round'
                }).addTo(map);

                const distKm = (route.distance / 1000).toFixed(2);
                const elemDist = document.getElementById('txtDistancia');
                if (elemDist) {
                    elemDist.textContent = `${distKm} km`;
                }
                console.log("Ruta trazada exitosamente por las calles. Distancia:", distKm, "km");
            } else {
                trazarRutaFallbackDirecta(posO, posD);
            }
        } catch (errorDeRed) {
            console.warn("Fallo de conexión con OSRM en localhost, aplicando respaldo directo:", errorDeRed);
            trazarRutaFallbackDirecta(posO, posD);
        }
    }


    function trazarRutaFallbackDirecta(posO, posD) {
        capaPolylineRuta = L.polyline([posO, posD], {
            color: '#1840dd',
            weight: 6,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(map);

        const distMetros = posO.distanceTo(posD);
        const distKm = (distMetros / 1000).toFixed(2);
        const elemDist = document.getElementById('txtDistancia');
        if (elemDist) {
            elemDist.textContent = `${distKm} km`;
        }
    }


    markerOrigen.on('dragend', function () {
        actualizarRuta();
    });

    markerDestino.on('dragend', function () {
        actualizarRuta();
    });


    actualizarRuta();


    const selectTraslado = document.getElementById('selectTraslado');
    if (selectTraslado) {
        selectTraslado.addEventListener('change', function (e) {
            const opcionSeleccionada = e.target.options[e.target.selectedIndex];
            if (!opcionSeleccionada.value) return;

            const latDestino = parseFloat(opcionSeleccionada.getAttribute('data-lat'));
            const lngDestino = parseFloat(opcionSeleccionada.getAttribute('data-lng'));


            document.getElementById('txtId').textContent = '#' + opcionSeleccionada.value;
            document.getElementById('txtOrigen').textContent = opcionSeleccionada.getAttribute('data-origen');
            document.getElementById('txtDestino').textContent = opcionSeleccionada.getAttribute('data-destino');
            document.getElementById('txtSalida').textContent = opcionSeleccionada.getAttribute('data-salida');
            document.getElementById('txtEstimada').textContent = opcionSeleccionada.getAttribute('data-estimada');
            document.getElementById('txtAmbulancia').textContent = 'Unidad N° ' + opcionSeleccionada.getAttribute('data-ambulancia');

            const estadoTraslado = opcionSeleccionada.getAttribute('data-estado');
            const txtEstado = document.getElementById('txtEstado');
            if (txtEstado) {
                txtEstado.textContent = estadoTraslado.replace('_', ' ').toUpperCase();
                txtEstado.className = 'badge-estado badge-' + estadoTraslado;
            }

            if (!isNaN(latDestino) && !isNaN(lngDestino)) {
                markerDestino.setLatLng([latDestino, lngDestino]);
                actualizarRuta();
                map.fitBounds(L.latLngBounds([markerOrigen.getLatLng(), [latDestino, lngDestino]]), {
                    padding: [50, 50]
                });
                setTimeout(function () {
                    map.invalidateSize();
                }, 150);
            }
        });
    }
});