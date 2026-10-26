document.addEventListener('DOMContentLoaded', () => {
    const formAmbulancia = document.getElementById('formAmbulancia');
    const tituloForm = document.getElementById('tituloForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnCancelar = document.getElementById('btnCancelar');
    const inputAccion = document.getElementById('accion');
    const inputSerieOriginal = document.getElementById('serie_original');

    const inputSerie = document.getElementById('numero_de_serie');
    const inputKilometraje = document.getElementById('kilometraje');
    const inputMatricula = document.getElementById('matricula');

    const botonesEditar = document.querySelectorAll('.btn-tabla-editar');

    botonesEditar.forEach(boton => {
        boton.addEventListener('click', () => {
            const serie = boton.getAttribute('data-serie');
            const kilometraje = boton.getAttribute('data-kilometraje');
            const matricula = boton.getAttribute('data-matricula');

            // Rellenar los campos del formulario de forma segura
            if (inputSerie) inputSerie.value = serie;
            if (inputKilometraje) inputKilometraje.value = kilometraje;
            if (inputMatricula) inputMatricula.value = matricula;

            // Cambiar el estado del formulario a modo edición
            if (inputAccion) inputAccion.value = 'editar';
            if (inputSerieOriginal) inputSerieOriginal.value = serie; // Clave para que el backend sepa qué registro actualizar

            if (tituloForm) tituloForm.textContent = 'Editar Ambulancia #' + serie;
            if (btnSubmit) btnSubmit.textContent = 'Actualizar Ambulancia';
            if (btnCancelar) btnCancelar.style.display = 'inline-block';

            // Desplazar suavemente hacia arriba para ver el formulario activo
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            formAmbulancia.reset();
            if (inputAccion) inputAccion.value = 'guardar';
            if (inputSerieOriginal) inputSerieOriginal.value = '';
            if (tituloForm) tituloForm.textContent = 'Registro ambulancias';
            if (btnSubmit) btnSubmit.textContent = 'Registrar traslado de ambulancia';
            btnCancelar.style.display = 'none';
        });
    }
});