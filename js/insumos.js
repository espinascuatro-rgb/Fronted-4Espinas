document.addEventListener('DOMContentLoaded', () => {
    const selectAmbulancia = document.getElementById('numero_de_serie_ambulancia');

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
            console.error("Error al obtener los insumos:", e);
        }
    }

    if (selectAmbulancia) {
        selectAmbulancia.addEventListener('change', (e) => {
            cargarInsumosAmbulancia(e.target.value);
        });
    }

    document.querySelectorAll('.btn-editar-insumos').forEach(boton => {
        boton.addEventListener('click', () => {
            const serie = boton.getAttribute('data-serie');
            if (selectAmbulancia) {
                selectAmbulancia.value = serie;
                cargarInsumosAmbulancia(serie);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    document.querySelectorAll('.link-eliminar-confirmar').forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            if (!confirm('¿Desea eliminar todos los insumos cargados en esta ambulancia?')) {
                e.preventDefault();
            }
        });
    });
});