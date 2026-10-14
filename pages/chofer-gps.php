<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Chofer - Traslado activo</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; }
        .btn { padding: 15px 25px; font-size: 18px; border: none; border-radius: 5px; color: white; cursor: pointer; width: 100%; margin-top: 10px;}
        .btn-activar { background-color: #28a745; }
        .btn-desactivar { background-color: #dc3545; }
        #estado { font-weight: bold; margin-top: 15px; color: #555; }
    </style>
</head>
<body>
    <h2>Modo Chofer - Enviar Ubicación GPS</h2>
    <p>Asegúrate de permitir el acceso a tu ubicación.</p>

    <button id="btnActivar" class="btn btn-activar">Compartir Ubicación en Tiempo Real</button>
    <button id="btnDesactivar" class="btn btn-desactivar" style="display:none;">Detener Compartir</button>

    <p id="estado">Ubicación desactivada</p>

    <script>
        const ID_TRASLADO = 1; // ID del traslado actual
        let watchId = null;

        const btnActivar = document.getElementById('btnActivar');
        const btnDesactivar = document.getElementById('btnDesactivar');
        const estado = document.getElementById('estado');

        btnActivar.addEventListener('click', () => {
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
                        btnDesactivar.style.display = 'inline-block';
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
                btnActivar.style.display = 'inline-block';
                btnDesactivar.style.display = 'none';
            }
        });
    </script>
</body>
</html>