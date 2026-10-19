document.addEventListener('DOMContentLoaded', () => {
    const btnMostrarEditar = document.getElementById('btn-mostrar-editar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const datosPerfil = document.getElementById('datos-perfil');
    const formEditarPerfil = document.getElementById('form-editar-perfil');
    
    const pwdActual = document.getElementById('password_actual');
    const pwdNueva = document.getElementById('password_nueva');

    // 1. Mostrar formulario y ocultar vista estática
    if (btnMostrarEditar) {
        btnMostrarEditar.addEventListener('click', () => {
            datosPerfil.style.display = 'none';
            formEditarPerfil.style.display = 'block';
        });
    }

    // 2. Ocultar formulario y volver a la vista estática
    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            formEditarPerfil.style.display = 'none';
            datosPerfil.style.display = 'block';
            formEditarPerfil.reset(); // Limpia los cambios si cancela
            pwdNueva.disabled = true; // Vuelve a bloquear la contraseña nueva
            pwdNueva.required = false;
        });
    }

    // 3. Habilitar la contraseña nueva solo si se escribe la actual
    if (pwdActual && pwdNueva) {
        pwdActual.addEventListener('input', () => {
            if (pwdActual.value.trim().length > 0) {
                pwdNueva.disabled = false;
                pwdNueva.required = true;
            } else {
                pwdNueva.disabled = true;
                pwdNueva.required = false;
                pwdNueva.value = '';
            }
        });
    }

    if (formEditarPerfil) {
        formEditarPerfil.addEventListener('submit', async (e) => {
            e.preventDefault(); // Evita que la página recargue normalmente

            const formData = new FormData(formEditarPerfil);

            try {
                const response = await fetch('../php/ajustes-perfil.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'success') {
                    alert(data.message);
                    // Recarga la página para mostrar los datos de la sesión actualizados
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error en la petición:', error);
                alert('Ocurrió un error al procesar la solicitud.');
            }
        });
    }
});