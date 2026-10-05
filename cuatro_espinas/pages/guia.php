 <?php require_once __DIR__ . '/../php/checksesion.php'; ?> <!--llama al script que verifica si el usuario esta verificado en la sesion. -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital de clinicas</title>
    <link rel="stylesheet" href="../css/guia.css">
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas "Dr. Manuel Quintela" </h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p> <!-- muestra el nombre del usuario verificado en la sesion. -->
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>
    <main class="mainguia">
        <section class="sectionguia">
            <button class="btnguia1"><a href="../pages/tratamientos.php">Registros</a></button>
            <p>Aqui es la pagina de registro.</p>
            <button class="btnguia2"><a href="../pages/administracion-de-documentos.php">Ver Documentos</a></button>
            <p>Aqui par ver documentos</p>
            <button class="btnguia3"><a href="../pages/encuesta.php">Resultados Encuestas</a></button>
            <p>Aqui para ver los resultados de las encuestas</p>
        </section>
        <section id="sectionguia2">
            <h2>Visualización en el mapa</h2>
            <img src="" height="300" width="400"> <!-- Como no tenemos el mapa todavia, se deja el espacio para el con un img vacio -->
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