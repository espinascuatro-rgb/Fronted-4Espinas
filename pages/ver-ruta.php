<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo GPS - Ver Ruta</title>
    
    <link rel="stylesheet" href="../css/style.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- Leaflet Fullscreen CSS -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet-fullscreen.css" />

    <style>
        .grid-formulario {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .campo {
            display: flex;
            flex-direction: column;
        }

        .campo label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 0.88em;
        }

        .campo input, 
        .campo select, 
        .campo button {
            padding: 9px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-accion {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
            height: 38px;
            align-self: flex-end;
            width: 100%;
        }

        .btn-accion:hover {
            background-color: #0056b3;
        }

        .btn-iniciar {
            background-color: #28a745;
        }

        .btn-iniciar:hover {
            background-color: #218838;
        }

        .btn-finalizar {
            background-color: #dc3545;
        }

        .btn-finalizar:hover {
            background-color: #c82333;
        }

        #visualizar {
            height: 480px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 10px;
            z-index: 1;
        }
    </style>
</head>
<body>
    <header class="encabezado-app">
        <h1>Visualización en GPS</h1>
        <nav class="nav-app">
            <a href="../pages/trazabilidad.php">Volver a Trazabilidad</a>
        </nav>
    </header>

    <main>
        <section id="traslado2" class="tarjeta">
            <h1>Detalles del traslado activo</h1>
            <div class="grid-formulario">
                <div class="campo">
                    <label>Unidad:</label>
                    <input type="text" value="Móvil 3" readonly>
                </div>
                <div class="campo">
                    <label>Chofer:</label>
                    <input type="text" value="Mateo" readonly>
                </div>
                <div class="campo">
                    <label for="transporte">Medio de Transporte:</label>
                    <select id="transporte">
                        <option value="drive" selected>Vehículo / Auto</option>
                        <option value="bicycle">Bicicleta</option>
                        <option value="walk">A Pie / Caminando</option>
                    </select>
                </div>
                <div class="campo">
                    <label>Tiempo Estimado:</label>
                    <input type="text" id="tiempoEstimado" value="Sin iniciar" readonly>
                </div>
                <div class="campo">
                    <label>Hora Estimada de Llegada:</label>
                    <input type="text" id="horaLlegada" value="Sin iniciar" readonly>
                </div>
            </div>
        </section>

        <section class="tarjeta">
            <h2 style="color: var(--azul-marino, #0a192f);">Mapa GPS en Tiempo Real</h2>

            <div class="grid-formulario">
                <div class="campo">
                    <label for="departamento">Departamento:</label>
                    <select id="departamento">
                        <option value="Artigas">Artigas</option>
                        <option value="Canelones">Canelones</option>
                        <option value="Cerro Largo">Cerro Largo</option>
                        <option value="Colonia">Colonia</option>
                        <option value="Durazno">Durazno</option>
                        <option value="Flores">Flores</option>
                        <option value="Florida">Florida</option>
                        <option value="Lavalleja">Lavalleja</option>
                        <option value="Maldonado">Maldonado</option>
                        <option value="Montevideo" selected>Montevideo</option>
                        <option value="Paysandú">Paysandú</option>
                        <option value="Río Negro">Río Negro</option>
                        <option value="Rivera">Rivera</option>
                        <option value="Rocha">Rocha</option>
                        <option value="Salto">Salto</option>
                        <option value="San José">San José</option>
                        <option value="Soriano">Soriano</option>
                        <option value="Tacuarembó">Tacuarembó</option>
                        <option value="Treinta y Tres">Treinta y Tres</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="dir">Dirección / Calle:</label>
                    <input type="text" id="dir" placeholder="Ej: Av. Italia 2870">
                </div>

                <div class="campo">
                    <button type="button" id="ubicar" class="btn-accion">Ubicar Dirección</button>
                </div>
            </div>

            <div class="grid-formulario">
                <div class="campo">
                    <label for="lat">Latitud:</label>
                    <input type="text" id="lat" readonly placeholder="Latitud capturada">
                </div>

                <div class="campo">
                    <label for="lng">Longitud:</label>
                    <input type="text" id="lng" readonly placeholder="Longitud capturada">
                </div>

                <div class="campo">
                    <button type="button" id="ingresar0" class="btn-accion">Capturar Coordenadas</button>
                </div>
            </div>

            <!-- Botones de control del traslado -->
            <div class="grid-formulario">
                <div class="campo">
                    <button type="button" id="btnIniciar" class="btn-accion btn-iniciar">Iniciar Traslado</button>
                </div>
                <div class="campo">
                    <button type="button" id="btnFinalizar" class="btn-accion btn-finalizar">Finalizar Traslado</button>
                </div>
            </div>

            <div id="visualizar"></div>
        </section>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Leaflet Fullscreen JS -->
    <script src="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js"></script>

    <!-- Mapa personalizado -->
    <script src="../js/mapa.js"></script>
</body>
</html>