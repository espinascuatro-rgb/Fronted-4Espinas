document.addEventListener('DOMContentLoaded', () => {
    const enlacesEncuesta = document.querySelectorAll('.btn-completar-encuesta');
//dominicke estubo aqui.
    enlacesEncuesta.forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            console.log('Abriendo encuesta:', enlace.href);
        });
    });
});