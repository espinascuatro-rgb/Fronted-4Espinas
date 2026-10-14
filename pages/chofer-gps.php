<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Chofer - Traslado activo</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; max-width: 500px; margin: 0 auto; }
        .campo { text-align: left; margin-bottom: 15px; }
        .campo label { display: block; font-weight: bold; margin-bottom: 5px; }
        .campo input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { padding: 15px 25px; font-size: 18px; border: none; border-radius: 5px; color: white; cursor: pointer; width: 100%; margin-top: 10px; font-weight: bold; }
        .btn-activar { background-color: #28a745; }
        .btn-desactivar { background-color: #dc3545; }
        #estado { font-weight: bold; margin-top: 15px; color: #555; }
    </style>
</head>
<body>
    <h2>Modo Chofer - Enviar Ubicación GPS</h2>

    <div class="campo">
        <label for="txtChofer">Nombre del Chofer:</label>
        <input type="text" id="txtChofer" placeholder="Ej: Mateo">
    </div>

    <div class="campo">
        <label for="txtAcompanante">Nombre del Acompañante (Opcional):</label>
        <input type="text" id="txtAcompanante" placeholder="Ej: Laura">
    </div>

    <div class="campo">
        <label for="txtAmbulancia">Unidad / Ambulancia:</label>
        <input type="text" id="txtAmbulancia" placeholder="Ej: Móvil 3">
    </div>

    <button id="btnActivar" class="btn btn-activar">Compartir Ubicación en Tiempo Real</button>
    <button id="btnDesactivar" class="btn btn-desactivar" style="display:none;">Detener Compartir</button>

    <p id="estado">Ubicación desactivada</p>

    <script>
        const ID_TRASLADO = 1;
        let watchId = null;

        const btnActivar = document.getElementById('btnActivar');
        const btnDesactivar = document.getElementById('btnDesactivar');
        const estado = document.getElementById('estado');

        const txtChofer = document.getElementById('txtChofer');
        const txtAcompanante = document.getElementById('txtAcompanante');
        const txtAmbulancia = document.getElementById('txtAmbulancia');

        async function guardarDatosIniciales() {
            const formData = new FormData();
            formData.append('accion', 'guardar_datos_chofer');
            formData.append('id_traslado', ID_TRASLADO);
            formData.append('chofer', txtChofer.value.trim());
            formData.append('acompanante', txtAcompanante.value.trim());
            formData.append('ambulancia', txtAmbulancia.value.trim());

            await fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });
        }

        btnActivar.addEventListener('click', async () => {
            if (!txtChofer.value.trim() || !txtAmbulancia.value.trim()) {
                alert("Por favor, ingrese el nombre del chofer y la unidad de ambulancia.");
                return;
            }

            await guardarDatosIniciales();

            if ("geolocation" in navigator) {
                watchId = navigator.geolocation.watchPosition(
                    async (posicion) => {
                        const lat = posicion.coords.latitude;
                        const lng = posicion.coords.longitude;

                        const formData = new FormData();
                        formData.append('accion', 'actualizar_chofer');
                        formData.append('id_traslado', ID_TRASLADO);
                        formData.append('lat', lat);
                        formData.append('lng', lng);

                        await fetch('../php/actualizar_posicion.php', { method: 'POST', body: formData });

                        estado.innerText = `Transmitiendo GPS: Lat ${lat.toFixed(5)}, Lng ${lng.toFixed(5)}`;
                        estado.style.color = '#28a745';

                        btnActivar.style.display = 'none';
                        btnDesactivar.style.display = 'block';
                    },
                    (err) => {
                        alert("Error al obtener la ubicación: " + err.message);
                    },
                    { enableHighAccuracy: true, timeout: 5000, maximumAge: 1000 }
                );
            } else {
                alert("Tu dispositivo no soporta geolocalización.");
            }
        });

        btnDesactivar.addEventListener('click', () => {
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
                estado.innerText = "Ubicación desactivada";
                estado.style.color = "#dc3545";
                btnActivar.style.display = 'block';
                btnDesactivar.style.display = 'none';
            }
        });
    </script>
</body>
</html>