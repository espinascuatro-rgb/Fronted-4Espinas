<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trazabilidad - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.9em;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pendiente { background-color: #ffc107; color: #212529; }
        .badge-proceso { background-color: #28a745; color: #ffffff; }
        .badge-finalizado { background-color: #6c757d; color: #ffffff; }

        .timeline-list {
            list-style: none;
            padding-left: 0;
            position: relative;
            margin-top: 15px;
        }

        .timeline-item {
            position: relative;
            padding-left: 25px;
            margin-bottom: 12px;
            border-left: 3px solid #007bff;
            transition: all 0.3s;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #007bff;
        }

        .timeline-item.inactivo {
            border-left-color: #ccc;
            color: #888;
        }

        .timeline-item.inactivo::before {
            background-color: #ccc;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 15px;
        }

        .info-card {
            background-color: #f8f9fa;
            padding: 12px 16px;
            border-radius: 6px;
            border-left: 4px solid #0a192f;
        }
    </style>
</head>
<body>
    <header class="encabezado-app">
        <img src="../fotos-videos/Logo-Hospital.png" alt="Logo del hospital" class="logo">
        <div>
            <h1>Hospital de Clínicas</h1>
            <h2>Trazabilidad de Pacientes y Ambulancias</h2>
        </div>
        <nav class="nav-app">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ver-ruta.php">GPS Ver Ruta</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main>
        <section id="traslado1" class="tarjeta"> 
            <p><strong>Buscar código de traslado:</strong></p>
            <div style="display: flex; gap: 10px; max-width: 400px;">
                <input type="number" id="inputBuscarId" placeholder="Ingrese ID (Ej: 1)" value="1" style="flex: 1; padding: 8px;">
                <button type="button" id="btnRastrear" class="btn-primary" style="padding: 8px 15px;">Rastrear</button>
            </div>
        </section>
        
        <section class="tarjeta">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <h2 style="color: var(--azul-marino);">Estado del Traslado #<span id="lblIdTraslado">1</span></h2>
                <span id="badgeEstado" class="badge badge-pendiente">Cargando...</span>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <strong>Ambulancia / Unidad:</strong>
                    <div id="lblAmbulancia" style="font-weight: bold; color: #007bff; font-size: 1.1em; margin-top: 4px;">Cargando...</div>
                </div>
                <div class="info-card">
                    <strong>Chofer Asignado:</strong>
                    <div id="lblChofer" style="font-weight: bold; color: #007bff; font-size: 1.1em; margin-top: 4px;">Cargando...</div>
                </div>
                <div class="info-card">
                    <strong>Acompañante:</strong>
                    <div id="lblAcompanante" style="font-weight: bold; color: #007bff; font-size: 1.1em; margin-top: 4px;">Sin acompañante</div>
                </div>
            </div>

            <hr>
            
            <h3 style="color: var(--azul-medio);">Monitoreo de Estado</h3>
            <ul class="timeline-list">
                <li id="step1" class="timeline-item">
                    <strong>Estado del GPS:</strong> <span id="lblEstadoTexto">Esperando transmisión...</span>
                </li>
            </ul>
        </section>
    </main>

    <footer class="pie-app">
        <p>Equipo 4 Espinas</p>
        <button><a href="../php/logout.php" style="color: white; text-decoration: none;">Cerrar Sesión</a></button>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let idActual = 1;

            const inputBuscarId = document.getElementById('inputBuscarId');
            const btnRastrear = document.getElementById('btnRastrear');

            const lblIdTraslado = document.getElementById('lblIdTraslado');
            const badgeEstado = document.getElementById('badgeEstado');
            const lblAmbulancia = document.getElementById('lblAmbulancia');
            const lblChofer = document.getElementById('lblChofer');
            const lblAcompanante = document.getElementById('lblAcompanante');
            const lblEstadoTexto = document.getElementById('lblEstadoTexto');
            const step1 = document.getElementById('step1');

            async function consultarTrazabilidad() {
                try {
                    const res = await fetch(`../php/actualizar_posicion.php?accion=obtener_estado&id_traslado=${idActual}&t=${new Date().getTime()}`);
                    const json = await res.json();

                    if (json.status === 'ok' && json.data) {
                        const d = json.data;

                        lblIdTraslado.textContent = d.id_traslado;
                        
                        lblAmbulancia.textContent = (d.matricula_ambulancia && d.matricula_ambulancia.trim() !== '') ? `Móvil / Matrícula #${d.matricula_ambulancia}` : 'Sin asignar';
                        
                        if (d.nombre_usuario_chofer) {
                            lblChofer.textContent = `${d.nombre_usuario_chofer} (CI: ${d.cedula_funcionario})`;
                        } else if (d.cedula_funcionario && d.cedula_funcionario.trim() !== '') {
                            lblChofer.textContent = `CI: ${d.cedula_funcionario}`;
                        } else {
                            lblChofer.textContent = 'Sin asignar';
                        }

                        lblAcompanante.textContent = (d.acompanante && d.acompanante.trim() !== '') ? d.acompanante : 'Sin acompañante';

                        const estado = d.estado || 'pendiente';

                        if (estado === 'en_proceso') {
                            badgeEstado.className = 'badge badge-proceso';
                            badgeEstado.textContent = 'En Tránsito / Activo';
                            lblEstadoTexto.textContent = 'Móvil en movimiento con señal GPS activa';
                            step1.classList.remove('inactivo');

                        } else if (estado === 'finalizado') {
                            badgeEstado.className = 'badge badge-finalizado';
                            badgeEstado.textContent = 'Finalizado';
                            lblEstadoTexto.textContent = 'Traslado completado exitosamente';
                            step1.classList.remove('inactivo');

                        } else {
                            badgeEstado.className = 'badge badge-pendiente';
                            badgeEstado.textContent = 'Pendiente / En Espera';
                            lblEstadoTexto.textContent = 'Esperando inicio de traslado desde el panel administrativo';
                            step1.classList.add('inactivo');
                        }
                    } else {
                        badgeEstado.className = 'badge badge-pendiente';
                        badgeEstado.textContent = 'No Encontrado';
                        lblChofer.textContent = 'ID Inexistente';
                        lblAmbulancia.textContent = '-';
                        lblAcompanante.textContent = '-';
                        lblEstadoTexto.textContent = 'Sin registro';
                    }
                } catch (e) {
                    console.error('Error actualizando trazabilidad:', e);
                }
            }

            consultarTrazabilidad();
            setInterval(consultarTrazabilidad, 2000);

            btnRastrear.addEventListener('click', (e) => {
                e.preventDefault();
                const nuevoId = parseInt(inputBuscarId.value);
                if (nuevoId > 0) {
                    idActual = nuevoId;
                    consultarTrazabilidad();
                }
            });
        });
    </script>
</body>
</html>