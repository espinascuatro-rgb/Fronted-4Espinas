<?php 
require_once __DIR__ . '/../php/checksesion.php'; 
require_once __DIR__ . '/../php/conexion.php';

$sql_ambulancias = "SELECT a.*, i.vendas, i.jeringas, i.equipos_infusion, i.guantes, i.mascarillas, i.hisopos, i.desinfectantes, i.termometros, i.hilos_quirurgicos
                    FROM ambulancia a 
                    LEFT JOIN insumo_ambulancia i ON a.numero_de_serie = i.numero_de_serie_ambulancia 
                    ORDER BY a.numero_de_serie ASC";
$res_ambulancias = $con->query($sql_ambulancias);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Insumos - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/guia.css">
    <link rel="stylesheet" href="../css/insumos.css">
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
            <button class="btnguia1 btn-volver">
                <a href="../pages/guia.php">← Volver al Inicio</a>
            </button>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alerta alerta-exito">
                    <?php 
                        if ($_GET['msg'] === 'insumos_guardados') echo "Los insumos médicos han sido registrados en la ambulancia correctamente.";
                        if ($_GET['msg'] === 'insumos_eliminados') echo "Se han eliminado los insumos médicos de la ambulancia.";
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['err'])): ?>
                <div class="alerta alerta-error">
                    <?php echo htmlspecialchars($_GET['err']); ?>
                </div>
            <?php endif; ?>

            <h2 id="tituloForm" class="titulo-seccion">Registro y Edición de Insumos por Ambulancia</h2>
            <form action="../php/guardar_insumos_ambulancia.php" method="POST" id="formInsumos">
                <input type="hidden" name="accion" value="guardar_insumos">

                <div class="form-select-ambulancia">
                    <label for="numero_de_serie_ambulancia"><strong>Seleccionar Ambulancia / Unidad:</strong></label>
                    <select id="numero_de_serie_ambulancia" name="numero_de_serie_ambulancia" required class="input-select">
                        <option value="">-- Seleccione la Ambulancia --</option>
                        <?php 
                        if ($res_ambulancias && $res_ambulancias->num_rows > 0) {
                            $res_ambulancias->data_seek(0);
                            while ($amb = $res_ambulancias->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($amb['numero_de_serie']) . '">Nº Serie: ' . htmlspecialchars($amb['numero_de_serie']) . ' | Matrícula: ' . htmlspecialchars($amb['matricula'] ?? 'Sin Matrícula') . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <h3 class="subtitulo-seccion">Inventario de Insumos</h3>
                <div class="grid-form-insumos">
                    <div>
                        <label for="vendas"><strong>Vendas (Unidades):</strong></label>
                        <input type="number" id="vendas" name="vendas" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="jeringas"><strong>Jeringas (Unidades):</strong></label>
                        <input type="number" id="jeringas" name="jeringas" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="equipos_infusion"><strong>Equipos de Infusión:</strong></label>
                        <input type="number" id="equipos_infusion" name="equipos_infusion" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="guantes"><strong>Guantes (Pares):</strong></label>
                        <input type="number" id="guantes" name="guantes" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="mascarillas"><strong>Mascarillas (Unidades):</strong></label>
                        <input type="number" id="mascarillas" name="mascarillas" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="hisopos"><strong>Hisopos (Cajas/Unid):</strong></label>
                        <input type="number" id="hisopos" name="hisopos" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="desinfectantes"><strong>Desinfectantes:</strong></label>
                        <input type="number" id="desinfectantes" name="desinfectantes" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="termometros"><strong>Termómetros Clínicos:</strong></label>
                        <input type="number" id="termometros" name="termometros" min="0" value="0" class="input-form">
                    </div>
                    <div>
                        <label for="hilos_quirurgicos"><strong>Hilos Quirúrgicos:</strong></label>
                        <input type="number" id="hilos_quirurgicos" name="hilos_quirurgicos" min="0" value="0" class="input-form">
                    </div>
                </div>

                <div class="contenedor-botones">
                    <button type="submit" id="btnSubmit">Guardar / Modificar Insumos</button>
                </div>
            </form>

            <h2 class="titulo-seccion">Inventario General de Unidades</h2>
            <div class="overflow-tabla">
                <table class="tabla-insumos-admin">
                    <thead>
                        <tr>
                            <th>Nº Serie</th>
                            <th>Matrícula</th>
                            <th>Kilometraje</th>
                            <th>Insumos Registrados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($res_ambulancias && $res_ambulancias->num_rows > 0) {
                            $res_ambulancias->data_seek(0);
                            while ($row = $res_ambulancias->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td><strong>' . htmlspecialchars($row['numero_de_serie']) . '</strong></td>';
                                echo '<td>' . htmlspecialchars($row['matricula'] ?? 'N/A') . '</td>';
                                echo '<td>' . htmlspecialchars($row['kilometraje'] ?? '0') . ' km</td>';
                                echo '<td>';
                                echo '<span class="badge-insumo">Vendas: ' . intval($row['vendas'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Jeringas: ' . intval($row['jeringas'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Eq. Infusión: ' . intval($row['equipos_infusion'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Guantes: ' . intval($row['guantes'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Mascarillas: ' . intval($row['mascarillas'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Hisopos: ' . intval($row['hisopos'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Desinfectantes: ' . intval($row['desinfectantes'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Termómetros: ' . intval($row['termometros'] ?? 0) . '</span> ';
                                echo '<span class="badge-insumo">Hilos Quir.: ' . intval($row['hilos_quirurgicos'] ?? 0) . '</span>';
                                echo '</td>';
                                echo '<td>';
                                echo '<button type="button" class="btn-tabla-editar btn-editar-insumos" data-serie="' . htmlspecialchars($row['numero_de_serie']) . '">Editar</button> ';
                                echo '<a href="../php/guardar_insumos_ambulancia.php?accion=eliminar_insumos&numero_de_serie=' . urlencode($row['numero_de_serie']) . '" class="btn-tabla-eliminar link-eliminar-confirmar">Eliminar</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="td-vacio">No hay ambulancias registradas.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../js/insumos.js"></script>
</body>

</html>