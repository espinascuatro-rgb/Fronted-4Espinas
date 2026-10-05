document.addEventListener('DOMContentLoaded', () => {
    // Inicializar el mapa
    const mapa = L.map('visualizar').setView([-34.9011, -56.1645], 15);

    // Cargar mapa desde OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    // Iconos personalizados desde la carpeta ../mape/
    const ImagenMarcador = L.icon({
        iconUrl: '../mape/mapo1.png',
        iconSize: [38, 45],
        iconAnchor: [19, 45],
        popupAnchor: [0, -40]
    });

    const ImagenDestino = L.icon({
        iconUrl: '../mape/mapo2.png',
        iconSize: [38, 45],
        iconAnchor: [19, 45],
        popupAnchor: [0, -40]
    });

    // Marcadores iniciales
    const marcador = L.marker([-34.9011, -56.1645], {
        draggable: true,
        icon: ImagenMarcador
    }).addTo(mapa);

    const marcadorDestino = L.marker([-34.900362, -56.163353], { 
        draggable: true, 
        icon: ImagenDestino 
    }).addTo(mapa);

    // Elementos del DOM
    const ingresaro = document.getElementById('ingresar0');
    const ubicar = document.getElementById('ubicar');
    const departamento = document.getElementById('departamento');
    const direccion = document.getElementById('dir');
    const insertlat = document.getElementById('lat');
    const insertlng = document.getElementById('lng');

    // Botón para obtener lat/lng del marcador movible
    ingresaro.addEventListener('click', () => {
        const posicion = marcador.getLatLng();
        insertlat.value = posicion.lat.toFixed(6);
        insertlng.value = posicion.lng.toFixed(6);
    });

    // Botón para geolocalizar dirección en Uruguay
    ubicar.addEventListener('click', async () => {
        if (!direccion.value.trim()) {
            alert('Por favor ingrese una dirección.');
            return;
        }

        const texto = `${direccion.value}, ${departamento.value}, Uruguay`;
        const url = `https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(texto)}&filter=countrycode:uy&lang=es&limit=1&format=json&apiKey=85d1d62262044361831644c6f04d5152`;

        try {
            const respuesta = await fetch(url);
            const dato = await respuesta.json();

            if (dato.results && dato.results.length > 0) {
                const resultado = dato.results[0];
                mapa.setView([resultado.lat, resultado.lon], 16);

                const marcadorBusqueda = L.marker([resultado.lat, resultado.lon], { 
                    draggable: true, 
                    icon: ImagenMarcador 
                }).addTo(mapa);

                marcadorBusqueda.bindPopup(`<b>Ubicación:</b><br>${resultado.formatted}`).openPopup();
                alert(`Coordenadas encontradas:\nLatitud: ${resultado.lat}\nLongitud: ${resultado.lon}`);
            } else {
                alert('No se encontraron resultados para la dirección introducida.');
            }
        } catch (error) {
            console.error('Error al realizar la búsqueda:', error);
            alert('Error al conectar con el servicio de mapas.');
        }
    });

    // Fuerza la renderización correcta del mapa al cargar la vista
    setTimeout(() => {
        mapa.invalidateSize();
    }, 300);
});