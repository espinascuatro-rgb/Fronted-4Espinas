document.addEventListener('DOMContentLoaded', () => {
    const API_KEY = '85d1d62262044361831644c6f04d5152';

    // Estado del traslado
    let trasladoActivo = false;

    // Inicializar el mapa con la opción de Pantalla Completa activada
    const mapa = L.map('visualizar', {
        fullscreenControl: true,
        fullscreenControlOptions: {
            position: 'topleft'
        }
    }).setView([-34.9011, -56.1645], 15);

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

    const btnIniciar = document.getElementById('btnIniciar');
    const btnFinalizar = document.getElementById('btnFinalizar');

    // Función para calcular y trazar la ruta en el mapa
    async function trazarRuta() {
        if (!trasladoActivo) return;

        const origen = marcador.getLatLng();
        const destino = marcadorDestino.getLatLng();

        const urlRouting = `https://api.geoapify.com/v1/routing?waypoints=${origen.lat},${origen.lng}|${destino.lat},${destino.lng}&mode=drive&apiKey=${API_KEY}`;

        try {
            const respuesta = await fetch(urlRouting);
            const datos = await respuesta.json();

            if (datos.features && datos.features.length > 0) {
                limpiarRuta();

                capaRuta = L.geoJSON(datos.features[0], {
                    style: {
                        color: '#007bff',
                        weight: 6,
                        opacity: 0.8
                    }
                }).addTo(mapa);

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

    // Función para limpiar la ruta del mapa
    function limpiarRuta() {
        if (capaRuta) {
            mapa.removeLayer(capaRuta);
            capaRuta = null;
        }
    }

    // Evento: Iniciar Traslado
    btnIniciar.addEventListener('click', () => {
        trasladoActivo = true;
        trazarRuta();
    });

    // Evento: Finalizar Traslado
    btnFinalizar.addEventListener('click', () => {
        trasladoActivo = false;
        limpiarRuta();
        if (inputTiempo) {
            inputTiempo.value = 'Traslado finalizado';
        }
    });

    // Actualizar ruta cuando se arrastren los marcadores
    marcador.on('dragend', () => {
        if (trasladoActivo) trazarRuta();
    });
    marcadorDestino.on('dragend', () => {
        if (trasladoActivo) trazarRuta();
    });

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

                marcadorDestino.setLatLng(nuevaPos);
                marcadorDestino.bindPopup(`<b>Destino:</b><br>${resultado.formatted}`).openPopup();

                const grupo = new L.featureGroup([marcador, marcadorDestino]);
                mapa.fitBounds(grupo.getBounds().pad(0.2));

                if (trasladoActivo) {
                    await trazarRuta();
                }
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