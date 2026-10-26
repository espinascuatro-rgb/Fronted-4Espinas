<?php 
require_once __DIR__ . '/../php/checksesion.php';
require_once __DIR__ . '/../php/conexion.php';


$sql_docs = "SELECT * FROM documentos ORDER BY id_documento DESC";
$res_docs = $con->query($sql_docs);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Documentos</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/admdocu.css">
</head>
<body>
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Documentación Oficial</h1>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>
    <main class="mainadm">
        
        <!-- Formulario para subir nuevos archivos y asignarles nombre personalizado -->
        <section class="admdocumento1"> 
            <form action="../php/subir_documento.php" method="POST" enctype="multipart/form-data" class="form-subida-doc">
                <p>Subir nuevo documento:</p>
                
                <div class="grupo-input-doc">
                    <label for="nombre_personalizado"><strong>Nombre personalizado del documento:</strong></label>
                    <input type="text" name="nombre_personalizado" id="nombre_personalizado" class="input-form" placeholder="Ej: Protocolo de Traslado 2026" required>
                </div>

                <div class="grupo-input-doc" style="margin-top: 10px;">
                    <input type="file" name="archivo-subir" id="archivo-subir" required>
                </div>

                <button type="submit" class="boton1" style="margin-top: 10px;">Cargar Archivo</button>
            </form>
        </section>
        
        <!-- Sección de listado de archivos -->
        <section class="admdocumento2">
            <h2>Archivos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre del Documento</th>
                        <th>Tipo</th>
                        <th>Modificar Nombre</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res_docs && $res_docs->num_rows > 0): ?>
                        <?php while ($doc = $res_docs->fetch_assoc()): ?>
                            <tr>
                                <td class="td-espaciado"><strong><?php echo htmlspecialchars($doc['nombre_personalizado']); ?></strong> <br><small style="color: #64748b;"><?php echo htmlspecialchars($doc['nombre_original']); ?></small></td>
                                <td class="td-espaciado"><?php echo strtoupper(htmlspecialchars($doc['tipo_archivo'])); ?></td>
                                <td class="td-espaciado">

                                <form action="../php/editar_nombre_documento.php" method="POST" class="form-editar-nombre">
                                        <input type="hidden" name="id_documento" value="<?php echo $doc['id_documento']; ?>">
                                        <input type="text" name="nuevo_nombre" value="<?php echo htmlspecialchars($doc['nombre_personalizado']); ?>" class="input-form input-tabla-editar" required>
                                        <button type="submit" class="boton-actualizar">Actualizar</button>
                                    </form>
                                </td>
                                <td class="td-espaciado">

                                <a href="<?php echo htmlspecialchars($doc['ruta_archivo']); ?>" download="<?php echo htmlspecialchars($doc['nombre_personalizado'] . '.' . $doc['tipo_archivo']); ?>" class="boton2 enlace-descarga">Descargar</a>
                                    <a href="../php/eliminar_documento.php?id=<?php echo $doc['id_documento']; ?>" class="btn-tabla-eliminar" onclick="return confirm('¿Seguro que deseas eliminar este documento?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="td-vacio">No hay documentos cargados en el sistema.</td>
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
</body>
</html>