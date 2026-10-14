<?php 
require_once __DIR__ . '/../php/checksesion.php'; 

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "hospital_de_clinicas";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);
$conexion->set_charset("utf8mb4");

$fecha_hoy = date('Y-m-d');

// Obtener lista completa de pacientes (personas)
$sql = "SELECT * FROM persona ORDER BY ci DESC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Pacientes - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .contenedor-admin {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }

        .grid-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            font-size: 0.9em;
            margin-bottom: 4px;
        }

        .form-group input, .form-group select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-guardar {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-cancelar {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: none;
        }

        .tabla-pacientes {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
        }

        .tabla-pacientes th, .tabla-pacientes td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .tabla-pacientes th {
            background-color: #0a192f;
            color: white;
        }

        .btn-editar {
            background-color: #ffc107;
            color: black;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-eliminar {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-inicio {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .alerta {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .alerta-exito { background-color: #d4edda; color: #155724; }
        .alerta-error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>

<body>
    <header class="encabezado-app">
        <img src="../fotos-videos/Logo-Hospital.png" alt="Logo del hospital" class="logo">
        <div>
            <h1>Hospital de Clínicas</h1>
            <h2>Gestión y Administración de Pacientes</h2>
        </div>
        <nav class="nav-app">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="contenedor-admin">

        <?php if (isset($_GET['msg'])): ?>
            <div class="alerta alerta-exito">
                <?php 
                    if ($_GET['msg'] === 'agregado') echo "¡Paciente registrado exitosamente!";
                    if ($_GET['msg'] === 'editado') echo "¡Datos del paciente actualizados correctamente!";
                    if ($_GET['msg'] === 'eliminado') echo "¡Paciente eliminado del sistema!";
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['err'])): ?>
            <div class="alerta alerta-error">
                <?php echo htmlspecialchars($_GET['err']); ?>
            </div>
        <?php endif; ?>

        <section class="tarjeta">
            <h2 id="tituloForm">Agregar Nuevo Paciente</h2>
            <form action="../php/crud_paciente.php" method="POST" id="formPaciente">
                <input type="hidden" name="accion" id="accion" value="agregar">
                <input type="hidden" name="ci_original" id="ci_original" value="">

                <div class="grid-form">
                    <div class="form-group">
                        <label for="ci">Cédula de Identidad:</label>
                        <input type="number" id="ci" name="ci" required placeholder="Ej: 12345678">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Ej: Juan">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" required placeholder="Ej: Pérez">
                    </div>
                    <div class="form-group">
                        <label for="fecha_nac">Fecha Nacimiento:</label>
                        <!-- Restricción de fecha máxima al día de hoy -->
                        <input type="date" id="fecha_nac" name="fecha_nac" max="<?php echo $fecha_hoy; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo:</label>
                        <select id="sexo" name="sexo" required>
                            <option value="M">Masculino (M)</option>
                            <option value="F">Femenino (F)</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-guardar" id="btnSubmit">Guardar Paciente</button>
                <button type="button" class="btn-cancelar" id="btnCancelar">Cancelar Edición</button>
            </form>
        </section>

        <section class="tarjeta" style="margin-top: 20px;">
            <h2>Listado de Pacientes Registrados</h2>
            <table class="tabla-pacientes">
                <thead>
                    <tr>
                        <th>Cédula (CI)</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha Nac.</th>
                        <th>Sexo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ci']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_nac']); ?></td>
                                <td><?php echo htmlspecialchars($row['sexo']); ?></td>
                                <td>
                                    <button class="btn-editar" onclick="editarPaciente(<?php echo htmlspecialchars(json_encode($row)); ?>)">Editar</button>
                                    <a href="../php/crud_paciente.php?accion=eliminar&ci=<?php echo $row['ci']; ?>" class="btn-eliminar" onclick="return confirm('¿Está seguro de que desea eliminar este paciente?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No hay pacientes registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="pie-app">
        <p>Equipo 4 Espinas - Hospital de Clínicas</p>
        <button><a href="../php/logout.php" style="color: white; text-decoration: none;">Cerrar Sesión</a></button>
    </footer>

    <script>
        function editarPaciente(paciente) {
            document.getElementById('tituloForm').textContent = "Editar Datos del Paciente";
            document.getElementById('accion').value = "editar";
            document.getElementById('ci_original').value = paciente.ci;
            
            document.getElementById('ci').value = paciente.ci;
            document.getElementById('ci').readOnly = true;
            document.getElementById('nombre').value = paciente.nombre;
            document.getElementById('apellido').value = paciente.apellido;
            document.getElementById('fecha_nac').value = paciente.fecha_nac;
            document.getElementById('sexo').value = paciente.sexo;

            document.getElementById('btnSubmit').textContent = "Actualizar Paciente";
            document.getElementById('btnCancelar').style.display = "inline-block";
        }

        document.getElementById('btnCancelar').addEventListener('click', () => {
            document.getElementById('tituloForm').textContent = "Agregar Nuevo Paciente";
            document.getElementById('accion').value = "agregar";
            document.getElementById('formPaciente').reset();
            document.getElementById('ci').readOnly = false;
            document.getElementById('btnSubmit').textContent = "Guardar Paciente";
            document.getElementById('btnCancelar').style.display = "none";
        });
    </script>
</body>

</html>