<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar insumo</title>
    <link rel="stylesheet" href="../css/registro-insumos.css">
</head>
<body>
    <main>
        <section>
            <h3>Datos del insumo<h3>
                <p>
                    <label for="Identificacion">Codigo de barras</label>
                    <input type="number" id="id_equipo_medico" name="id_equipo_medico" required>
                </p>
                <p>
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre">
                </p>
                <p>
                    <label for="categoria">Tipo de insumo</label>
                    <input type="text" id="insumos" name="insumos">
                </p>
        </section>

        <section> 
            <h3>Movimientos y logistica<h3>
                <p>
                    <label for="cantidad">Cantidad de insumos que ingresan, se transfieren o retiran</label>
                    <input type="number" id="cantidad" name="cantidad" required>
                </p>
                <p>
                    <label for="proveedor">Proveedor y costos</label>
                    <input type="text" id="proveedor" name="proveedor">
                </p>
                <p>
                    <label for="ubicación">Ubicacion o almacen</label>
                    <input type="text" id="ubicación" name="ubicación">
                </p>
        </section>
        <section> 
            <h3>Control de lotes y vencimientos<h3>
                <p>
                    <label for="numero">Numero de lotes</label>
                    <input type="number" id="number" name="number" required>
                </p>
                <p>
                    <label for="fecha">fecha de caducidad</label>
                    <input type="text" id="fecha" name="fecha">
                </p>
                <p>
                    <label for="registro">registro sanitario</label>
                    <input type="text" id="registro" name="registro">
                </p>
        </section>
</body>
<footer>
    <p>Hospital de clínicas</p>
        <p>Todos los derechos reservados por Equipo 4 Espinas</p>
    </footer>
</html>