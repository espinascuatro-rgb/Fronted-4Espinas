<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuestas de Satisfacción - Hospital de Clínicas</title>
    <!-- Se incluye ÚNICAMENTE este CSS para evitar colisiones globales -->
    <link rel="stylesheet" href="../css/encuesta.css">
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <div class="header-texto">
            <h1>Hospital de Clínicas "Dr. Manuel Quintela"</h1>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        </div>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="mainguia">
        <section class="sectionguia contenedor-encuesta-main">
            <div class="header-encuesta-top">
                <a href="../pages/guia.php" class="btn-volver-atras">← Volver al Inicio</a>
            </div>

            <h2 class="titulo-seccion">Tu opinión nos ayuda a mejorar</h2>
            <p class="subtitulo-encuesta">Escanea el código QR o haz clic en el botón azul para abrir la encuesta directamente en Google Forms.</p>

            <!-- Botones superiores en Azul Oscuro -->
            <div class="selector-encuestas-nav">
                <a href="#encuesta-traslado" class="btn-select-encuesta">1. Encuesta Traslado</a>
                <a href="#encuesta-hospital" class="btn-select-encuesta">2. Encuesta Hospital</a>
                <a href="#encuesta-funcionario" class="btn-select-encuesta">3. Encuesta Funcionarios</a>
            </div>

            <!-- Estructura de 3 columnas -->
            <div class="grid-tres-columnas">
                <!-- Columna 1: Traslado -->
                <article id="encuesta-traslado" class="tarjeta-encuesta-col">
                    <span class="badge-categoria">Traslados</span>
                    <h3>Encuesta de Traslado</h3>
                    <div class="box-qr-img">
                        <img src="../fotos-videos/Encuesta-Traslado.png" alt="QR Encuesta Traslado" class="qr-encuesta-img" width="100" height="100">
                    </div>
                    <p class="desc-encuesta">Evaluación sobre el servicio de ambulancias, tiempos de respuesta y atención médica durante el traslado.</p>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSeVwcUk_ZKY7ZrNLHozyzYkB_LV4Yx3rVbTzo5mkWUM8doiaQ/viewform?usp=sharing&ouid=114919482076470136106" target="_blank" class="btn-completar-encuesta">Abrir Formulario Traslado ↗</a>
                </article>

                <!-- Columna 2: Hospital -->
                <article id="encuesta-hospital" class="tarjeta-encuesta-col">
                    <span class="badge-categoria">Hospital General</span>
                    <h3>Encuesta del Hospital</h3>
                    <div class="box-qr-img">
                        <img src="../fotos-videos/Encuesta-Hospital.png" alt="QR Encuesta Hospital" class="qr-encuesta-img" width="100" height="100">
                    </div>
                    <p class="desc-encuesta">Evaluación de la atención médica general, confort e higiene de las instalaciones hospitalarias.</p>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSdeZQmWWZSXiLMmZY2d3Di4uRXOjXw-9N0h9tMx0oVRgDN4oA/viewform?usp=sharing&ouid=114919482076470136106" target="_blank" class="btn-completar-encuesta">Abrir Formulario Hospital ↗</a>
                </article>

                <!-- Columna 3: Funcionario -->
                <article id="encuesta-funcionario" class="tarjeta-encuesta-col">
                    <span class="badge-categoria">Personal Interno</span>
                    <h3>Encuesta a Funcionarios</h3>
                    <div class="box-qr-img">
                        <img src="../fotos-videos/Encuesta-Funcionarios.png" alt="QR Encuesta Funcionarios" class="qr-encuesta-img" width="100" height="100">
                    </div>
                    <p class="desc-encuesta">Formulario exclusivo para la evaluación del personal e insumos hospitalarios.</p>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSdUcCmLNxYndn0d5CfGckFwLEiE_GYDVxHZ5XfGUy7cLaYRHA/viewform?usp=sharing&ouid=114919482076470136106" target="_blank" class="btn-completar-encuesta">Abrir Formulario Funcionarios ↗</a>
                </article>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../js/encuesta.js"></script>
</body>

</html>