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

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet-fullscreen.css" />
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Visualización en GPS</h1>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="chofer-gps.php">Modo Chofer (GPS)</a>
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
                        <p><strong>Número de Traslado: </strong> <span id="txtId"></span></p>
                        <p><strong>Origen: </strong> <span id="txtOrigen"> Hospital de Clínicas</span></p>
                        <p><strong>Destino: </strong> <span id="txtDestino"></span></p>
                        <p><strong>Estado:</strong> <span id="txtEstado" class="badge-estado"> Sin Selección</span></p>
                        <p><strong>Distancia estimada: </strong> <span id="txtDistancia"> km</span></p>
                        <p><strong>Hora Salida: </strong> <span id="txtSalida"></span></p>
                        <p><strong>Llegada Estimada: </strong> <span id="txtEstimada"></span></p>
                        <p><strong>Unidad Ambulancia: </strong> <span id="txtAmbulancia"></span></p>
                    </div>
                </div>

                <div class="tarjeta-mapa-box" id="mapaBox">
                    <div id="mapaRuta" class="mapa-contenedor"></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <!-- Scripts de Leaflet y Routing Machine v3.2.12 -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="../js/ver-ruta.js"></script>
</body>

</html>