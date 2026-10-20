<?php 
require_once __DIR__ . '/../php/checksesion.php';
require_once __DIR__ . '/../php/conexion.php';

$cedula = $_SESSION['usuario_id'] ?? '';

if (!empty($cedula)) {
    $sql = "SELECT f.usuario, f.credenciales, p.Numero_de_telefono, p.direccion 
            FROM funcionario f 
            INNER JOIN persona p ON f.id_tipo = p.cedula 
            WHERE f.id_tipo = ?";
            
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($datos = $res->fetch_assoc()) {
            $_SESSION['usuario_nombre'] = $datos['usuario'] ?? '';
            $_SESSION['telefono']       = $datos['Numero_de_telefono'] ?? '';
            $_SESSION['direccion']      = $datos['direccion'] ?? '';
            $_SESSION['credencial']     = $datos['credenciales'] ?? '';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes de Perfil</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/ajustes.css">
</head>
<body>
    <header class="Headerguia">
        <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Ajustes</h1>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>
    
    <main>
        <section class="ajustes1">
            <h2 id="aj1">Ajustes de perfil</h2>

           <!-- poco serio que los comentarios en html sean tan dificiles de poner. -->
            <div class="perfil-fila">
                <p><strong>Nombre:</strong> <span class="val-nombre"><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'No disponible'); ?></span></p>
                <button type="button" class="btn-toggle-form" data-target="form-nombre">Modificar</button>
            </div>
            <form id="form-nombre" class="perfil-form form-ajax" action="../php/ajustes-perfil.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="nombre">Nuevo Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? ''); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" class="btn-cancelar-form" data-target="form-nombre">Cancelar</button>
                </div>
            </form>

            
            <div class="perfil-fila">
                <p><strong>Teléfono:</strong> <span class="val-telefono"><?php echo htmlspecialchars($_SESSION['telefono'] ?? 'No disponible'); ?></span></p>
                <button type="button" class="btn-toggle-form" data-target="form-telefono">Modificar</button>
            </div>
            <form id="form-telefono" class="perfil-form form-ajax" action="../php/ajustes-perfil.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="telefono">Nuevo Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($_SESSION['telefono'] ?? ''); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" class="btn-cancelar-form" data-target="form-telefono">Cancelar</button>
                </div>
            </form>

            <!-- Thomas si estas viendo esto, quiero que sepas que me sorprende que estes viendo el codigo, bien ahi. -->
            <div class="perfil-fila">
                <p><strong>Dirección:</strong> <span class="val-direccion"><?php echo htmlspecialchars($_SESSION['direccion'] ?? 'No disponible'); ?></span></p>
                <button type="button" class="btn-toggle-form" data-target="form-direccion">Modificar</button>
            </div>
            <form id="form-direccion" class="perfil-form form-ajax" action="../php/ajustes-perfil.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="direccion">Nueva Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($_SESSION['direccion'] ?? ''); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" class="btn-cancelar-form" data-target="form-direccion">Cancelar</button>
                </div>
            </form>

            
            <div class="perfil-fila">
                <p><strong>Credencial:</strong> <span class="val-credencial"><?php echo htmlspecialchars($_SESSION['credencial'] ?? 'No disponible'); ?></span></p>
                <button type="button" class="btn-toggle-form" data-target="form-credencial">Modificar</button>
            </div>
            <form id="form-credencial" class="perfil-form form-ajax" action="../php/ajustes-perfil.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="credencial">Nueva Credencial:</label>
                    <input type="text" id="credencial" name="credencial" value="<?php echo htmlspecialchars($_SESSION['credencial'] ?? ''); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" class="btn-cancelar-form" data-target="form-credencial">Cancelar</button>
                </div>
            </form>

            
            <div class="perfil-fila">
                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($_SESSION['usuario_id'] ?? 'No disponible'); ?></p>
            </div>

            <!-- La vida son como manzanas, todos vinimos de una rama. -->
            <div class="perfil-fila">
                <p><strong>Contraseña:</strong> ********</p>
                <button type="button" class="btn-toggle-form" data-target="form-password">Modificar</button>
            </div>
            <form id="form-password" class="perfil-form form-ajax" action="../php/ajustes-perfil.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="password_actual">Contraseña Anterior:</label>
                    <input type="password" id="password_actual" name="password_actual" placeholder="Ingresa tu contraseña actual" required>
                </div>
                <div class="form-group">
                    <label for="password_nueva">Nueva Contraseña:</label>
                    <input type="password" id="password_nueva" name="password_nueva" placeholder="Ingresa la nueva contraseña" disabled required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" class="btn-cancelar-form" data-target="form-password">Cancelar</button>
                </div>
            </form>
        </section>
    </main>
    <!-- Si el html es tu poder, que eres tu sin el? -->
    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../js/ajustes.js"></script>
</body>
</html>