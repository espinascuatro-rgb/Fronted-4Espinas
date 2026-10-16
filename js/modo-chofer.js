document.addEventListener('DOMContentLoaded', () => {
    const selectTraslado = document.getElementById('selectTrasladoChofer');
    const btnGps = document.getElementById('btnChoferGps');
    const btnFinalizar = document.getElementById('btnFinalizarTraslado');
    const sttText = document.getElementById('sttGpsText');
    const txtLat = document.getElementById('txtLat');
    const txtLng = document.getElementById('txtLng');
    const txtHoraGps = document.getElementById('txtHoraGps');

    let watchId = null;

    async function enviarCoordenadasServidor(idTraslado, lat, lng) {
        try {
            const res = await fetch('../php/actualizar_posicion.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `accion=actualizar_gps&id_traslado=${encodeURIComponent(idTraslado)}&lat=${encodeURIComponent(lat)}&lng=${encodeURIComponent(lng)}`
            });
            const json = await res.json();
            if (json.status !== 'ok') {
                console.warn('Advertencia del servidor al enviar GPS:', json.msg);
            }
        } catch (e) {
            console.error('Error al enviar coordenadas al servidor:', e);
        }
    }

    if (btnGps) {
        btnGps.addEventListener('click', () => {
            const idTraslado = selectTraslado.value;
            if (!idTraslado) {
                alert('Por favor, seleccione un traslado asignado antes de iniciar la transmisión GPS.');
                return;
            }

            if (watchId === null) {
                if ('geolocation' in navigator) {
                    watchId = navigator.geolocation.watchPosition((pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        const hora = new Date().toLocaleTimeString();

                        txtLat.textContent = lat.toFixed(6);
                        txtLng.textContent = lng.toFixed(6);
                        txtHoraGps.textContent = hora;

                        sttText.textContent = 'Transmitiendo en Vivo';
                        sttText.className = 'badge-gps badge-on';

                        btnGps.textContent = 'Detener Transmisión GPS';
                        btnGps.classList.add('activo');
                        if (btnFinalizar) btnFinalizar.style.display = 'block';

                        // Enviar coordenadas actualizadas al servidor
                        enviarCoordenadasServidor(idTraslado, lat, lng);
                    }, (err) => {
                        alert('Error al obtener la ubicación GPS: ' + err.message);
                    }, {
                        enableHighAccuracy: true,
                        maximumAge: 0,
                        timeout: 10000
                    });
                } else {
                    alert('El navegador no soporta la API de Geolocalización GPS.');
                }
            } else {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
                txtLat.textContent = '--';
                txtLng.textContent = '--';
                txtHoraGps.textContent = '--';

                sttText.textContent = 'Inactivo';
                sttText.className = 'badge-gps badge-off';

                btnGps.textContent = 'Iniciar Transmisión GPS';
                btnGps.classList.remove('activo');
                if (btnFinalizar) btnFinalizar.style.display = 'none';
            }
        });
    }

    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', async () => {
            const idTraslado = selectTraslado.value;
            if (!idTraslado) return;

            if (confirm('¿Está seguro de dar por finalizado este traslado?')) {
                try {
                    const res = await fetch('../php/actualizar_posicion.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `accion=finalizar_traslado&id_traslado=${encodeURIComponent(idTraslado)}`
                    });
                    const json = await res.json();

                    if (json.status === 'ok') {
                        alert('El traslado ha sido finalizado correctamente.');
                        if (watchId !== null) {
                            navigator.geolocation.clearWatch(watchId);
                            watchId = null;
                        }
                        window.location.reload();
                    } else {
                        alert('Error al finalizar el traslado: ' + json.msg);
                    }
                } catch (e) {
                    console.error('Error al finalizar el traslado:', e);
                }
            }
        });
    }
});