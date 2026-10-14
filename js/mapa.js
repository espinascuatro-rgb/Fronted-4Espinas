document.addEventListener('DOMContentLoaded', () => {
    const API_KEY = '85d1d62262044361831644c6f04d5152';
    const ID_TRASLADO = 1; 
    const DISTANCIA_LLEGADA_METROS = 50; 

    let trasladoActivo = false;
    let yaAlertoLlegada = false;
    let intervaloPolling = null;

    // Inicializar el mapa
    const mapa = L.map('visualizar', {
        fullscreenControl: true,
        fullscreenControlOptions: { position: 'topleft' }
    }).setView([-34.9011, -56.1645], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    // Iconos personalizados
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

    const marcadorChofer = L.marker([-34.9011, -56.1645], { icon: ImagenMarcador }).addTo(mapa);
    const marcadorDestino = L.marker([-34.900362, -56.163353], { draggable: true, icon: ImagenDestino }).addTo(mapa);

    let capaRuta = null;

    // Elementos del DOM
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

    // Calcular distancia en metros entre dos puntos
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

    // Trazar ruta y enviar tiempo estimado a la Base de Datos
    async function actualizarRutaYCuentaRegresiva(posChofer, posDestino) {
        const modo = selectTransporte.value;
        const url = `https://api.geoapify.com/v1/routing?waypoints=${posChofer.lat},${posChofer.lng}|${posDestino.lat},${posDestino.lng}&mode=${modo}&apiKey=${API_KEY}`;

        try {
            const res = await fetch(url);
            const datos = await res.json();

            if (datos.features && datos.features.length > 0) {
                limpiarRuta();

                capaRuta = L.geoJSON(datos.features[0], {
                    style: { color: '#007bff', weight: 6, opacity: 0.8 }
                }).addTo(mapa);

                const props = datos.features[0].properties;
                if (props && props.time) {
                    const segundos = props.time;
                    const minutos = Math.floor(segundos / 60);
                    const segRestantes = Math.floor(segundos % 60);

                    if (inputTiempo) inputTiempo.value = `${minutos}m ${segRestantes}s restantes`;

                    if (inputHoraLlegada) {
                        const ahora = new Date();
                        ahora.setSeconds(ahora.getSeconds() + segundos);
                        const horas = String(ahora.getHours()).padStart(2, '0');
                        const mins = String(ahora.getMinutes()).padStart(2, '0');
                        const horaCalculada = `${horas}:${mins} hs`;
                        
                        inputHoraLlegada.value = horaCalculada;

                        // Guardar la hora estimada de llegada en la BD para Trazabilidad
                        const formData = new FormData();
                        formData.append('accion', 'actualizar_tiempo_gps');
                        formData.append('id_traslado', ID_TRASLADO);
                        formData.append('hora_estimada', horaCalculada);
                        fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });
                    }
                }
            }
        } catch (e) {
            console.error("Error al trazar la ruta:", e);
        }
    }

    function limpiarRuta() {
        if (capaRuta) {
            mapa.removeLayer(capaRuta);
            capaRuta = null;
        }
    }

    async function monitorearTraslado() {
        if (!trasladoActivo) return;

        try {
            const res = await fetch(`../php/actualizar_posicion.php?accion=obtener_estado&id_traslado=${ID_TRASLADO}&_=${new Date().getTime()}`);
            const respuesta = await res.json();

            let posChofer = null;

            if (respuesta.status === 'ok' && respuesta.data) {
                if (respuesta.data.lat_chofer && respuesta.data.lng_chofer) {
                    posChofer = { 
                        lat: parseFloat(respuesta.data.lat_chofer), 
                        lng: parseFloat(respuesta.data.lng_chofer) 
                    };
                }
            }

            if (!posChofer) {
                posChofer = marcadorChofer.getLatLng();
            }

            const posDestino = marcadorDestino.getLatLng();

            marcadorChofer.setLatLng([posChofer.lat, posChofer.lng]);
            await actualizarRutaYCuentaRegresiva(posChofer, posDestino);

            const distancia = calcularDistanciaMetros(posChofer.lat, posChofer.lng, posDestino.lat, posDestino.lng);

            if (distancia <= DISTANCIA_LLEGADA_METROS && !yaAlertoLlegada) {
                yaAlertoLlegada = true;
                alert("¡ATENCIÓN! El chofer ha llegado al lugar de destino.");
            }
        } catch (e) {
            console.error("Error al consultar la ubicación:", e);
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

        await monitorearTraslado();

        if (intervaloPolling) clearInterval(intervaloPolling);
        intervaloPolling = setInterval(monitorearTraslado, 4000);
    });

    // Botón: Finalizar Traslado
    btnFinalizar.addEventListener('click', async () => {
        trasladoActivo = false;
        if (intervaloPolling) {
            clearInterval(intervaloPolling);
            intervaloPolling = null;
        }

        limpiarRuta();

        const formData = new FormData();
        formData.append('accion', 'cambiar_estado');
        formData.append('id_traslado', ID_TRASLADO);
        formData.append('estado', 'finalizado');
        
        await fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });

        if (inputTiempo) inputTiempo.value = 'Traslado finalizado';
        if (inputHoraLlegada) inputHoraLlegada.value = 'Traslado finalizado';
    });

    selectTransporte.addEventListener('change', () => {
        if (trasladoActivo) monitorearTraslado();
    });

    marcadorDestino.on('dragend', () => {
        if (trasladoActivo) monitorearTraslado();
    });

    ingresaro.addEventListener('click', () => {
        const posicion = marcadorChofer.getLatLng();
        insertlat.value = posicion.lat.toFixed(6);
        insertlng.value = posicion.lng.toFixed(6);
    });

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

                const formData = new FormData();
                formData.append('accion', 'actualizar_destino');
                formData.append('id_traslado', ID_TRASLADO);
                formData.append('direccion', resultado.formatted);
                fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });

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

    setTimeout(() => {
        mapa.invalidateSize();
    }, 300);
});