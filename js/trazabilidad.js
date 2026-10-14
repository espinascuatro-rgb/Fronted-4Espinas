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