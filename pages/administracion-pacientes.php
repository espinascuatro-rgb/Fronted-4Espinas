<?php 
require_once __DIR__ . '/../php/checksesion.php'; 

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "hospital_de_clinicas";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);
$conexion->set_charset("utf8mb4");

$sql = "SELECT * FROM persona ORDER BY cedula DESC";
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
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
        }

        .grid-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .form-group input {
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
            font-size: 0.92em;
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
                    if ($_GET['msg'] === 'agregado') echo "El paciente ha sido registrado";
                    if ($_GET['msg'] === 'editado') echo "Datos actualizados de forma correctamente";
                    if ($_GET['msg'] === 'eliminado') echo "Registro eliminado correctamente";
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
                <input type="hidden" name="cedula_original" id="cedula_original" value="">

                <div class="grid-form">
                    <div class="form-group">
                        <label for="cedula">Cédula de Identidad (*):</label>
                        <input type="text" id="cedula" name="cedula" required placeholder="Ej: 12345678">
                    </div>
                    <div class="form-group">
                        <label for="primer_nombre">Primer Nombre (*):</label>
                        <input type="text" id="primer_nombre" name="primer_nombre" required placeholder="Ej: Juan">
                    </div>
                    <div class="form-group">
                        <label for="segundo_nombre">Segundo Nombre:</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" placeholder="Ej: Carlos">
                    </div>
                    <div class="form-group">
                        <label for="primer_apellido">Primer Apellido (*):</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" required placeholder="Ej: Pérez">
                    </div>
                    <div class="form-group">
                        <label for="segundo_apellido">Segundo Apellido:</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" placeholder="Ej: Rodríguez">
                    </div>
                    <div class="form-group">
                        <label for="carne_de_salud">Carné de Salud:</label>
                        <input type="text" id="carne_de_salud" name="carne_de_salud" placeholder="Ej: 1234">
                    </div>
                    <div class="form-group">
                        <label for="numero_de_telefono">Teléfono / Celular:</label>
                        <input type="text" id="numero_de_telefono" name="numero_de_telefono" placeholder="Ej: 099123456">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" placeholder="Ej: Av. Italia 2870">
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
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Carné de Salud</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <?php 
                                $nombre_completo = trim(($row['primer_nombre'] ?? '') . ' ' . ($row['segundo_nombre'] ?? '') . ' ' . ($row['primer_apellido'] ?? '') . ' ' . ($row['segundo_apellido'] ?? ''));
                                if (empty($nombre_completo)) $nombre_completo = 'Sin nombre registrado';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                                <td><strong><?php echo htmlspecialchars($nombre_completo); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['carne_de_salud'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['numero_de_telefono'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['direccion'] ?? ''); ?></td>
                                <td>
                                    <button class="btn-editar" onclick='editarPaciente(<?php echo json_encode($row); ?>)'>Editar</button>
                                    <a href="../php/crud_paciente.php?accion=eliminar&cedula=<?php echo urlencode($row['cedula']); ?>" class="btn-eliminar" onclick="return confirm('¿Está seguro de que desea eliminar este paciente?');">Eliminar</a>
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
        function editarPaciente(p) {
            document.getElementById('tituloForm').textContent = "Editar Datos del Paciente";
            document.getElementById('accion').value = "editar";
            document.getElementById('cedula_original').value = p.cedula;
            
            document.getElementById('cedula').value = p.cedula;
            document.getElementById('cedula').readOnly = true;
            document.getElementById('primer_nombre').value = p.primer_nombre || '';
            document.getElementById('segundo_nombre').value = p.segundo_nombre || '';
            document.getElementById('primer_apellido').value = p.primer_apellido || '';
            document.getElementById('segundo_apellido').value = p.segundo_apellido || '';
            document.getElementById('carne_de_salud').value = p.carne_de_salud || '';
            document.getElementById('numero_de_telefono').value = p.numero_de_telefono || '';
            document.getElementById('direccion').value = p.direccion || '';

            document.getElementById('btnSubmit').textContent = "Actualizar Paciente";
            document.getElementById('btnCancelar').style.display = "inline-block";
        }

        document.getElementById('btnCancelar').addEventListener('click', () => {
            document.getElementById('tituloForm').textContent = "Agregar Nuevo Paciente";
            document.getElementById('accion').value = "agregar";
            document.getElementById('formPaciente').reset();
            document.getElementById('cedula').readOnly = false;
            document.getElementById('btnSubmit').textContent = "Guardar Paciente";
            document.getElementById('btnCancelar').style.display = "none";
        });
    </script>
</body>

</html>