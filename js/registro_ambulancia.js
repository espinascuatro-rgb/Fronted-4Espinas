document.addEventListener('DOMContentLoaded', () => {
    const tituloForm = document.getElementById('tituloForm');
    const accion = document.getElementById('accion');
    const serieOriginal = document.getElementById('serie_original');
    const formAmbulancia = document.getElementById('formAmbulancia');
    const inputSerie = document.getElementById('numero_de_serie');
    const inputKms = document.getElementById('kilometraje');
    const inputMatricula = document.getElementById('matricula');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnCancelar = document.getElementById('btnCancelar');


    document.querySelectorAll('.btn-tabla-editar').forEach(boton => {
        boton.addEventListener('click', () => {
            try {

                const serie = boton.getAttribute('data-serie');
                const kilometraje = boton.getAttribute('data-kilometraje');
                const matricula = boton.getAttribute('data-matricula');

                if (tituloForm) tituloForm.textContent = "Modificar Ambulancia";
                if (accion) accion.value = "editar";
                if (serieOriginal) serieOriginal.value = serie;

                if (inputSerie) {
                    inputSerie.value = serie;
                    inputSerie.readOnly = true; 
                }
                if (inputKms) inputKms.value = kilometraje;
                if (inputMatricula) inputMatricula.value = matricula;

                if (btnSubmit) btnSubmit.textContent = "Actualizar ambulancia";
                if (btnCancelar) btnCancelar.style.display = "inline-block";

                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch (error) {
                console.error("Error al cargar los datos para editar:", error);
            }
        });
    });


    if (btnCancelar && formAmbulancia) {
        btnCancelar.addEventListener('click', () => {
            if (tituloForm) tituloForm.textContent = "Registro ambulancias";
            if (accion) accion.value = "guardar";
            if (serieOriginal) serieOriginal.value = "";
            formAmbulancia.reset();
            if (inputSerie) inputSerie.readOnly = false;
            if (btnSubmit) btnSubmit.textContent = "Registrar traslado de ambulancia";
            btnCancelar.style.display = "none";
        });
    }

    // Confirmación de eliminación
    document.querySelectorAll('.link-eliminar-confirmar').forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            if (!confirm('¿Está seguro de eliminar esta ambulancia?')) {
                e.preventDefault();
            }
        });
    });
});