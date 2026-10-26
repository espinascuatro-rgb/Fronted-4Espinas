<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuestas de Satisfacción - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/encuesta.css">
    <link rel="stylesheet" href="../css/elementosC.css">
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <div class="header-texto">
            <h1>Encuestas</h1>
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
            </div>

            <h2 class="titulo-seccion">Tu opinión nos ayuda a mejorar</h2>
            <p class="subtitulo-encuesta">Escanea los codigos QR o haz click en los botones azul para abrir aguna de las encuestas. </p>

            <div class="grid-tres-columnas">
                <article id="encuesta-traslado" class="tarjeta-encuesta-col">
                    <span class="badge-categoria">Traslados</span>
                    <h3>Encuesta de Traslado</h3>
                    <div class="box-qr-img">
                        <img src="../fotos-videos/Encuesta-Traslado.png" alt="QR Encuesta Traslado" class="qr-encuesta-img" width="100" height="100">
                    </div>
                    <p class="desc-encuesta">Evaluación sobre el servicio de ambulancias, tiempos de respuesta y atención médica durante el traslado.</p>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSeVwcUk_ZKY7ZrNLHozyzYkB_LV4Yx3rVbTzo5mkWUM8doiaQ/viewform?usp=sharing&ouid=114919482076470136106" target="_blank" class="btn-completar-encuesta">Abrir Formulario Traslado ↗</a>
                </article>

                <article id="encuesta-hospital" class="tarjeta-encuesta-col">
                    <span class="badge-categoria">Hospital General</span>
                    <h3>Encuesta del Hospital</h3>
                    <div class="box-qr-img">
                        <img src="../fotos-videos/Encuesta-Hospital.png" alt="QR Encuesta Hospital" class="qr-encuesta-img" width="100" height="100">
                    </div>
                    <p class="desc-encuesta">Evaluación de la atención médica general, confort e higiene general de las instalaciones hospitalarias.</p>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSdeZQmWWZSXiLMmZY2d3Di4uRXOjXw-9N0h9tMx0oVRgDN4oA/viewform?usp=sharing&ouid=114919482076470136106" target="_blank" class="btn-completar-encuesta">Abrir Formulario Hospital ↗</a>
                </article>

                <article id="encuesta-funcionario" class="tarjeta-encuesta-col">
                    <span class="badge-categoria">Personal Interno</span>
                    <h3>Encuesta a Funcionarios</h3>
                    <div class="box-qr-img">
                        <img src="../fotos-videos/Encuesta-Funcionarios.png" alt="QR Encuesta Funcionarios" class="qr-encuesta-img" width="100" height="100">
                    </div>
                    <p class="desc-encuesta">Formulario exclusivo para la evaluación de todo el personal que lo atendio y la calidad de insumos hospitalarios.</p>
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