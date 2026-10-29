<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital de clínicas</title>
    <link rel="stylesheet" href="../css/guia.css">
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas "Dr. Manuel Quintela" </h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>
    <main class="mainguia">
        <section class="sectionguia">
            <button class="btnguia1"><a href="../pages/tratamientos.php">Información del hospital</a></button>
            <p>Aquí esta la información del hospital.</p>

            <button class="btnguia1" style="background-color: #007bff;"><a href="../pages/administracion-pacientes.php" style="color: white;">Administración de pacientes</a></button>
            <p>Agregar y eliminar pacientes del sistema.</p>

            <button class="btnguia1" style="background-color: #8b5cf6;"><a href="../pages/gestion-organos.php" style="color: white;">Gestión de Órganos</a></button>
            <p>Registro y control de ablación, conservación y transporte de órganos.</p>

            <button class="btnguia2"><a href="../pages/administracion-de-documentos.php">Ver Documentos</a></button>
            <p>Aquí para ver documentos</p>

            <button class="btnguia3"><a href="../pages/encuesta.php">Resultados Encuestas</a></button>
            <p>Aquí para ver los resultados de las encuestas</p>
        </section>
        <section id="sectionguia2">
            <h2>Visualización en el mapa</h2>
            <img src="../fotos-videos/hospital-de-clinicas.jpg" alt="Imagen del Hospital de Clínicas" height="300" width="400" style="object-fit: cover; border-radius: 8px;">
            <button class="btnguia4"><a href="../pages/registro-ambulancia.php">Registro Ambulancia</a></button>
            <button class="btnguia5"><a href="../pages/trazabilidad.php">Trazabilidad</a></button>
            <button class="btnguia6"><a href="../pages/ver-ruta.php">Ver Mapa/Rutas</a></button>
        </section>
    </main>
    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</body>

</html>