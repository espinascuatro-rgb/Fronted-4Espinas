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
            const amb = JSON.parse(boton.getAttribute('data-ambulancia'));

            tituloForm.textContent = "Modificar Ambulancia";
            accion.value = "editar";
            serieOriginal.value = amb.numero_de_serie;

            inputSerie.value = amb.numero_de_serie;
            inputSerie.readOnly = true;
            inputKms.value = amb.kilometraje || '';
            inputMatricula.value = amb.matricula || '';

            btnSubmit.textContent = "Actualizar ambulancia";
            btnCancelar.style.display = "inline-block";

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            tituloForm.textContent = "Registro de Ambulancias";
            accion.value = "guardar";
            formAmbulancia.reset();
            inputSerie.readOnly = false;
            btnSubmit.textContent = "Registrar ambulancia";
            btnCancelar.style.display = "none";
        });
    }

    document.querySelectorAll('.link-eliminar-confirmar').forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            if (!confirm('¿Está seguro de eliminar esta ambulancia?')) {
                e.preventDefault();
            }
        });
    });
});