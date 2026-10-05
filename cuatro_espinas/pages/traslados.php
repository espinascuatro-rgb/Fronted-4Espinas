<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital de traslados</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header id="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas "Dr. Manuel Quintela"</h1>
        <h2>Sistema de traslados y trazabilidad</h2>
        <nav class="nav-botonesguia">
            <button class="btng"><a href="../pages/guia.php">Inicio</a></button>
            <button class="btng"><a href="../pages/tratamientos.php">Pacientes</a></button>
            <button class="btng"><a href="../pages/traslados.php">Ambulancias</a></button>
            <button class="btng"><a href="../pages/ajustes.php">Ajustes</a></button>
        </nav>
    </header>
<main>
    <section id="traslado1"> 
      
        <form action="../php/buscar_paciente.php" method="GET">
            <p>Buscar paciente:</p>
            <input type="text" name="busqueda_paciente" placeholder="Ingrese nombre o ID" required>
            <button type="submit">Buscar</button>
        </form>
    </section>
    
    <section id="traslado2">
        <h1>Datos del paciente</h1>
        <label>Nombre:</label> <input type="text" readonly><br><br>
        <label>ID:</label> <input type="text" readonly><br><br>
        <label>Edad:</label> <input type="text" readonly><br><br>
        <label>Habitación:</label> <input type="text" readonly><br><br>
        <label>Estado:</label> <input type="text" readonly><br><br>
    </section>

    <section id="traslado3">
        <h3>Registrar traslado</h3>
     
        <form action="../php/registrar_traslado.php" method="POST">
            <label>Origen:</label>
            <select name="origen">
                <option value="Urgencias">Urgencias</option>
                <option value="Habitacion">Habitación</option>
                <option value="Rayos X">Rayos X</option>
                <option value="Laboratorio">Laboratorio</option>
                <option value="Quirofano">Quirófano</option>
            </select>
            <br><br>

            <label>Destino:</label>
            <select name="destino">
                <option value="Urgencias">Urgencias</option>
                <option value="Habitacion">Habitación</option>
                <option value="Rayos X">Rayos X</option>
                <option value="Laboratorio">Laboratorio</option>
                <option value="Quirofano">Quirófano</option>
            </select>
            <br><br>

            <label>Fecha:</label>
            <input type="date" name="fecha" required>
            <br><br>

            <label>Hora:</label>
            <input type="time" name="hora" required>
            <br><br>

            <label>Responsable:</label>
            <input type="text" name="responsable" required>
            <br><br>

            <label>Motivo:</label>
            <input type="text" name="motivo" required>
            <br><br>
            
            <button type="submit">Registrar traslado</button>
        </form>
    </section>
</main>
</body>
</html>