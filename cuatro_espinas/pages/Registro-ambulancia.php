<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de registro de ambulancias</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="headersection">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas</h1>
    </header>
    <main>

               <form action="../php/inyeccion2.php" method="POST">
            <section>
                <h2>Registro ambulancias</h2>     

                </h2>
              <!--  
                <h3>Datos del solicitante</h3>
                <p>
                    <label for="nombre_solicitante">Nombre del solicitador</label>
                    <input type="text" id="nombre_solicitante" name="nombre_solicitante" required>
                </p>
                <p>
                    <label for="telefono_solicitante">Teléfono de contacto</label>
                    <input type="text" id="telefono_solicitante" name="telefono_solicitante" required>
                </p>
            </section>
            
            <section>
                <h3>Datos del paciente</h3>
                <p>
                    <label for="nombre_paciente">Nombre del paciente</label>
                    <input type="text" id="nombre_paciente" name="nombre_paciente" required>
                </p>
                <p>
                    <label for="edad_paciente">Edad del paciente</label>
                    <input type="number" id="edad_paciente" name="edad_paciente">
                </p>
                <p>
                    <label for="condicion_paciente">Condición del paciente</label>
                    <input type="text" id="condicion_paciente" name="condicion_paciente">
                </p>
            </section>
          
            <section>
                <h3>Detalles del traslado</h3>
                <p>
                    <label for="origen">Dirección de origen</label>
                    <input type="text" id="origen" name="origen" required>
                </p>
                <p>
                    <label for="destino">Destino del traslado</label>
                    <input type="text" id="destino" name="destino" required>
                </p>
            </section>
        -->
            <section>
                <h3>Detalles del vehículo</h3>
                <p>
                    <label for="numero_de_serie">Número de identificación</label>
                    <input type="text" id="numero_de_serie" name="numero_de_serie" required>
                </p>
                <p>
                    <label for="kilometraje">Kilometraje</label>
                    <input type="text" id="kilometraje" name="kilometraje" requiered>
                    </p>
                <p>
                    <label for="matricula">Matrícula</label>
                    <input type="text" id="matricula" name="matricula" required>
                </p>
              
                <button type="submit">Registrar traslado de ambulancia</button>
            </section>
        </form>
    </main>
    <footer>
        <p>Hospital de clínicas</p>
        <p>Todos los derechos reservados por Equipo 4 Espinas</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</body>
</html>
