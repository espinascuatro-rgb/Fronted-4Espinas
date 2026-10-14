document.addEventListener('DOMContentLoaded', () => {
    const API_KEY = '85d1d62262044361831644c6f04d5152';

    // Inicializar el mapa
    const mapa = L.map('visualizar').setView([-34.9011, -56.1645], 15);

    // Cargar mapa desde OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    // Iconos personalizados desde la carpeta ../mape/
    const ImagenMarcador = L.icon({
        iconUrl: '../mape/mapo1.png',
        iconSize: [38, 45],
        iconAnchor: [19, 45],
        popupAnchor: [0, -40]
    });

    const ImagenDestino = L.icon({
        iconUrl: '../mape/mapo2.png',
        iconSize: [38, 45],
        iconAnchor: [19, 45],
        popupAnchor: [0, -40]
    });

    // Marcadores iniciales (Origen y Destino)
    const marcador = L.marker([-34.9011, -56.1645], {
        draggable: true,
        icon: ImagenMarcador
    }).addTo(mapa);

    const marcadorDestino = L.marker([-34.900362, -56.163353], { 
        draggable: true, 
        icon: ImagenDestino 
    }).addTo(mapa);

    // Capa para la línea de la ruta
    let capaRuta = null;

    // Elementos del DOM
    const ingresaro = document.getElementById('ingresar0');
    const ubicar = document.getElementById('ubicar');
    const departamento = document.getElementById('departamento');
    const direccion = document.getElementById('dir');
    const insertlat = document.getElementById('lat');
    const insertlng = document.getElementById('lng');
    const inputTiempo = document.getElementById('tiempoEstimado');

    // Función para calcular y trazar la ruta en el mapa
    async function trazarRuta() {
        const origen = marcador.getLatLng();
        const destino = marcadorDestino.getLatLng();

        const urlRouting = `https://api.geoapify.com/v1/routing?waypoints=${origen.lat},${origen.lng}|${destino.lat},${destino.lng}&mode=drive&apiKey=${API_KEY}`;

        try {
            const respuesta = await fetch(urlRouting);
            const datos = await respuesta.json();

            if (datos.features && datos.features.length > 0) {
                // Eliminar la ruta anterior si existe
                if (capaRuta) {
                    mapa.removeLayer(capaRuta);
                }

                // Dibujar la nueva ruta trazada por calles
                capaRuta = L.geoJSON(datos.features[0], {
                    style: {
                        color: '#007bff',
                        weight: 6,
                        opacity: 0.8
                    }
                }).addTo(mapa);

                // Calcular el tiempo estimado
                const propiedades = datos.features[0].properties;
                if (propiedades && propiedades.time) {
                    const minutos = Math.ceil(propiedades.time / 60);
                    if (inputTiempo) {
                        inputTiempo.value = `${minutos} Minutos`;
                    }
                }
            }
        } catch (error) {
            console.error('Error al calcular la ruta:', error);
        }
    }

    // Trazar la ruta inicial al cargar
    trazarRuta();

    // Actualizar ruta cuando se arrastren los marcadores
    marcador.on('dragend', trazarRuta);
    marcadorDestino.on('dragend', trazarRuta);

    // Botón para obtener lat/lng del marcador principal
    ingresaro.addEventListener('click', () => {
        const posicion = marcador.getLatLng();
        insertlat.value = posicion.lat.toFixed(6);
        insertlng.value = posicion.lng.toFixed(6);
    });

    // Botón para geolocalizar dirección y trazar la ruta
    ubicar.addEventListener('click', async () => {
        if (!direccion.value.trim()) {
            alert('Por favor ingrese una dirección.');
            return;
        }

        const texto = `${direccion.value}, ${departamento.value}, Uruguay`;
        const url = `https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(texto)}&filter=countrycode:uy&lang=es&limit=1&format=json&apiKey=${API_KEY}`;

        try {
            const respuesta = await fetch(url);
            const dato = await respuesta.json();

            if (dato.results && dato.results.length > 0) {
                const resultado = dato.results[0];
                const nuevaPos = [resultado.lat, resultado.lon];

                // Mover marcador de destino a la nueva ubicación
                marcadorDestino.setLatLng(nuevaPos);
                marcadorDestino.bindPopup(`<b>Destino:</b><br>${resultado.formatted}`).openPopup();

                // Ajustar vista para abarcar origen y destino
                const grupo = new L.featureGroup([marcador, marcadorDestino]);
                mapa.fitBounds(grupo.getBounds().pad(0.2));

                // Trazar la ruta hacia el nuevo destino
                await trazarRuta();
            } else {
                alert('No se encontraron resultados para la dirección introducida.');
            }
        } catch (error) {
            console.error('Error al realizar la búsqueda:', error);
            alert('Error al conectar con el servicio de mapas.');
        }
    });

    // Fuerza la renderización correcta del mapa al cargar la vista
    setTimeout(() => {
        mapa.invalidateSize();
    }, 300);
});