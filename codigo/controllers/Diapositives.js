function validateForm() {
    // Obtener el elemento del título y el mensaje de error
    const titol = document.getElementById("titol");
    var titleError = document.getElementById("titolError");
    let isValid = true;

    // Verificar si la longitud del título es mayor de 25 caracteres
    if (titol.value.length > 25) {
        titleError.textContent = "El título no puede tener más de 25 caracteres";
        titleError.style.display = 'initial';
        titol.focus();
        isValid = false;
    } else {
        titleError.textContent = ""; // Limpia el mensaje de error si es válido
    }
    return isValid;
}

function validateFormCont() {
    // Obtener los elementos del título, el contenido y los mensajes de error
    const titol = document.getElementById("titol");
    var titleError = document.getElementById("titolError");
    const contingut = document.getElementById("contingut");
    var contError = document.getElementById("contError");
    let isValid = true;

    // Verificar si la longitud del título es mayor de 25 caracteres
    if (titol.value.length > 25) {
        titleError.textContent = "El título no puede tener más de 25 caracteres";
        titleError.style.display = 'initial';
        titol.focus();
        isValid = false;
    } else if (contingut.value.length > 640) { // Si el título es válido, verificar la longitud del contenido
        contError.textContent = "Has superado el límite de caracteres";
        contError.style.display = 'initial';
        contError.focus();
        isValid = false;
    } else {
        titleError.textContent = ""; // Limpia el mensaje de error del título si es válido
        contError.textContent = ""; // Limpia el mensaje de error del contenido si es válido
    }
    return isValid;
}

function validatePregunta(){
    const titol = document.getElementById("titol");
    const pregunta = document.getElementById('pregunta');
    const respuesta = document.querySelectorAll('#opcionDiapo');
    var titleError = document.getElementById("titolError");
    let isValid = true;

    if (titol.value.length > 25) {
        titleError.textContent = "El título no puede tener más de 25 caracteres";
        titleError.style.display = 'initial';
        titol.focus();
        isValid = false;
    }else if(pregunta.value.length > 65){
        titleError.textContent = "La pregunta no puede tener más de 65 caracteres";
        titleError.style.display = 'initial';
        titol.focus();
        isValid = false;
    }
    respuesta.forEach(ele => {
       if (ele.value.length > 25) {
        titleError.textContent = "Las respuestas no puede tener más de 110 caracteres";
        titleError.style.display = 'initial';
        titol.focus();
        isValid = false;
       } 
    });

    return isValid;
}
