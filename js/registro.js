document.getElementById('FRegistro').addEventListener('submit', async function (e) { // agrega un evento al form cuando se envia el form, y hace una funcion para que no se recargue la pagina.
  e.preventDefault(); // evita que se recargue la pagina al enviar el form.

  const formData = new FormData(this); // crea un objeto FormData con los datos del form.

  try {
    const response = await fetch('../php/insercionT.php', { // pide al script de registro los datos del form y espera la respuesta.
      method: 'POST',
      body: formData
    });

    const result = await response.json(); // espera la respuesta del script de inserciont y lo hace json.

    if (result.status === 'success') { // si el status es succes, muestra un mensaje y redirige al index.
      alert(result.message); 
      window.location.href = '../index.html'; 
    } else {
      alert(result.message); 
    }
  } catch (error) {
    alert('Hubo un error al procesar la solicitud.');
  }
});