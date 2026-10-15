<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';
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
    <!-- Leaflet CSS para mapas interactivos -->
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
            <button class="btnguia1 btn-volver">
                <a href="../pages/guia.php">← Volver al Inicio</a>
            </button>

            <h2 class="titulo-seccion">Seguimiento y Visualización de Rutas</h2>

            <div class="panel-ruta-grid">
                <div class="tarjeta-controles-ruta">
                    <h3>Selección de Traslado</h3>
                    <div class="form-group-ruta">
                        <label for="selectTraslado"><strong>Traslado Activo:</strong></label>
                        <select id="selectTraslado" class="input-select-ruta">
                            <option value="">-- Cargar ruta en tiempo real --</option>
                            <option value="1">Traslado #1 - Hospital de Clínicas -> Av. Italia 2870</option>
                        </select>
                    </div>

                    <div id="infoTraslado" class="info-traslado-card">
                        <p><strong>Origen:</strong> <span id="txtOrigen">Hospital de Clínicas</span></p>
                        <p><strong>Destino:</strong> <span id="txtDestino">Av. Italia 2870</span></p>
                        <p><strong>Estado:</strong> <span id="txtEstado" class="badge-estado">En Proceso</span></p>
                        <p><strong>Tiempo Estimado:</strong> <span id="txtTiempo">-- hs</span></p>
                    </div>
                </div>

                <div class="tarjeta-mapa-box">
                    <div id="mapaRuta" class="mapa-contenedor"></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <!-- Leaflet JS y Script de Ruta -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../js/ver-ruta.js"></script>
</body>

</html>