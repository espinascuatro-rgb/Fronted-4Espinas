<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

// Consulta para obtener la trazabilidad completa de traslados
$sql_trazabilidad = "SELECT t.*, a.kilometraje, a.matricula 
                      FROM traslado t 
                      LEFT JOIN ambulancia a ON t.matricula_ambulancia = a.numero_de_serie 
                      ORDER BY t.id_traslado DESC";
$res_trazabilidad = $con->query($sql_trazabilidad);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trazabilidad de Traslados - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/guia.css">
    <link rel="stylesheet" href="../css/trazabilidad.css">
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
        <section class="sectionguia contenedor-trazabilidad-main">
            <div class="header-acciones-trazabilidad">
                <button class="btnguia1 btn-volver">
                    <a href="../pages/guia.php">← Volver al Inicio</a>
                </button>
                <div class="grupo-botones-nav">
                    <button class="btnguia6 btn-ver-mapa">
                        <a href="../pages/ver-ruta.php">Ver Mapa / Rutas</a>
                    </button>
                    <button class="btnguia4 btn-chofer-nav">
                        <a href="../pages/chofer-gps.php">Modo Chofer</a>
                    </button>
                </div>
            </div>

            <h2 class="titulo-seccion">Trazabilidad e Historial de Traslados</h2>

            <!-- Filtro de Búsqueda -->
            <div class="panel-filtro-trazabilidad">
                <input type="text" id="inputBuscarTrazabilidad" placeholder="Buscar por ID, destino o ambulancia..." class="input-buscar">
            </div>

            <!-- Tabla de Historial -->
            <div class="overflow-tabla">
                <table class="tabla-trazabilidad" id="tablaTrazabilidad">
                    <thead>
                        <tr>
                            <th>Nº Traslado</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Estado</th>
                            <th>Hora Salida</th>
                            <th>Estimada GPS</th>
                            <th>Ambulancia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_trazabilidad && $res_trazabilidad->num_rows > 0): ?>
                            <?php while ($row = $res_trazabilidad->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id_traslado']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['origen'] ?? 'Hospital de Clínicas'); ?></td>
                                    <td><?php echo htmlspecialchars($row['destino'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge-estado badge-<?php echo htmlspecialchars($row['estado'] ?? 'pendiente'); ?>">
                                            <?php echo strtoupper(str_replace('_', ' ', $row['estado'] ?? 'PENDIENTE')); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['hora_de_salida'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['hora_estimada_gps'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['matricula_ambulancia'] ?? 'N/A'); ?></td>
                                    <td>
                                        <button class="btn-ver-ruta-tabla">
                                            <a href="../pages/ver-ruta.php?id=<?php echo $row['id_traslado']; ?>">Ver en Mapa</a>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="td-vacio">No hay traslados registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../js/trazabilidad.js"></script>
</body>

</html>