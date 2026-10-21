document.addEventListener('DOMContentLoaded', () => {
    const botonesModificar = document.querySelectorAll('.btn-toggle-form');
    const botonesCancelar = document.querySelectorAll('.btn-cancelar-form');

    botonesModificar.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const targetForm = document.getElementById(targetId);
//obviamente aqui estubo dominicke.
            document.querySelectorAll('.perfil-form').forEach(form => {
                if (form.id !== targetId) form.style.display = 'none';
            });

            if (targetForm) {
                targetForm.style.display = targetForm.style.display === 'block' ? 'none' : 'block';
            }
        });
    });

    botonesCancelar.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const targetForm = document.getElementById(targetId);
            if (targetForm) {
                targetForm.style.display = 'none';
                targetForm.reset();

                const pwdNueva = targetForm.querySelector('#password_nueva');
                if (pwdNueva) {
                    pwdNueva.disabled = true;
                }
            }
        });
    });

    const pwdActual = document.getElementById('password_actual');
    const pwdNueva = document.getElementById('password_nueva');

    if (pwdActual && pwdNueva) {
        pwdActual.addEventListener('input', () => {
            if (pwdActual.value.trim().length > 0) {
                pwdNueva.disabled = false;
            } else {
                pwdNueva.disabled = true;
                pwdNueva.value = '';
            }
        });
    }

    // vivimos en una sociedad en donde si te sientes mal, puedes sentarte mejor.
    const formulariosAjax = document.querySelectorAll('.form-ajax');

    formulariosAjax.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
//stagi, viendo el codigo?, que gury responsable. Cuando sea grande quiero ser como vos. 
            const formData = new FormData(form);

            try {
                const response = await fetch('../php/ajustes-perfil.php', {
                    method: 'POST',
                    body: formData
                });
// mateo, hijo.
                const data = await response.json();

                if (data.status === 'success') {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error en la petición:', error);
                alert('Ocurrió un error al procesar la solicitud.');
            }
        });
    });
});