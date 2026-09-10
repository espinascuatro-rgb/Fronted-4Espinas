document.getElementById('FLogin').addEventListener('submit', async function (e) {
  e.preventDefault(); 

  const formData = new FormData(this);

  try {
    const response = await fetch('php/login.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.status === 'success') {
      window.location.href = result.redirect;
    } else {
      alert(result.message);
    }
  } catch (error) {
    alert('Hubo un error al procesar la solicitud.');
  }
});