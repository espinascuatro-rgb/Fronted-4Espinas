document.addEventListener('DOMContentLoaded', () => {
    const enlacesEncuesta = document.querySelectorAll('.btn-completar-encuesta');

    enlacesEncuesta.forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            console.log('Abriendo encuesta:', enlace.href);
        });
    });
});