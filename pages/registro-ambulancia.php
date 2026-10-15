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
    <style>
        .alerta {
            padding: 10px 15px;
            border-radius: 4px;
            margin: 15px 0;
            font-weight: bold;
        }
        .alerta-exito { background-color: #d4edda; color: #155724; }
        .alerta-error { background-color: #f8d7da; color: #721c24; }

        .tabla-amb {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
            color: #333;
        }

        .tabla-amb th, .tabla-amb td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .tabla-amb th {
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
            display: inline-block;
        }

        .btn-cancelar {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: none;
            margin-top: 10px;
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
    </style>
</head>
<body>
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
    <main class="mainregistroambu" style="max-width: 900px; margin: 20px auto; padding: 20px;">
        <a href="../pages/guia.php" class="btn-inicio">← Volver al Inicio</a>

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

        <h2 id="tituloForm">Registro de Ambulancias</h2>     
        <form action="../php/inyeccion2.php" method="POST" id="formAmbulancia">
            <input type="hidden" name="accion" id="accion" value="guardar">
            <input type="hidden" name="serie_original" id="serie_original" value="">

            <section class="sectionregistroambu">
                <h3>Detalles del vehículo</h3>
                <p>
                    <label for="numero_de_serie">Número de identificación (Nº Serie)</label>
                    <input type="text" id="numero_de_serie" name="numero_de_serie" required>
                </p>
                <p>
                    <label for="kilometraje">Kilometraje</label>
                    <input type="number" id="kilometraje" name="kilometraje" required>
                </p>
                <p>
                    <label for="matricula">Matrícula</label>
                    <input type="text" id="matricula" name="matricula" required>
                </p>
              
                <button type="submit" id="btnSubmit">Registrar ambulancia</button>
                <button type="button" class="btn-cancelar" id="btnCancelar">Cancelar Edición</button>
            </section>
        </form>

        <section style="margin-top: 30px; background: white; padding: 20px; border-radius: 8px;">
            <h3 style="color: #0a192f; margin-bottom: 10px;">Ambulancias Registradas</h3>
            <table class="tabla-amb">
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
                                    <button class="btn-editar" onclick='editarAmbulancia(<?php echo json_encode($amb); ?>)'>Editar</button>
                                    <a href="../php/inyeccion2.php?accion=eliminar&numero_de_serie=<?php echo urlencode($amb['numero_de_serie']); ?>" class="btn-eliminar" onclick="return confirm('¿Está seguro de eliminar esta ambulancia?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay ambulancias registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script>
        function editarAmbulancia(amb) {
            document.getElementById('tituloForm').textContent = "Modificar Ambulancia";
            document.getElementById('accion').value = "editar";
            document.getElementById('serie_original').value = amb.numero_de_serie;

            document.getElementById('numero_de_serie').value = amb.numero_de_serie;
            document.getElementById('numero_de_serie').readOnly = true;
            document.getElementById('kilometraje').value = amb.kilometraje || '';
            document.getElementById('matricula').value = amb.matricula || '';

            document.getElementById('btnSubmit').textContent = "Actualizar ambulancia";
            document.getElementById('btnCancelar').style.display = "inline-block";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById('btnCancelar').addEventListener('click', () => {
            document.getElementById('tituloForm').textContent = "Registro de Ambulancias";
            document.getElementById('accion').value = "guardar";
            document.getElementById('formAmbulancia').reset();
            document.getElementById('numero_de_serie').readOnly = false;
            document.getElementById('btnSubmit').textContent = "Registrar ambulancia";
            document.getElementById('btnCancelar').style.display = "none";
        });
    </script>
</body>
</html>