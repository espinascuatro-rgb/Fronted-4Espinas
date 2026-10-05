document.getElementById('FLogin').addEventListener('submit', async function (e) { // hace un evento al formo cuando envia el form y hace una funcion igual al registro.
  e.preventDefault(); 

  const formData = new FormData(this);

  try {
    const response = await fetch('php/login.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.status === 'success') {
      window.location.href = result.redirect; // redirige a la pagina que el script de login le dice.
    } else {
      alert(result.message);
    }
  } catch (error) {
    alert('Hubo un error al procesar la solicitud.');
  }
});