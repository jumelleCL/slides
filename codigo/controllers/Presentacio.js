// Obtener referencias a elementos HTML por su ID o clase
const titPres = document.getElementById('titulo');
const descPres = document.getElementById('descripcion');
const button = document.querySelector('.volver');
const downElements = document.querySelectorAll('.down .left, .down .right'); // Selecciona todos los elementos dentro de "down"
const presentacionGuardada = document.querySelector('.presentacion-guardada');
const tituloGuardado = document.getElementById('titulo-guardado');
const añadir = document.querySelector('.añadir');
const descripcionGuardada = document.getElementById('descripcion-guardada');
const passwordInput = document.getElementById('password');
const toggleButton = document.getElementById('togglePassword');
const toggleButtonOpen = document.getElementById('togglePasswordOpen');

// Variables para almacenar el título y la descripción de la presentación
let tituloPresentacion = '';
let descripcionPresentacion = '';

// Evento click en el botón "volver" para redirigir a otra página
button.addEventListener('click', function (e) {
    document.location.href = 'index.php';
});

// Función para validar el formulario
function validateForm() {
    const titol = document.forms["formPresentacio"]["titol"].value;
    const descripcio = document.forms["formPresentacio"]["descripcio"].value;
    const password = document.forms["formPresentacio"]["password"].value;
    let isValid = true;
    var titleError = document.getElementById("titolError");
    var passwordError = document.getElementById("passwordError");
    
    // Validar el título
    if (titol === "") {
        isValid = false;
    } else if (titol.length > 30) {
        titleError.textContent = "El título no debe tener más de 30 caracteres.";
        titleError.style.display = 'initial';
        isValid = false;
    } else {
        document.getElementById("titolError").innerText = "";
    }

    // Validar la contraseña (PIN)
    if (password.length > 8) {
        passwordError.textContent = "El PIN no debe tener más de 8 caracteres."
        passwordError.style.display = 'initial';
        isValid = false;
    } else {
        document.getElementById("passwordError").innerText = "";
    }
    
    return isValid;
}

// Eventos para mostrar/ocultar la contraseña en el input de contraseña (PIN)
toggleButton.addEventListener('click', function () {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.style.display = 'none';
        toggleButtonOpen.style.display = 'block';
    }
})

toggleButtonOpen.addEventListener('click', function () {
    if (passwordInput.type === 'text') {
        passwordInput.type = 'password';
        toggleButton.style.display = 'block';
        toggleButtonOpen.style.display = 'none';
    }
})
