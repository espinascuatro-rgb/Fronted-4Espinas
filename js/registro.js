document.getElementById('FRegistro').addEventListener('submit', async function (e) { 
  e.preventDefault(); 

  const formData = new FormData(this);

  try {
    const response = await fetch('../php/insercionT.php', { 
      method: 'POST',
      body: formData
    });
    const textData = await response.text();
    console.log("Respuesta del servidor:", textData);

    let result;
    try {
      result = JSON.parse(textData);
    } catch (err) {
      alert("Error del servidor:\n" + textData);
      return;
    }

    if (result.status === 'success') { 
      alert(result.message); 
      window.location.href = '../index.html'; 
    } else {
      alert(result.message); 
    }
  } catch (error) {
    alert('Hubo un error de red o en el fetch: ' + error.message);
  }
});