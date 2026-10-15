<?php 
require_once __DIR__ . '/../php/checksesion.php'; 

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "hospital_de_clinicas";

$conexion = new mysqli($servidor, $usuario,$password, $base_datos);$conexion->set_charset("utf8mb4");

$sql_ambulancias = "SELECT a.*, i.vendas, i.jeringas, i.equipos_infusion, i.guantes, i.mascarillas, i.hisopos, i.desinfectantes, i.termometros, i.hilos_quirurgicos
                    FROM ambulancia a 
                    LEFT JOIN insumo_ambulancia i ON a.numero_de_serie = i.numero_de_serie_ambulancia 
                    ORDER BY a.numero_de_serie ASC";
$res_ambulancias = $conexion->query($sql_ambulancias);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Insumos - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .contenedor-main {
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
        }

        .grid-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            padding: 9px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-guardar {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
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

        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
            font-size: 0.9em;
        }

        .tabla-datos th, .tabla-datos td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .tabla-datos th {
            background-color: #0a192f;
            color: white;
        }

        .alerta {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .alerta-exito { background-color: #d4edda; color: #155724; }
        .alerta-error { background-color: #f8d7da; color: #721c24; }

        .badge-insumo {
            background-color: #e9ecef;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.85em;
            display: inline-block;
            margin: 2px;
        }
    </style>
</head>

<body>
    <header class="encabezado-app">
        <img src="../fotos-videos/Logo-Hospital.png" alt="Logo del hospital" class="logo">
        <div>
            <h1>Hospital de Clínicas</h1>
            <h2>Gestión e Insumos Médicos para Ambulancias</h2>
        </div>
        <nav class="nav-app">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="contenedor-main">
        <a href="../pages/guia.php" class="btn-inicio">← Volver al Inicio</a>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alerta alerta-exito">
                <?php 
                    if ($_GET['msg'] === 'insumos_guardados') echo "Los insumos médicos han sido registrados en la ambulancia correctamente.";
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['err'])): ?>
            <div class="alerta alerta-error">
                <?php echo htmlspecialchars($_GET['err']); ?>
            </div>
        <?php endif; ?>

        <section class="tarjeta">
            <h2 style="color: #0a192f;">Registro de Insumos por Ambulancia</h2>
            <form action="../php/guardar_insumos_ambulancia.php" method="POST">
                <input type="hidden" name="accion" value="guardar_insumos">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="numero_de_serie_ambulancia">Seleccionar Ambulancia / Unidad:</label>
                    <select id="numero_de_serie_ambulancia" name="numero_de_serie_ambulancia" required onchange="cargarInsumosAmbulancia(this.value)">
                        <option value="">-- Seleccione la Ambulancia --</option>
                        <?php 
                        if ($res_ambulancias && $res_ambulancias->num_rows > 0) {$res_ambulancias->data_seek(0);
                            while ($amb =$res_ambulancias->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($amb['numero_de_serie']) . '">Nº Serie: ' . htmlspecialchars($amb['numero_de_serie']) . ' \vert{} Matrícula: ' . htmlspecialchars($amb['matricula'] ?? 'Sin Matrícula') . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <h3 style="color: #007bff; margin-top: 10px;">Inventario de Insumos</h3>
                <div class="grid-form">
                    <div class="form-group">
                        <label for="vendas">Vendas (Unidades):</label>
                        <input type="number" id="vendas" name="vendas" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="jeringas">Jeringas (Unidades):</label>
                        <input type="number" id="jeringas" name="jeringas" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="equipos_infusion">Equipos de Infusión:</label>
                        <input type="number" id="equipos_infusion" name="equipos_infusion" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="guantes">Guantes (Pares):</label>
                        <input type="number" id="guantes" name="guantes" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="mascarillas">Mascarillas (Unidades):</label>
                        <input type="number" id="mascarillas" name="mascarillas" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="hisopos">Hisopos (Cajas/Unid):</label>
                        <input type="number" id="hisopos" name="hisopos" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="desinfectantes">Desinfectantes:</label>
                        <input type="number" id="desinfectantes" name="desinfectantes" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="termometros">Termómetros Clínicos:</label>
                        <input type="number" id="termometros" name="termometros" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="hilos_quirurgicos">Hilos Quirúrgicos:</label>
                        <input type="number" id="hilos_quirurgicos" name="hilos_quirurgicos" min="0" value="0">
                    </div>
                </div>

                <button type="submit" class="btn-guardar">Guardar Insumos en la Ambulancia</button>
            </form>
        </section>

        <section class="tarjeta" style="margin-top: 20px;">
            <h2>Inventario General de Unidades</h2>
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Nº Serie</th>
                        <th>Matrícula</th>
                        <th>Kilometraje</th>
                        <th>Insumos Registrados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($res_ambulancias && $res_ambulancias->num_rows > 0) {$res_ambulancias->data_seek(0);
                        while ($row =$res_ambulancias->fetch_assoc()) {
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
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align: center;">No hay ambulancias registradas.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="pie-app">
        <p>Equipo 4 Espinas - Hospital de Clínicas</p>
        <button><a href="../php/logout.php" style="color: white; text-decoration: none;">Cerrar Sesión</a></button>
    </footer>

    <script>
        async function cargarInsumosAmbulancia(numSerie) {
            if (!numSerie) return;

            try {
                const res = await fetch(`../php/guardar_insumos_ambulancia.php?accion=obtener_insumos&numero_de_serie=${encodeURIComponent(numSerie)}`);
                const json = await res.json();

                if (json.status === 'ok' && json.data) {
                    document.getElementById('vendas').value = json.data.vendas || 0;
                    document.getElementById('jeringas').value = json.data.jeringas || 0;
                    document.getElementById('equipos_infusion').value = json.data.equipos_infusion || 0;
                    document.getElementById('guantes').value = json.data.guantes || 0;
                    document.getElementById('mascarillas').value = json.data.mascarillas || 0;
                    document.getElementById('hisopos').value = json.data.hisopos || 0;
                    document.getElementById('desinfectantes').value = json.data.desinfectantes || 0;
                    document.getElementById('termometros').value = json.data.termometros || 0;
                    document.getElementById('hilos_quirurgicos').value = json.data.hilos_quirurgicos || 0;
                }
            } catch (e) {
                console.error("Error al obtener insumos:", e);
            }
        }
    </script>
</body>

</html>