document.getElementById('FRegistro').addEventListener('submit', async function (e) {
  e.preventDefault(); 

  const formData = new FormData(this);

  try {
    const response = await fetch('../php/insercionT.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.status === 'success') {
      alert(result.message); 
      window.location.href = '../index.html'; 
    } else {
      alert(result.message); 
    }
  } catch (error) {
    alert('Hubo un error al procesar la solicitud.');
  }
});