<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

$sql_traslados = "SELECT t.*, a.matricula 
                  FROM traslado t 
                  LEFT JOIN ambulancia a ON t.matricula_ambulancia = a.numero_de_serie 
                  ORDER BY t.id_traslado DESC";
$res_traslados = $con->query($sql_traslados);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo GPS - Ver Ruta</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/Ver_ruta.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link href="https://unpkg.com/leaflet.fullscreen@latest/Control.FullScreen.css" rel="stylesheet" />
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Visualización en GPS</h1>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/chofer-gps.php">Modo Chofer (GPS)</a>
            <a href="../pages/trazabilidad.php">Volver a Trazabilidad</a>
        </nav>
    </header>

    <main class="mainguia">
        <section class="sectionguia contenedor-ruta-main">
            <div class="header-acciones-ruta">
                <div class="grupo-botones-nav">
                    <button class="btnguia4 btn-chofer-nav">
                        <a href="../pages/chofer-gps.php">Modo Chofer</a>
                    </button>
                    <button class="btnguia5 btn-trazabilidad">
                        <a href="../pages/trazabilidad.php">Ver Trazabilidad</a>
                    </button>
                </div>
            </div>

            <h2 class="titulo-seccion">Seguimiento y Visualización de Rutas</h2>

            <div class="panel-ruta-grid">
                <div class="tarjeta-controles-ruta">
                    <h3>Selección de Traslado</h3>
                    <div class="form-group-ruta">
                        <label for="selectTraslado"><strong>Selecciona un traslado Activo:</strong></label>
                        <select id="selectTraslado" class="input-select-ruta">
                            <option value=""> Selecciona un traslado </option>
                            <?php if ($res_traslados && $res_traslados->num_rows > 0): ?>
                                <?php while ($t = $res_traslados->fetch_assoc()): ?>
                                    <option value="<?php echo $t['id_traslado']; ?>" 
                                            data-origen="<?php echo htmlspecialchars($t['origen'] ?? 'Hospital de Clínicas'); ?>"
                                            data-destino="<?php echo htmlspecialchars($t['destino'] ?? 'Av. Italia 2870'); ?>"
                                            data-estado="<?php echo htmlspecialchars($t['estado'] ?? 'pendiente'); ?>"
                                            data-lat="<?php echo htmlspecialchars($t['lat_chofer'] ?? '-34.8883'); ?>"
                                            data-lng="<?php echo htmlspecialchars($t['lng_chofer'] ?? '-56.1561'); ?>"
                                            data-salida="<?php echo htmlspecialchars($t['hora_de_salida'] ?? 'N/A'); ?>"
                                            data-estimada="<?php echo htmlspecialchars($t['hora_estimada_gps'] ?? 'N/A'); ?>"
                                            data-ambulancia="<?php echo htmlspecialchars($t['matricula_ambulancia'] ?? 'N/A'); ?>">
                                        Traslado #<?php echo $t['id_traslado']; ?> - <?php echo htmlspecialchars($t['destino'] ?? 'Destino'); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div id="infoTraslado" class="info-traslado-card">
                        <p><strong>Número de Traslado: </strong> <span id="txtId">-</span></p>
                        <p><strong>Origen: </strong> <span id="txtOrigen">Hospital de Clínicas</span></p>
                        <p><strong>Destino: </strong> <span id="txtDestino">-</span></p>
                        <p><strong>Estado:</strong> <span id="txtEstado" class="badge-estado">Sin Selección</span></p>
                        <p><strong>Distancia estimada: </strong> <span id="txtDistancia">0.00 km</span></p>
                        <p><strong>Hora Salida: </strong> <span id="txtSalida">-</span></p>
                        <p><strong>Llegada Estimada: </strong> <span id="txtEstimada">-</span></p>
                        <p><strong>Unidad Ambulancia: </strong> <span id="txtAmbulancia">-</span></p>
                    </div>
                </div>

                <div class="tarjeta-mapa-box" style="width: 100%; min-height: 520px; display: block;">
                    <div id="mapaRuta" class="mapa-contenedor" style="width: 100%; height: 520px; min-height: 520px; display: block; position: relative;"></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/leaflet.fullscreen@latest/Control.FullScreen.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const latOrigenDefault = -34.8883;
            const lngOrigenDefault = -56.1561;
            const latDestinoDefault = -34.8812;
            const lngDestinoDefault = -56.1420;

            const contenedorMapa = document.getElementById('mapaRuta');
            if (!contenedorMapa) return;

            setTimeout(() => {
                const mapa = L.map('mapaRuta', {
                    zoomControl: true,
                    fullscreenControl: true,
                    fullscreenControlOptions: {
                        position: 'topleft',
                        title: 'Ver en pantalla completa',
                        titleCancel: 'Salir de pantalla completa'
                    }
                }).setView([latOrigenDefault, lngOrigenDefault], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(mapa);

                mapa.invalidateSize();

                const iconoMapo2 = L.icon({
                    iconUrl: '../mape/mapo2.png',
                    iconSize: [38, 38],
                    iconAnchor: [19, 38],
                    popupAnchor: [0, -38]
                });

                const iconoMapo1 = L.icon({
                    iconUrl: '../mape/mapo1.png',
                    iconSize: [38, 38],
                    iconAnchor: [19, 38],
                    popupAnchor: [0, -38]
                });

                let controlRuta = null;

                controlRuta = L.Routing.control({
                    waypoints: [
                        L.latLng(latOrigenDefault, lngOrigenDefault),
                        L.latLng(latDestinoDefault, lngDestinoDefault)
                    ],
                    routeWhileDragging: true,
                    addWaypoints: false,
                    draggableWaypoints: true,
                    show: false,
                    createMarker: function(i, wp, nWps) {
                        let iconoUsar = (i === 0) ? iconoMapo2 : iconoMapo1;
                        let tituloPopup = (i === 0) ? "<b>Origen (Arrastra para mover)</b>" : "<b>Destino (Arrastra para mover)</b>";
                        
                        return L.marker(wp.latLng, {
                            draggable: true,
                            icon: iconoUsar
                        }).bindPopup(tituloPopup);
                    },
                    lineOptions: {
                        styles: [
                            { color: '#2563eb', opacity: 0.9, weight: 7 } 
                        ]
                    }
                }).addTo(mapa);

                controlRuta.on('routesfound', function(e) {
                    const resumen = e.routes[0].summary;
                    const minutos = Math.round(resumen.totalTime / 60);
                    const km = (resumen.totalDistance / 1000).toFixed(1);

                    const elemDist = document.getElementById('txtDistancia');
                    if (elemDist) {
                        elemDist.textContent = `${km} km (${minutos} min)`;
                    }
                });

                const selectTraslado = document.getElementById('selectTraslado');
                if (selectTraslado) {
                    selectTraslado.addEventListener('change', (e) => {
                        const op = e.target.options[e.target.selectedIndex];
                        if (!op.value) return;

                        const lat = parseFloat(op.getAttribute('data-lat'));
                        const lng = parseFloat(op.getAttribute('data-lng'));

                        document.getElementById('txtId').textContent = '#' + op.value;
                        document.getElementById('txtOrigen').textContent = op.getAttribute('data-origen');
                        document.getElementById('txtDestino').textContent = op.getAttribute('data-destino');
                        document.getElementById('txtSalida').textContent = op.getAttribute('data-salida');
                        document.getElementById('txtEstimada').textContent = op.getAttribute('data-estimada');
                        document.getElementById('txtAmbulancia').textContent = 'Unidad N° ' + op.getAttribute('data-ambulancia');

                        const estado = op.getAttribute('data-estado');
                        const txtEstado = document.getElementById('txtEstado');
                        if (txtEstado) {
                            txtEstado.textContent = estado.replace('_', ' ').toUpperCase();
                            txtEstado.className = 'badge-estado badge-' + estado;
                        }

                        if (!isNaN(lat) && !isNaN(lng)) {
                            controlRuta.setWaypoints([
                                L.latLng(latOrigenDefault, lngOrigenDefault),
                                L.latLng(lat, lng)
                            ]);
                            setTimeout(() => mapa.invalidateSize(), 150);
                        }
                    });
                }
            }, 300);
        });
    </script>
</body>
</html>