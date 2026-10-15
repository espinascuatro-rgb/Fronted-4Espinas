function editarPaciente(p) {
    document.getElementById('tituloForm').textContent = "Editar Datos del Paciente";
    document.getElementById('accion').value = "editar";
    document.getElementById('cedula_original').value = p.cedula;
    
    document.getElementById('cedula').value = p.cedula;
    document.getElementById('cedula').readOnly = true;
    document.getElementById('primer_nombre').value = p.primer_nombre || '';
    document.getElementById('segundo_nombre').value = p.segundo_nombre || '';
    document.getElementById('primer_apellido').value = p.primer_apellido || '';
    document.getElementById('segundo_apellido').value = p.segundo_apellido || '';
    document.getElementById('carne_de_salud').value = p.carne_de_salud || '';
    document.getElementById('numero_de_telefono').value = p.numero_de_telefono || '';
    document.getElementById('direccion').value = p.direccion || '';

    document.getElementById('btnSubmit').textContent = "Actualizar Paciente";
    document.getElementById('btnCancelar').style.display = "inline-block";
}

document.addEventListener('DOMContentLoaded', () => {
    const btnCancelar = document.getElementById('btnCancelar');
    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            document.getElementById('tituloForm').textContent = "Agregar Nuevo Paciente";
            document.getElementById('accion').value = "agregar";
            document.getElementById('formPaciente').reset();
            document.getElementById('cedula').readOnly = false;
            document.getElementById('btnSubmit').textContent = "Guardar Paciente";
            btnCancelar.style.display = "none";
        });
    }
});