<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trazabilidad - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/trazabilidad.css">
</head>
<body>
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h2>Trazabilidad de Pacientes y Ambulancias</h2>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ver-ruta.php">GPS Ver Ruta</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="maintrazabilidad">
        <section id="traslado1" class="tarjeta"> 
            <p><strong>Buscar código de traslado:</strong></p>
            <div class="search-container">
                <input type="number" id="inputBuscarId" placeholder="Ingrese ID (Ej: 1)" value="1" class="search-input">
                <button type="button" id="btnRastrear" class="btn-primary">Rastrear</button>
            </div>
        </section>
        
        <section class="tarjeta">
            <div class="tarjeta-header">
                <h2>Estado del Traslado #<span id="lblIdTraslado">1</span></h2>
                <span id="badgeEstado" class="badge badge-pendiente">Cargando...</span>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <strong>Ambulancia / Unidad:</strong>
                    <div id="lblAmbulancia" class="info-value">Cargando...</div>
                </div>
                <div class="info-card">
                    <strong>Chofer Asignado:</strong>
                    <div id="lblChofer" class="info-value">Cargando...</div>
                </div>
                <div class="info-card">
                    <strong>Acompañante:</strong>
                    <div id="lblAcompanante" class="info-value">Sin acompañante</div>
                </div>
            </div>

            <hr class="separator">
            
            <h3>Monitoreo de Estado</h3>
            <ul class="timeline-list">
                <li id="step1" class="timeline-item">
                    <strong>Estado del GPS:</strong> <span id="lblEstadoTexto">Esperando transmisión...</span>
                </li>
            </ul>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../Fronted-4Espinas/js/trazabilidad.js"></script>
</body>
</html>