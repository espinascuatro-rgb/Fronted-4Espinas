const credencial = document.getElementById("credencial");
const error = document.getElementById("errorCredencial");

credencial.addEventListener("input", function() {
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/.test(this.value)) {
        error.textContent = "Caracteres no válidos. Solo se permiten letras.";
    } else {
        error.textContent = "";
    }
});