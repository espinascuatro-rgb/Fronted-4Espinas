<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';


$sql_organos = "SELECT * FROM organo ORDER BY nombre_organo ASC";
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

            <h2 class="titulo-seccion">Gestión e Inventario de Órganos</h2>


            <form action="../php/guardar_organo.php" method="POST" class="form-organo">
                <div class="grid-form-insumos">
                    <div>
                        <label for="nombre_organo"><strong>Nombre del Órgano:</strong></label>
                        <input type="text" id="nombre_organo" name="nombre_organo" class="input-form" placeholder="Ej: Corazón, Hígado, Córnea" required>
                    </div>

                    <div>
                        <label for="cantidad"><strong>Cantidad Disponible:</strong></label>
                        <input type="number" id="cantidad" name="cantidad" class="input-form" placeholder="Ej: 1" min="0" required>
                    </div>

                    <div>
                        <label for="tiempo_isquemia_horas"><strong>Tiempo Límite de Isquemia (Horas):</strong></label>
                        <input type="number" id="tiempo_isquemia_horas" name="tiempo_isquemia_horas" class="input-form" placeholder="Ej: 6" min="1" max="120" required>
                    </div>
                </div>

                <div class="contenedor-botones" style="margin-top: 15px;">
                    <button type="submit" id="btnSubmit">Agregar al Inventario</button>
                </div>
            </form>

            <h3 class="subtitulo-seccion">Órganos Disponibles en Banco</h3>


            <div class="overflow-tabla">
                <table class="tabla-insumos-admin">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Órgano</th>
                            <th>Tiempo Máx. Isquemia</th>
                            <th>Stock / Cantidad</th>
                            <th>Actualizar Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_organos && $res_organos->num_rows > 0): ?>
                            <?php while ($o = $res_organos->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $o['id_organo']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($o['nombre_organo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($o['tiempo_isquemia_horas']); ?> horas</td>
                                    <td><span class="badge-insumo"><?php echo $o['cantidad']; ?> unidades</span></td>
                                    <td>
                                        <form action="../php/editar_cantidad_organo.php" method="POST" style="display: flex; gap: 6px; align-items: center;">
                                            <input type="hidden" name="id_organo" value="<?php echo $o['id_organo']; ?>">
                                            <input type="number" name="cantidad" value="<?php echo $o['cantidad']; ?>" min="0" class="input-form" style="width: 80px; padding: 4px 8px;" required>
                                            <button type="submit" style="background: #0a1e6f; color: white; border: none; border-radius: 4px; padding: 6px 10px; cursor: pointer;">Guardar</button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="../php/eliminar_organo.php?id=<?php echo $o['id_organo']; ?>" class="btn-tabla-eliminar" onclick="return confirm('¿Confirma que desea eliminar este órgano del sistema?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="td-vacio">No hay órganos registrados en la base de datos.</td>
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