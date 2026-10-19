<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
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
<!--Se pide obligatoriamente cambiar todos los campos. Lo ideal seria que se pueda cambiar los que se deseen.-->
<!-- sale un error donde si se deja vacio el cambio de contraseña se cambia igualmente. Ademas de que siendo la contraseña anterior incorrecta pero se deja vacio lo cuenta como valido. -->
            <div id="datos-perfil" class="perfil-info">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($_SESSION['telefono']  ?? 'No disponible'); ?></p>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($_SESSION['direccion'] ?? 'No disponible'); ?></p>
                <p><strong>Credencial:</strong> <?php echo htmlspecialchars($_SESSION['credencial'] ?? 'No disponible'); ?></p>
                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($_SESSION['usuario_id']); ?></p>
                <p><strong>Contraseña:</strong> ********</p>
                
                <button type="button" id="btn-mostrar-editar" class="btn-editar">Modificar datos</button>
            </div>

            <form id="form-editar-perfil" action="../php/ajustes-perfil.php" method="POST" class="perfil-form" style="display: none;">
                <h3>Editar tus datos</h3>
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($_SESSION['telefono'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($_SESSION['direccion'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="credencial">Credencial:</label>
                    <input type="text" id="credencial" name="credencial" value="<?php echo htmlspecialchars($_SESSION['credencial'] ?? ''); ?>" required>
                </div>

                <hr style="margin: 20px 0;">
                <h4>Cambiar Contraseña</h4>
                
                <div class="form-group">
                    <label for="password_actual">Contraseña Anterior:</label>
                    <input type="password" id="password_actual" name="password_actual" placeholder="Ingresa tu contraseña actual">
                </div>

                <div class="form-group">
                    <label for="password_nueva">Nueva Contraseña:</label>
                    <input type="password" id="password_nueva" name="password_nueva" placeholder="Ingresa la nueva contraseña" disabled>
                </div>

                <div class="form-actions" style="margin-top: 15px;">
                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                    <button type="button" id="btn-cancelar" class="btn-cancelar">Cancelar</button>
                </div>
            </form>
        </section>

        <section class="section2">
            <h2 id="aj2">Preferencias de sistemas.</h2>
        </section>
    </main>

    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>

    <script src="../js/ajustes.js"></script>
</body>
</html>