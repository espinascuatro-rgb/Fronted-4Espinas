<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Documentos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="encabezado-app">
        <h1>Documentación Oficial</h1>
        <nav class="nav-app">
            <a href="./guia.php">Inicio</a>
        </nav>
    </header>
    <main>
        <section id="traslado1"> 
            <p>Subir nuevo documento:</p>
            <input type="file" id="archivo-subir">
            <button class="btn-primary">Cargar Archivo</button>
        </section>
        
        <section class="tarjeta">
            <h2 style="color: var(--azul-marino);">Archivos</h2>
            <table style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 15px;">
                <tr style="border-bottom: 2px solid var(--azul-borde); padding-bottom: 10px;">
                    <th>Nombre del Documento</th>
                    <th>Tipo</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">ejemplo.pdf</td>
                    <td>PDF</td>
                    <td><button class="btn-secondary" style="padding: 5px 20px;">Descargar</button></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">ejemplo.docx</td>
                    <td>Word</td>
                    <td><button class="btn-secondary" style="padding: 5px 20px;">Descargar</button></td>
                </tr>
            </table>
        </section>
    </main>
</body>
</html>