<?php

// Incluye los archivos necesarios
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");

// Verifica si se ha proporcionado un parámetro "id" en la URL
if (isset($_GET["id"])) {
    // Obtiene el título y la descripción de la presentación a partir del ID proporcionado
    $id_presentacio = $_GET["id"];
    $titol = $dao->getTitolPorID($id_presentacio);
    $desc = $dao->getDescPorID($id_presentacio);
} else {
    $titol = "Error, no se encuentra la presentacion";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Pantalla Editar Presentació</title>
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <link rel="stylesheet" href="Styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
</head>
<body id="crearPresentacio">
    <div class="up">
        <div class="volver">
            <!-- Botón de regreso a la página de inicio -->
            <button> 
            <svg  class='volverButton' xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>

                Home
            </button>
        </div>
        <div class="presentacion">
            <form method="POST" id="formPresentacio" onsubmit="return validateForm();">
                <input type="hidden" name="id_presentacio" value="<?= $id_presentacio; ?>">
                <!-- Campo para el título de la presentación -->
                <input type="text" name="titol" class="titol" value="<?php echo $titol; ?>" placeholder="Titulo de la presentación" maxlength="30" required>
                <span id="titolError" class="error"></span>
                <!-- Campo para la descripción de la presentación -->
                <input type="text" name="descripcio" class="descripcio" value="<?php echo $desc; ?>" placeholder="Descripción" required></textarea>
                <span id="descripcioError" class="error"></span>
                <div >
                    <!-- Enlace para volver a la página de edición de diapositivas de título -->
                    <a href="editarDiapositivesTitol.php?id=<?= $id_presentacio?>" class="boton-volver">Volver</a>
                    <!-- Botón para cambiar la presentación -->
                    <input type="submit" name="cambiarPresentacion" class="boton-crear" value="Cambiar"> 
                </div>  
            </form>
        </div>
    </div>
    <script>
    const button = document.querySelector('.volver');
    // Agrega un evento de clic al botón de regreso para redirigir a la página de inicio
    button.addEventListener('click', function (e) {
        document.location.href = 'index.php';
    });

    // Función para validar el formulario
    function validateForm() {
    const titol = document.forms["formPresentacio"]["titol"].value;
    let isValid = true;
    var titleError = document.getElementById("titolError");
    
    if (titol === "") {
        isValid = false;
    } else if( titol.length > 30){
        titleError.textContent = "El título no debe tener más de 30 caracteres.";
        isValid = false;
    } else {
        document.getElementById("titolError").innerText = "";
    }
    
    return isValid;
}
    </script>
</body>
</html>
