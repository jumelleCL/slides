<?php

// Incluye archivos de funciones necesarios
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");

if (isset($_GET["id"])) {
    $fondoBlancoChecked = '';
    $fondoNegroChecked = '';

    // Comprueba si el estilo de presentación es "fondoBlanco" o "fondoNegro" y marca el radio button correspondiente
    if ($estiloPresentacion === 'fondoBlanco') {
        $fondoBlancoChecked = 'checked';
    } elseif ($estiloPresentacion === 'fondoNegro') {
        $fondoNegroChecked = 'checked';
    }
} else {
    $titol = "Error, no se encuentra la presentacion";

}
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}
if (isset($_GET['id_diapo'])) {
    $id_diapo = $_GET['id_diapo'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Estilos</title>
    <link rel="stylesheet" href="Styles.css">
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
</head>
<body>  
    <div class="up">
        <div class="volver">
            <!-- Botón "Volver" con un icono -->
            <button> 
                <!-- Icono SVG para "Volver" -->
            <svg class="volverButton" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#000000}</style><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
                Volver
            </button>
        </div>
        
    </div>
<h1 class="tituloSeleccionarEstilos">Seleccionar Estilos</h1>
<div class="containerSeleccionarEstilos">

    <form class="containerSeleccionarEstilos" method="post" action="">
    <input type="hidden" id="type" name="type" value="<?=$type?>">
    <div>
        <input type="radio" id="fondoBlanco" name="estilos" value="fondoBlanco" <?php echo $fondoBlancoChecked; ?>>
        <label for="fondoBlanco">Estilo 1</label>

        <!-- Ejemplo de diapositiva para el "Estilo 1" -->
        <div class="diapositivaExemple1">
            <h4 class="diapositivaExemple1Titol">Titulo Ejemplo</h4>
            <p class="diapositivaExemple1Contingut">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate quasi debitis atque 
                est enim animi ut facilis voluptatum, accusamus harum nemo quae nam ab incidunt similique quos aliquid deserunt sit.</p>
        </div>

    </div>


    <div>

    <input type="radio" id="fondoNegro" name="estilos" value="fondoNegro" <?php echo $fondoNegroChecked; ?>>
        <label for="fondoNegro">Estilo 2</label>
        <input type="hidden" name="id_presentacion" value="' . $id_presentacion . '">
        
        <!-- Ejemplo de diapositiva para el "Estilo 2" -->
        <div class="diapositivaExemple2">
            <h4 class="diapositivaExemple2Titol">Titulo Ejemplo</h4>
            <p class="diapositivaExemple2Contingut">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus nam expedita ducimus 
                totam ratione incidunt, adipisci animi nulla illo, ex blanditiis iure dolores! Voluptatum, laboriosam ea quaerat quam alias dolor.</p>
        </div>

    </div>
        

        
    </div>
        <div>
            <!-- Botón para enviar la selección de estilos -->
            <button class="enviarEstils" type="submit" name="enviarEstilos">Guardar Estilos</button>
        </div>
        
    </form>
    
</div>

<?php
// Verificar si se ha recibido el ID de la presentación
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_presentacion = $_GET['id'];

    // Procesar el formulario cuando se envía
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviarEstilos"])) {
        $estilos = $_POST["estilos"];
        $type = $_POST['type'];

        // Editar los estilos de la presentación en la base de datos
        $dao->editarEstilsPresentacio($id_presentacion, $estilos);

        $mensaje = 'Estilo cambiado exitosamente.';
        
        $mensajeCodificado = base64_encode($mensaje);

        
       // Redirigir a la página adecuada según el contenido
        switch ($type) {
            case 'Titol':
                header("Location: editarDiapositivesTitol.php?id=" . $id_presentacio . "&id_diapo=".$id_diapo . "&mensaje=" . $mensajeCodificado);
                break;
            case 'Contingut':
                header("Location: editarDiapositivesContingut.php?id=" . $id_presentacio . "&id_diapo=".$id_diapo . "&mensaje=" . $mensajeCodificado);
                break;

            case 'Imatge':
                header("Location: editarDiapositivesImatge.php?id=" . $id_presentacio . "&id_diapo=".$id_diapo . "&mensaje=" . $mensajeCodificado);
                break;
            case 'Pregunta':
                header("Location: editarDiapositivesPregunta.php?id=" . $id_presentacio . "&id_diapo=".$id_diapo . "&mensaje=" . $mensajeCodificado);
                break;
            default:
                header("Location: editarDiapositivesTitol.php?id=" . $id_presentacio . "&id_diapo=".$id_diapo . "&mensaje=" . $mensajeCodificado);
                break;
        }
        
    }
} else {
    echo 'Error: No se proporcionó un ID de presentación válido.';
}

?>

</body>
<script>
    // Agrega un evento de clic al botón "Volver" para regresar a la página anterior
    const btn = document.querySelector('.volver');
    btn.addEventListener('click', function (e) {
        window.history.back();
    });
</script>
</html>
