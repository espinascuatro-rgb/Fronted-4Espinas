document.addEventListener('DOMContentLoaded', () => {
    const API_KEY = '85d1d62262044361831644c6f04d5152';
    const ID_TRASLADO = 1; 

    let trasladoActivo = false;
    let intervaloPolling = null;
    let capaRuta = null;

    const mapa = L.map('visualizar', {
        fullscreenControl: true,
        fullscreenControlOptions: { position: 'topleft' }
    }).setView([-34.9011, -56.1645], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

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

    const marcadorChofer = L.marker([-34.9011, -56.1645], { draggable: true, icon: ImagenMarcador }).addTo(mapa);
    const marcadorDestino = L.marker([-34.900362, -56.163353], { draggable: true, icon: ImagenDestino }).addTo(mapa);

    marcadorChofer.bindPopup("<b>Origen / Chofer</b><br>(Arrastrable)");
    marcadorDestino.bindPopup("<b>Destino</b><br>(Arrastrable)");

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

    function limpiarRuta() {
        if (capaRuta) {
            mapa.removeLayer(capaRuta);
            capaRuta = null;
        }
    }

    async function actualizarRutaYCalculos() {
        const posChofer = marcadorChofer.getLatLng();
        const posDestino = marcadorDestino.getLatLng();
        const modo = selectTransporte ? selectTransporte.value : 'drive';

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

                    if (inputTiempo) inputTiempo.value = `${minutos} min ${segRestantes} seg`;

                    const ahora = new Date();
                    ahora.setSeconds(ahora.getSeconds() + segundos);
                    const horas = String(ahora.getHours()).padStart(2, '0');
                    const mins = String(ahora.getMinutes()).padStart(2, '0');
                    const horaCalculada = `${horas}:${mins} hs`;
                    
                    if (inputHoraLlegada) inputHoraLlegada.value = horaCalculada;

                    const formData = new FormData();
                    formData.append('accion', 'actualizar_tiempo_gps');
                    formData.append('id_traslado', ID_TRASLADO);
                    formData.append('hora_estimada', horaCalculada);
                    fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });
                }
            }
        } catch (e) {
            console.error("Error al trazar la ruta:", e);
        }
    }

    async function monitorearTraslado() {
        if (!trasladoActivo) return;

        try {
            const res = await fetch(`../php/actualizar_posicion.php?accion=obtener_estado&id_traslado=${ID_TRASLADO}&t=${new Date().getTime()}`);
            const respuesta = await res.json();

            if (respuesta.status === 'ok' && respuesta.data) {
                if (respuesta.data.lat_chofer && respuesta.data.lng_chofer) {
                    const lat = parseFloat(respuesta.data.lat_chofer);
                    const lng = parseFloat(respuesta.data.lng_chofer);

                    if (lat !== 0 && lng !== 0) {
                        marcadorChofer.setLatLng([lat, lng]);
                    }
                }
            }

            await actualizarRutaYCalculos();
        } catch (e) {
            console.error("Error monitoreando posición:", e);
        }
    }

    marcadorChofer.on('dragend', () => {
        const pos = marcadorChofer.getLatLng();
        if (insertlat) insertlat.value = pos.lat.toFixed(6);
        if (insertlng) insertlng.value = pos.lng.toFixed(6);
        actualizarRutaYCalculos();
    });

    marcadorDestino.on('dragend', () => {
        actualizarRutaYCalculos();
    });

    if (selectTransporte) {
        selectTransporte.addEventListener('change', () => {
            actualizarRutaYCalculos();
        });
    }

    if (ingresaro) {
        ingresaro.addEventListener('click', () => {
            const pos = marcadorChofer.getLatLng();
            if (insertlat) insertlat.value = pos.lat.toFixed(6);
            if (insertlng) insertlng.value = pos.lng.toFixed(6);
        });
    }

    if (ubicar) {
        ubicar.addEventListener('click', async () => {
            if (!direccion.value.trim()) {
                alert('Por favor ingrese una dirección.');
                return;
            }

            const textoBusqueda = `${direccion.value}, ${departamento.value}, Uruguay`;
            const url = `https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(textoBusqueda)}&filter=countrycode:uy&lang=es&limit=1&format=json&apiKey=${API_KEY}`;

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

                    await actualizarRutaYCalculos();
                } else {
                    alert('No se encontraron resultados para la dirección especificada.');
                }
            } catch (error) {
                console.error('Error al buscar dirección:', error);
            }
        });
    }
    if (btnIniciar) {
        btnIniciar.addEventListener('click', async () => {
            trasladoActivo = true;

            const formData = new FormData();
            formData.append('accion', 'cambiar_estado');
            formData.append('id_traslado', ID_TRASLADO);
            formData.append('estado', 'en_proceso');
            formData.append('transporte', selectTransporte ? selectTransporte.value : 'drive');

            await fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });

            await actualizarRutaYCalculos();

            if (intervaloPolling) clearInterval(intervaloPolling);
            intervaloPolling = setInterval(monitorearTraslado, 3000);
        });
    }

    if (btnFinalizar) {
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
    }

    actualizarRutaYCalculos();

    setTimeout(() => {
        mapa.invalidateSize();
    }, 400);
});