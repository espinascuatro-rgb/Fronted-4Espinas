<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

// Obtener traslados en proceso o pendientes para que el chofer seleccione
$sql_traslados = "SELECT t.*, a.matricula 
                  FROM traslado t 
                  LEFT JOIN ambulancia a ON t.matricula_ambulancia = a.numero_de_serie 
                  WHERE t.estado IN ('en_proceso', 'pendiente') 
                  ORDER BY t.id_traslado DESC";
$res_traslados = $con->query($sql_traslados);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modo Chofer GPS - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/guia.css">
    <link rel="stylesheet" href="../css/modo-chofer.css">
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas "Dr. Manuel Quintela"</h1>
        <p>Chofer: <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/ver-ruta.php">Ver Mapa</a>
            <a href="../pages/trazabilidad.php">Trazabilidad</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="mainguia">
        <section class="sectionguia contenedor-chofer-main">

            <h2 class="titulo-seccion">Panel del Chofer - Transmisión GPS</h2>

            <div class="card-chofer-panel">
                <div class="form-group-chofer">
                    <label for="selectTrasladoChofer"><strong>Seleccionar Traslado Asignado:</strong></label>
                    <select id="selectTrasladoChofer" class="input-select-chofer">
                        <option value="">Selecciona el Traslado </option>
                        <?php if ($res_traslados && $res_traslados->num_rows > 0): ?>
                            <?php while ($t = $res_traslados->fetch_assoc()): ?>
                                <option value="<?php echo $t['id_traslado']; ?>">
                                    Traslado #<?php echo $t['id_traslado']; ?> - Destino: <?php echo htmlspecialchars($t['destino'] ?? 'Sin destino'); ?> (Móvil: <?php echo htmlspecialchars($t['matricula_ambulancia'] ?? 'N/A'); ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="estado-gps-box">
                    <div class="fila-estado">
                        <strong>Estado GPS:</strong>
                        <span id="sttGpsText" class="badge-gps badge-off">Inactivo</span>
                    </div>
                    <div class="fila-estado">
                        <strong>Latitud:</strong>
                        <span id="txtLat"></span>
                    </div>
                    <div class="fila-estado">
                        <strong>Longitud:</strong>
                        <span id="txtLng"></span>
                    </div>
                    <div class="fila-estado">
                        <strong>Última actualización:</strong>
                        <span id="txtHoraGps"></span>
                    </div>
                </div>

                <div class="acciones-chofer-grid">
                    <button id="btnChoferGps" class="btn-transmitir-gps">Iniciar Transmisión GPS</button>
                    <button id="btnFinalizarTraslado" class="btn-finalizar-traslado" style="display: none;">Finalizar Traslado</button>
                </div>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Hospital de Clínicas</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../js/modo-chofer.js"></script>
</body>

</html>