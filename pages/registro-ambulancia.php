<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

$sql_ambulancias = "SELECT * FROM ambulancia ORDER BY numero_de_serie ASC";
$res_ambulancias = $con->query($sql_ambulancias);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de registro de ambulancias</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/registroambu.css">
    <link rel="stylesheet" href="../css/admin-ambulancia.css">
</head>

<body class="bodyguia">
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h2>Registro de Ambulancias</h2>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="mainguia">
        <section class="sectionguia contenedor-ambulancia-main">
            <button class="btnguia1 btn-volver">
                <a href="../pages/guia.php">← Volver al Inicio</a>
            </button>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alerta alerta-exito">
                    <?php 
                        if ($_GET['msg'] === 'guardado') echo "¡Ambulancia registrada correctamente!";
                        if ($_GET['msg'] === 'editado') echo "¡Datos de la ambulancia actualizados!";
                        if ($_GET['msg'] === 'eliminado') echo "¡Ambulancia eliminada correctamente!";
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['err'])): ?>
                <div class="alerta alerta-error">
                    <?php echo htmlspecialchars($_GET['err']); ?>
                </div>
            <?php endif; ?>

            <h2 id="tituloForm" class="titulo-seccion">Registro de Ambulancias</h2>     
            <form action="../php/inyeccion2.php" method="POST" id="formAmbulancia">
                <input type="hidden" name="accion" id="accion" value="guardar">
                <input type="hidden" name="serie_original" id="serie_original" value="">

                <div class="grid-form-ambulancia">
                    <div>
                        <label for="numero_de_serie"><strong>Número de identificación (Nº Serie):</strong></label>
                        <input type="text" id="numero_de_serie" name="numero_de_serie" required class="input-form">
                    </div>
                    <div>
                        <label for="kilometraje"><strong>Kilometraje:</strong></label>
                        <input type="number" id="kilometraje" name="kilometraje" required class="input-form">
                    </div>
                    <div>
                        <label for="matricula"><strong>Matrícula:</strong></label>
                        <input type="text" id="matricula" name="matricula" required class="input-form">
                    </div>
                </div>
              
                <div class="contenedor-botones">
                    <button type="submit" id="btnSubmit">Registrar ambulancia</button>
                    <button type="button" id="btnCancelar" class="btn-cancelar">Cancelar Edición</button>
                </div>
            </form>

            <h2 class="titulo-seccion">Ambulancias Registradas</h2>
            <div class="overflow-tabla">
                <table class="tabla-ambulancias-admin">
                    <thead>
                        <tr>
                            <th>Nº Serie</th>
                            <th>Kilometraje</th>
                            <th>Matrícula</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_ambulancias && $res_ambulancias->num_rows > 0): ?>
                            <?php while ($amb = $res_ambulancias->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($amb['numero_de_serie']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($amb['kilometraje'] ?? '0'); ?> km</td>
                                    <td><?php echo htmlspecialchars($amb['matricula'] ?? 'N/A'); ?></td>
                                    <td>
                                        <button type="button" class="btn-tabla-editar" data-ambulancia='<?php echo htmlspecialchars(json_encode($amb), ENT_QUOTES, 'UTF-8'); ?>'>Editar</button>
                                        <a href="../php/inyeccion2.php?accion=eliminar&numero_de_serie=<?php echo urlencode($amb['numero_de_serie']); ?>" class="btn-tabla-eliminar link-eliminar-confirmar">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="td-vacio">No hay ambulancias registradas.</td>
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

    <script src="../js/registro-ambulancia.js"></script>
</body>

</html>