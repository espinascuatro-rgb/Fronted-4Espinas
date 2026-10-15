<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

// Obtener lista de traslados para el panel y sincronizar con Trazabilidad y Modo Chofer
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
    <title>Visualización de Rutas y Mapa - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/guia.css">
    <link rel="stylesheet" href="../css/ver-ruta.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas "Dr. Manuel Quintela"</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="mainguia">
        <section class="sectionguia contenedor-ruta-main">
            <div class="header-acciones-ruta">
                <button class="btnguia1 btn-volver">
                    <a href="../pages/guia.php">← Volver al Inicio</a>
                </button>
                <button class="btnguia5 btn-trazabilidad">
                    <a href="../pages/trazabilidad.php">Ver Trazabilidad</a>
                </button>
            </div>

            <h2 class="titulo-seccion">Seguimiento y Visualización de Rutas en Tiempo Real</h2>

            <div class="panel-ruta-grid">
                <!-- Panel Lateral -->
                <div class="tarjeta-controles-ruta">
                    <h3>Selección de Traslado</h3>
                    <div class="form-group-ruta">
                        <label for="selectTraslado"><strong>Seleccionar Traslado Activo:</strong></label>
                        <select id="selectTraslado" class="input-select-ruta">
                            <option value="">-- Seleccione un traslado --</option>
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
                        <p><strong>Nº Traslado:</strong> <span id="txtId">--</span></p>
                        <p><strong>Origen:</strong> <span id="txtOrigen">Hospital de Clínicas</span></p>
                        <p><strong>Destino:</strong> <span id="txtDestino">--</span></p>
                        <p><strong>Estado:</strong> <span id="txtEstado" class="badge-estado">Sin Selección</span></p>
                        <p><strong>Distancia estimada:</strong> <span id="txtDistancia">-- km</span></p>
                        <p><strong>Hora Salida:</strong> <span id="txtSalida">--</span></p>
                        <p><strong>Llegada Estimada:</strong> <span id="txtEstimada">--</span></p>
                        <p><strong>Unidad Ambulancia:</strong> <span id="txtAmbulancia">--</span></p>
                    </div>

                    <!-- Modo Chofer para transmitir ubicación GPS -->
                    <div class="bloque-modo-chofer">
                        <h3>Modo Chofer (GPS)</h3>
                        <p class="desc-chofer">Transmite la ubicación actual de la ambulancia al servidor en tiempo real.</p>
                        <button id="btnModoChofer" class="btn-chofer">Iniciar Transmisión GPS</button>
                        <span id="sttChofer" class="stt-chofer-off">Desconectado</span>
                    </div>
                </div>

                <!-- Contenedor del Mapa -->
                <div class="tarjeta-mapa-box" id="mapaBox">
                    <div id="mapaRuta" class="mapa-contenedor"></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <!-- Leaflet JS y Script de Control -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../js/ver-ruta.js"></script>
</body>

</html>