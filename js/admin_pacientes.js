document.addEventListener('DOMContentLoaded', () => {
    const tituloForm = document.getElementById('tituloForm');
    const accion = document.getElementById('accion');
    const cedulaOriginal = document.getElementById('cedula_original');
    const formPaciente = document.getElementById('formPaciente');
    const inputCedula = document.getElementById('cedula');
    const inputPrimerNombre = document.getElementById('primer_nombre');
    const inputSegundoNombre = document.getElementById('segundo_nombre');
    const inputPrimerApellido = document.getElementById('primer_apellido');
    const inputSegundoApellido = document.getElementById('segundo_apellido');
    const inputCarneSalud = document.getElementById('carne_de_salud');
    const inputTelefono = document.getElementById('numero_de_telefono');
    const inputDireccion = document.getElementById('direccion');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnCancelar = document.getElementById('btnCancelar');

    document.querySelectorAll('.btn-tabla-editar').forEach(boton => {
        boton.addEventListener('click', () => {
            const paciente = JSON.parse(boton.getAttribute('data-paciente'));
            
            tituloForm.textContent = "Editar Datos del Paciente";
            accion.value = "editar";
            cedulaOriginal.value = paciente.cedula;

            inputCedula.value = paciente.cedula;
            inputCedula.readOnly = true;
            inputPrimerNombre.value = paciente.primer_nombre || '';
            inputSegundoNombre.value = paciente.segundo_nombre || '';
            inputPrimerApellido.value = paciente.primer_apellido || '';
            inputSegundoApellido.value = paciente.segundo_apellido || '';
            inputCarneSalud.value = paciente.carne_de_salud || '';
            inputTelefono.value = paciente.numero_de_telefono || '';
            inputDireccion.value = paciente.direccion || '';

            btnSubmit.textContent = "Actualizar Paciente";
            btnCancelar.style.display = "inline-block";

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            tituloForm.textContent = "Agregar Nuevo Paciente";
            accion.value = "agregar";
            formPaciente.reset();
            inputCedula.readOnly = false;
            btnSubmit.textContent = "Guardar Paciente";
            btnCancelar.style.display = "none";
        });
    }

    document.querySelectorAll('.link-eliminar-confirmar').forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            if (!confirm('¿Está seguro de eliminar este paciente?')) {
                e.preventDefault();
            }
        });
    });
});