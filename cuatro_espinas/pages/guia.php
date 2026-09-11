<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital de clinicas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body id="bodyguia">
    <header id="Headerguia" class="encabezado-app">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas "Dr. Manuel Quintela" </h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        <nav class="nav-app">
            <a href="../pages/guia.php" class="activo">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>
    <main id="mainguia">
        <section id="sectionguia">
            <h2>Guía para los pacientes</h2>
            <p>Obten una guía detallada de los servicios que brindamos</p>
            <button class="btn-primary"><a href="../pages/tratamientos.php">Registros</a></button>
             <h2>Administra documentos </h2>
            <p>Si eres funcionarios puedes cargar archivos</p>
            <button class="btn-secondary" style="margin-top: 10px;"><a href="../pages/administracion-de-documentos.php">Ver Documentos</a></button>
        </section>
        <section id="sectionguia2">
            <h2>Trazabilidad ambulancia</h2>
             <p>Mira el estado actual de los traslados y las antiguas peticiones</p>
            <button class="btn-primary"><a href="../pages/trazabilidad.php">Redirijete aqui</a></button>
            <h2>Visualización en el mapa</h2>
            <p>Mira en tiempo real todo lo que esta pasando</p>
            <button class="btn-secondary" style="margin-top: 10px;"><a href="../pages/ver-ruta.php">Ver Mapa/Rutas</a></button>
        </section>
        <section id="sectionguia3">
            <h2>Registro de ambulancia</h2>
            <p>Añade los vehiculos nuevos (involucrados en traslados)</p>
            <button class="btn-primary"><a href="../pages/registro-ambulancia.php">Redirijete aqui</a></button>

            <h2>Encuestas de satisfacción</h2>
            <p>Visualiza las encuestas respondidas por usuarios</p>
            <button class="btn-secondary" style="margin-top: 10px;"><a href="../pages/encuesta.php">Resultados Encuestas</a></button>
        </section>
    </main>
    <footer id="footerguia" class="pie-app">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</body>

</html>