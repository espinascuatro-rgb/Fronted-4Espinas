<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar insumo</title>
        <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/registroambu.css">
</head>
<body>
    <header class="Headerguia">
       <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h2>Guía de Tratamientos, Servicios y Experiencias de los Pacientes</h2>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>
    <main class="mainregistroambu>
        <section>
            <h3>Datos del insumo</h3>
                <p>
                    <label for="id_equipo_medico">Identificacion</label>
                    <input type="number" id="id_equipo_medico" name="id_equipo_medico" required>
                </p>
                <p>
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre">
                </p>
                <p>
                    <label for="insumos">Tipo de insumo</label>
                    <input type="text" id="insumos" name="insumos">
                </p>
        </section>

        <section> 
            <h3>Movimientos y logistica<h3>
                <p>
                    <label for="insumo_t">traslado de insumos</label>
                    <input type="number" id="insumo_t" name="insumo_t" required>
                </p>
                <p>
                    <label for="origen">origen</label>
                    <input type="text" id="origen" name="origen">
                </p>
                <p>
                    <label for="destino">destino</label>
                    <input type="text" id="destino" name="destino">
                </p>
        </section>
        <section> 
            <h3>Control de lotes y vencimientos<h3>
                <p>
                    <label for="numero">Numero de lotes</label>
                    <input type="number" id="number" name="number" required>
                </p>
                <p>
                    <label for="fecha">fecha de caducidad</label>
                    <input type="text" id="fecha" name="fecha">
                </p>
                <p>
                    <label for="registro">registro sanitario</label>
                    <input type="text" id="registro" name="registro">
                </p>
        </section>
</body>
        <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</html>