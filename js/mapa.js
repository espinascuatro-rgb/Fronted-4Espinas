document.addEventListener('DOMContentLoaded', () => {
    const API_KEY = '85d1d62262044361831644c6f04d5152';
    const ID_TRASLADO = 1; // ID del traslado a monitorear
    const DISTANCIA_LLEGADA_METROS = 50; // Umbral de llegada al destino

    let trasladoActivo = false;
    let yaAlertoLlegada = false;
    let intervaloPolling = null;

    // Inicializar el mapa con opción de Pantalla Completa
    const mapa = L.map('visualizar', {
        fullscreenControl: true,
        fullscreenControlOptions: {
            position: 'topleft'
        }
    }).setView([-34.9011, -56.1645], 15);

    // Cargar capa base de OpenStreetMap
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

    // Marcadores para el Chofer y el Destino
    const marcadorChofer = L.marker([-34.9011, -56.1645], {
        icon: ImagenMarcador
    }).addTo(mapa);

    const marcadorDestino = L.marker([-34.900362, -56.163353], { 
        draggable: true, 
        icon: ImagenDestino 
    }).addTo(mapa);

    // Capa para renderizar la línea de la ruta
    let capaRuta = null;

    // Referencias a los elementos del DOM
    const ingresaro = document.getElementById('ingresar0');
    const ubicar = document.getElementById('ubicar');
    const departamento = document.getElementById('departamento');
    const direccion = document.getElementById('dir');
    const insertlat = document.getElementById('lat');
    const insertlng = document.getElementById('lng');

    const inputTiempo = document.getElementById('tiempoEstimado');
    const inputHoraLlegada = document.getElementById('horaLlegada');
    const selectTransporte = document.getElementById('transporte');

    const btnIniciar = document.getElementById('btnIniciar');
    const btnFinalizar = document.getElementById('btnFinalizar');

    // Función para calcular la distancia en metros entre dos puntos GPS (Haversine)
    function calcularDistanciaMetros(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    // Trazar la ruta por calles y calcular la cuenta regresiva + hora de llegada
    async function actualizarRutaYCuentaRegresiva(posChofer, posDestino) {
        const modo = selectTransporte.value; // drive, bicycle, walk
        const url = `https://api.geoapify.com/v1/routing?waypoints=${posChofer.lat},${posChofer.lng}|${posDestino.lat},${posDestino.lng}&mode=${modo}&apiKey=${API_KEY}`;

        try {
            const res = await fetch(url);
            const datos = await res.json();

            if (datos.features && datos.features.length > 0) {
                limpiarRuta();

                capaRuta = L.geoJSON(datos.features[0], {
                    style: {
                        color: '#007bff',
                        weight: 6,
                        opacity: 0.8
                    }
                }).addTo(mapa);

                const props = datos.features[0].properties;
                if (props && props.time) {
                    const segundos = props.time;
                    const minutos = Math.floor(segundos / 60);
                    const segRestantes = Math.floor(segundos % 60);

                    // Cuenta regresiva MMm SSs
                    if (inputTiempo) {
                        inputTiempo.value = `${minutos}m ${segRestantes}s restantes`;
                    }

                    // Hora exacta estimada de llegada
                    if (inputHoraLlegada) {
                        const ahora = new Date();
                        ahora.setSeconds(ahora.getSeconds() + segundos);
                        const horas = String(ahora.getHours()).padStart(2, '0');
                        const mins = String(ahora.getMinutes()).padStart(2, '0');
                        inputHoraLlegada.value = `${horas}:${mins} hs`;
                    }
                }
            }
        } catch (e) {
            console.error("Error al trazar la ruta:", e);
        }
    }

    // Función para remover la línea del mapa
    function limpiarRuta() {
        if (capaRuta) {
            mapa.removeLayer(capaRuta);
            capaRuta = null;
        }
    }

    // Polling en tiempo real: lee las coordenadas del chofer guardadas en la BD
    async function monitorearTraslado() {
        if (!trasladoActivo) return;

        try {
            const res = await fetch(`../php/actualizar_posicion.php?accion=obtener_estado&id_traslado=${ID_TRASLADO}`);
            const respuesta = await res.json();

            if (respuesta.status === 'ok' && respuesta.data) {
                const data = respuesta.data;

                if (data.lat_chofer && data.lng_chofer) {
                    const posChofer = { lat: parseFloat(data.lat_chofer), lng: parseFloat(data.lng_chofer) };
                    const posDestino = marcadorDestino.getLatLng();

                    // Mover la marca del chofer en el mapa
                    marcadorChofer.setLatLng([posChofer.lat, posChofer.lng]);

                    // Actualizar trazado y cuenta regresiva
                    await actualizarRutaYCuentaRegresiva(posChofer, posDestino);

                    // Verificar llegada
                    const distancia = calcularDistanciaMetros(posChofer.lat, posChofer.lng, posDestino.lat, posDestino.lng);

                    if (distancia <= DISTANCIA_LLEGADA_METROS && !yaAlertoLlegada) {
                        yaAlertoLlegada = true;
                        alert("¡ATENCIÓN! El chofer ha llegado al lugar de destino.");
                    }
                }
            }
        } catch (e) {
            console.error("Error al consultar la ubicación en tiempo real:", e);
        }
    }

    // Botón: Iniciar Traslado
    btnIniciar.addEventListener('click', async () => {
        trasladoActivo = true;
        yaAlertoLlegada = false;

        const formData = new FormData();
        formData.append('accion', 'cambiar_estado');
        formData.append('id_traslado', ID_TRASLADO);
        formData.append('estado', 'en_proceso');
        formData.append('transporte', selectTransporte.value);
        
        await fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });

        monitorearTraslado();
        intervaloPolling = setInterval(monitorearTraslado, 4000); // Polling cada 4 segundos
    });

    // Botón: Finalizar Traslado
    btnFinalizar.addEventListener('click', async () => {
        trasladoActivo = false;
        clearInterval(intervaloPolling);

        limpiarRuta();

        const formData = new FormData();
        formData.append('accion', 'cambiar_estado');
        formData.append('id_traslado', ID_TRASLADO);
        formData.append('estado', 'finalizado');
        
        await fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });

        if (inputTiempo) inputTiempo.value = 'Traslado finalizado';
        if (inputHoraLlegada) inputHoraLlegada.value = 'Traslado finalizado';
    });

    // Recalcular ruta al cambiar el medio de transporte
    selectTransporte.addEventListener('change', () => {
        if (trasladoActivo) {
            monitorearTraslado();
        }
    });

    // Recalcular al mover manualmente el marcador de destino
    marcadorDestino.on('dragend', () => {
        if (trasladoActivo) {
            monitorearTraslado();
        }
    });

    // Capturar coordenadas del marcador chofer
    ingresaro.addEventListener('click', () => {
        const posicion = marcadorChofer.getLatLng();
        insertlat.value = posicion.lat.toFixed(6);
        insertlng.value = posicion.lng.toFixed(6);
    });

    // Botón para geolocalizar dirección y reubicar el destino
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

                const grupo = new L.featureGroup([marcadorChofer, marcadorDestino]);
                mapa.fitBounds(grupo.getBounds().pad(0.2));

                if (trasladoActivo) {
                    await monitorearTraslado();
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