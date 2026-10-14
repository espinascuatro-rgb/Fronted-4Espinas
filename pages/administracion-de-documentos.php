<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
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
        <section class="admdocumento1"> 
            <p>Subir nuevo documento:</p>
            <input type="file" id="archivo-subir">
            <button class="boton1">Cargar Archivo</button>
        </section>
        
        <section class="admdocumento2">
            <h2>Archivos</h2>
            <table>
                <tr>
                    <th>Nombre del Documento</th>
                    <th>Tipo</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">ejemplo.pdf</td>
                    <td>PDF</td>
                    <td><button class="boton2" style="padding: 5px 20px;">Descargar</button></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">ejemplo.docx</td>
                    <td>Word</td>
                    <td><button class="boton2" style="padding: 5px 20px;">Descargar</button></td>
                </tr>
            </table>
        </section>
    </main>
    <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</body>
</html>