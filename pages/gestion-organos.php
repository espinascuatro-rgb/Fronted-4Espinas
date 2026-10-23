<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

// Obtener la lista de pacientes para vincular al órgano
$sql_pacientes = "SELECT ci_persona, nombre, apellido FROM persona ORDER BY apellido ASC";
$res_pacientes = $con->query($sql_pacientes);

// Obtener los registros de órganos existentes
$sql_organos = "SELECT o.*, p.nombre, p.apellido 
                FROM organo_paciente o 
                LEFT JOIN persona p ON o.ci_paciente = p.ci_persona 
                ORDER BY o.id_organo DESC";
$res_organos = $con->query($sql_organos);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Órganos - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/guia.css">
    <link rel="stylesheet" href="../css/insumo.css">
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
        <section class="sectionguia contenedor-insumos-main">
            <div class="contenedor-botones">
                <button class="btnguia1 btn-volver">
                    <a href="../pages/guia.php">← Volver al Inicio</a>
                </button>
            </div>

            <h2 class="titulo-seccion">Gestión y Trazabilidad de Órganos</h2>

            <!-- Formulario para agregar / editar registro de órgano -->
            <form action="../php/guardar_organo.php" method="POST" class="form-organo">
                <input type="hidden" id="id_organo" name="id_organo" value="">

                <div class="grid-form-insumos">
                    <div>
                        <label for="ci_paciente"><strong>Paciente Asociado:</strong></label>
                        <select id="ci_paciente" name="ci_paciente" class="input-select" required>
                            <option value="">Seleccione un paciente...</option>
                            <?php if ($res_pacientes && $res_pacientes->num_rows > 0): ?>
                                <?php while ($p = $res_pacientes->fetch_assoc()): ?>
                                    <option value="<?php echo $p['ci_persona']; ?>">
                                        <?php echo htmlspecialchars($p['apellido'] . ', ' . $p['nombre'] . ' (CI: ' . $p['ci_persona'] . ')'); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="tipo_organo"><strong>Tipo de Órgano:</strong></label>
                        <select id="tipo_organo" name="tipo_organo" class="input-select" required>
                            <option value="">Seleccione tipo...</option>
                            <option value="Corazón">Corazón</option>
                            <option value="Hígado">Hígado</option>
                            <option value="Riñón">Riñón</option>
                            <option value="Pulmón">Pulmón</option>
                            <option value="Páncreas">Páncreas</option>
                            <option value="Córnea">Córnea</option>
                            <option value="Tejido / Otro">Tejido / Otro</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipo_operacion"><strong>Función del Registro:</strong></label>
                        <select id="tipo_operacion" name="tipo_operacion" class="input-select" required>
                            <option value="Donante">Donante (Ablación)</option>
                            <option value="Receptor">Receptor (Transplante)</option>
                        </select>
                    </div>

                    <div>
                        <label for="estado_conservacion"><strong>Estado de Conservación:</strong></label>
                        <select id="estado_conservacion" name="estado_conservacion" class="input-select" required>
                            <option value="En Espera">En Espera de extracción</option>
                            <option value="En Isquemia Fría">En Isquemia Fría (Preservado)</option>
                            <option value="En Tránsito">En Tránsito en Ambulancia</option>
                            <option value="Implantado">Implantado Exitosamente</option>
                            <option value="No Viable">No Viable / Descartado</option>
                        </select>
                    </div>

                    <div>
                        <label for="tiempo_limite"><strong>Tiempo Límite de Isquemia (Horas):</strong></label>
                        <input type="number" id="tiempo_limite" name="tiempo_limite" class="input-form" placeholder="Ej: 4" min="1" max="72" required>
                    </div>

                    <div>
                        <label for="urgencia"><strong>Nivel de Urgencia:</strong></label>
                        <select id="urgencia" name="urgencia" class="input-select" required>
                            <option value="Normal">Normal</option>
                            <option value="Alta">Alta</option>
                            <option value="Urgencia Cero">Urgencia Cero (Crítica)</option>
                        </select>
                    </div>
                </div>

                <div class="contenedor-botones">
                    <button type="submit" id="btnSubmit">Registrar Órgano</button>
                </div>
            </form>

            <h3 class="subtitulo-seccion">Órganos Registrados en Sistema</h3>

            <!-- Tabla de Órganos Registrados -->
            <div class="overflow-tabla">
                <table class="tabla-insumos-admin">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Órgano</th>
                            <th>Función</th>
                            <th>Estado</th>
                            <th>Límite (Horas)</th>
                            <th>Urgencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_organos && $res_organos->num_rows > 0): ?>
                            <?php while ($o = $res_organos->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $o['id_organo']; ?></td>
                                    <td><?php echo htmlspecialchars(($o['apellido'] ?? 'Sin asignar') . ', ' . ($o['nombre'] ?? '')); ?></td>
                                    <td><strong><?php echo htmlspecialchars($o['tipo_organo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($o['tipo_operacion']); ?></td>
                                    <td><span class="badge-insumo"><?php echo htmlspecialchars($o['estado_conservacion']); ?></span></td>
                                    <td><?php echo htmlspecialchars($o['tiempo_limite']); ?> hs</td>
                                    <td><strong><?php echo htmlspecialchars($o['urgencia']); ?></strong></td>
                                    <td>
                                        <a href="../php/eliminar_organo.php?id=<?php echo $o['id_organo']; ?>" class="btn-tabla-eliminar" onclick="return confirm('¿Desea eliminar este registro de órgano?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="td-vacio">No hay registros de órganos cargados actualmente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</body>

</html>