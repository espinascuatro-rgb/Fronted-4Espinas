<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

$sql = "SELECT * FROM persona ORDER BY cedula DESC";
$resultado = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Pacientes - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/guia.css">
    <link rel="stylesheet" href="../css/admin-pacientes.css">
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Hospital de Clínicas "Dr. Manuel Quintela"</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="mainguia">
        <section class="sectionguia contenedor-admin-pacientes">

            <?php if (isset($_GET['msg'])): ?>
                <div class="alerta alerta-exito">
                    <?php 
                        if ($_GET['msg'] === 'agregado') echo "Paciente registrado de forma correcta";
                        if ($_GET['msg'] === 'editado') echo "Los datos se han actualizados ";
                        if ($_GET['msg'] === 'eliminado') echo "El registro ha sido eliminado";
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['err'])): ?>
                <div class="alerta alerta-error">
                    <?php echo htmlspecialchars($_GET['err']); ?>
                </div>
            <?php endif; ?>

            <h2 id="tituloForm" class="titulo-seccion">Agregar Nuevo Paciente</h2>
            <form action="../php/crud_paciente.php" method="POST" id="formPaciente">
                <input type="hidden" name="accion" id="accion" value="agregar">
                <input type="hidden" name="cedula_original" id="cedula_original" value="">

                <div class="grid-form-pacientes">
                    <div>
                        <label for="cedula"><strong>Cédula de Identidad (*):</strong></label>
                        <input type="text" id="cedula" name="cedula" required placeholder="Ej: 12345678" class="input-form">
                    </div>
                    <div>
                        <label for="primer_nombre"><strong>Primer Nombre (*):</strong></label>
                        <input type="text" id="primer_nombre" name="primer_nombre" required placeholder="Ej: Juan" class="input-form">
                    </div>
                    <div>
                        <label for="segundo_nombre"><strong>Segundo Nombre:</strong></label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" placeholder="Ej: Carlos" class="input-form">
                    </div>
                    <div>
                        <label for="primer_apellido"><strong>Primer Apellido (*):</strong></label>
                        <input type="text" id="primer_apellido" name="primer_apellido" required placeholder="Ej: Pérez" class="input-form">
                    </div>
                    <div>
                        <label for="segundo_apellido"><strong>Segundo Apellido:</strong></label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" placeholder="Ej: Rodríguez" class="input-form">
                    </div>
                    <div>
                        <label for="carne_de_salud"><strong>Carné de Salud:</strong></label>
                        <input type="text" id="carne_de_salud" name="carne_de_salud" placeholder="Ej: 1234" class="input-form">
                    </div>
                    <div>
                        <label for="numero_de_telefono"><strong>Teléfono / Celular:</strong></label>
                        <input type="text" id="numero_de_telefono" name="numero_de_telefono" placeholder="Ej: 099123456" class="input-form">
                    </div>
                    <div>
                        <label for="direccion"><strong>Dirección:</strong></label>
                        <input type="text" id="direccion" name="direccion" placeholder="Ej: Av. Italia 2870" class="input-form">
                    </div>
                </div>

                <div class="contenedor-botones">
                    <button type="submit" id="btnSubmit">Guardar Paciente</button>
                    <button type="button" id="btnCancelar" class="btn-cancelar">Cancelar Edición</button>
                </div>
            </form>

            <h2 class="titulo-seccion">Listado de Pacientes Registrados</h2>
            <div class="overflow-tabla">
                <table class="tabla-pacientes-admin">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre Completo</th>
                            <th>Carné Salud</th>
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
                                        <button type="button" class="btn-tabla-editar" data-paciente='<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>'>Editar</button>
                                        <a href="../php/crud_paciente.php?accion=eliminar&cedula=<?php echo urlencode($row['cedula']); ?>" class="btn-tabla-eliminar link-eliminar-confirmar">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="td-vacio">No hay pacientes registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../js/admin-pacientes.js"></script>
</body>

</html>