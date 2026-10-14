<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/encuesta.css">
</head>

<body>
    <header class="Headerguia">
       <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Encuesta de Satisfacción</h1>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="mainencuesta">
        <section class="sectionencuesta">
            <h2>Tu opinión nos ayuda mejorar</h2>
            <p>Responde libremente y de forma critica</p>
            <br>
            
            <form action="../php/guardar_encuesta.php" method="POST">
                
                <p>
                    <label for="nombre_paciente">Nombre (opcional):</label>
                    <input type="text" id="nombre_paciente" name="nombre_paciente" placeholder="Mateo laura">
                </p>

                <p>
                    <label for="servicio">¿Qué servicio utilizaste?</label>
                    <select id="servicio" name="servicio" required>
                        <option value="">Selecciona un servicio...</option>
                        <option value="Urgencias">Urgencias</option>
                        <option value="Hospitalizacion">Hospitalización</option>
                        <option value="Ambulancia">Traslado en Ambulancia</option>
                        <option value="Policlinica">Atención en Policlínica</option>
                        <option value="Otro">Otro</option>
                    </select>
                </p>

                <p>
                    <label for="calificacion">¿Cómo calificarías la atención médica recibida?</label>
                    <select id="calificacion" name="calificacion" required>
                        <option value="">Selecciona una calificación...</option>
                        <option value="5">Excelente (5/5)</option>
                        <option value="4">Muy Buena (4/5)</option>
                        <option value="3">Buena (3/5)</option>
                        <option value="2">Regular (2/5)</option>
                        <option value="1">Mala (1/5)</option>
                    </select>
                </p>

                <p>
                    <label for="instalaciones">¿Cómo calificarías las instalaciones (higiene, comodidad)?</label>
                    <select id="instalaciones" name="instalaciones" required>
                        <option value="">Selecciona una calificación...</option>
                        <option value="5">Excelente (5/5)</option>
                        <option value="4">Muy Buena (4/5)</option>
                        <option value="3">Buena (3/5)</option>
                        <option value="2">Regular (2/5)</option>
                        <option value="1">Mala (1/5)</option>
                    </select>
                </p>

                <p>
                    <label for="comentarios">Comentarios o sugerencias para mejorar (Opcional):</label>
                    <input type="text" id="comentarios" name="comentarios" placeholder="Cuéntanos más sobre tu experiencia...">
                </p>

                <button type="submit">Enviar Encuesta</button>
            </form>
        </section>
    </main>

        <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</body>
</html>