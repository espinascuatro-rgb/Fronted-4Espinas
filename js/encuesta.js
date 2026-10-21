document.addEventListener('DOMContentLoaded', () => {
    // Permitir apertura limpia de Google Forms en pestañas nuevas
    const enlacesEncuesta = document.querySelectorAll('.btn-completar-encuesta');

    enlacesEncuesta.forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            // No interceptar para permitir la apertura inmediata a Google Forms
            console.log('Abriendo encuesta:', enlace.href);
        });
    });
});