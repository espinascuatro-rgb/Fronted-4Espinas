
let solicitudRuta = null;

async function actualizarRuta() {
    const posO = markerOrigen.getLatLng();
    const posD = markerDestino.getLatLng();

    if (solicitudRuta) {
        solicitudRuta.abort();
    }

    const controlador = new AbortController();
    solicitudRuta = controlador;

    if (capaPolylineRuta) {
        map.removeLayer(capaPolylineRuta);
        capaPolylineRuta = null;
    }

    const distancia = document.getElementById('txtDistancia');
    if (distancia) distancia.textContent = 'Calculando ruta...';

    const url = `https://router.project-osrm.org/route/v1/driving/${posO.lng},${posO.lat};${posD.lng},${posD.lat}?overview=full&geometries=geojson&steps=true`;

    try {
        const respuesta = await fetch(url, {
            signal: controlador.signal
        });

        if (!respuesta.ok) {
            throw new Error('No se pudo conectar con el servicio de rutas.');
        }

        const datos = await respuesta.json();

        if (solicitudRuta !== controlador) return;

        if (datos.code !== 'Ok' || !datos.routes?.length) {
            throw new Error('No se encontró una ruta por calles.');
        }

        const ruta = datos.routes[0];

        // Utilizar TODOS los puntos de la ruta calculada por calles.
        const puntos = ruta.geometry.coordinates.map(([lng, lat]) => [
            lat,
            lng
        ]);

        capaPolylineRuta = L.polyline(puntos, {
            color: '#1840dd',
            weight: 6,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round',
            className: 'ruta-continua',
            interactive: false,
            smoothFactor: 1
        }).addTo(map);

        if (distancia) {
            distancia.textContent =
                (ruta.distance / 1000).toFixed(2) + ' km';
        }

    } catch (error) {
        if (error.name === 'AbortError') return;

        console.error('Error al calcular la ruta:', error);

        if (solicitudRuta !== controlador) return;

        if (distancia) {
            distancia.textContent = 'No se pudo calcular la ruta';
        }
    } finally {
        if (solicitudRuta === controlador) {
            solicitudRuta = null;
        }
    }
}
