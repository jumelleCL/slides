<?php

// Incluye archivos de funciones necesarios
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");

// Comprueba si se proporciona un parámetro "id" en la URL
if (isset($_GET["id"])) {
    // Obtiene el ID de la presentación
    $id_presentacio = $_GET["id"];
    // Obtiene el título y las diapositivas de la presentación
    $titol = $dao->getTitolPorID($id_presentacio);
    $diapo = $dao->getDiapositives($id_presentacio);
} else {
    $titol = "Título no disponible";
}
$infoDiapo = FALSE;
$id_diapo = '';
$titolDiapo = "";
$contingut = "";

// Comprueba si se proporciona un parámetro "id_diapo" en la URL
if (isset($_GET["id_diapo"])) {
    $id_diapo = $_GET["id_diapo"];
    if ($id_diapo != '') {
        // Obtiene el título y el contenido de una diapositiva específica
        $titolDiapo = $dao->getTitolDiapoPorID($id_diapo);
        $contingut = $dao->getContingutPorID($id_diapo);
        $pregunta = $dao->getPregunta($id_diapo);
        $id_pregunta = $pregunta['ID_pregunta'];
        $respuestas = $dao->getRespuestas($id_pregunta);

        $infoDiapo = TRUE;
    }
    
}else{
    $infoDiapo =FALSE;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Pantalla Vista Previa Titol Client</title>
    <link rel="stylesheet" href="Styles.css">
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
</head>
<body id="crearDiapositivasContingut">
    <div class="up">
        <div class="volver">
            <button> 
            <svg  class='volverButton' xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                Home
            </button>
        </div>
        <div class="presentacionV2">
            <div class="presentacion-guardada">
                
                <div class='buttons-editar'>
                    <p id="titulo-guardado" class="tituloGuardado"><?php echo $titol; ?> 
                    <form method='post'>
                        <input type="hidden" name="id_presentacion" value="<?= $id_presentacio; ?>">
                        <input type="hidden" name="id_diapo" value="<?= $id_diapo; ?>">
                        <input type="hidden" name="from" value="Vista">
                        <button class='buttons' type="submit" name="previsualizar_client">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>                    
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="down">
        <div class="left">
            <div class="diapositivas">
                <?php while ($row = $diapo->fetch()) : ?>
                    <div class='diapo'>   
                        <div>
                            <form method='post'>
                                <input type="hidden" name="id" value="<?= $id_presentacio?>">
                                <input type="hidden" name="id_diapo" value="<?= $row['ID_Diapositiva'];?>">
                                <button type='submit' name="getInfoDiapoVista" class="button-diapo"><?= $row['titol']; ?></button>
                            </form>
                        </div>
                    </div>
                <?php endwhile ?>  
            </div>
        </div>
        <div class="right">
            <div id="formDiapo">
            <div class="preguntaSimple"><?php if ($infoDiapo === TRUE): ?>
                <input type="text" name="titol" id="titol" class="titolDiapoPregunta" value='<?= $titolDiapo ?>' readonly>
                <textarea class="preguntaDiapo" name="pregunta" id="pregunta" readonly><?= htmlspecialchars($pregunta['pregunta']) ?></textarea>
                <div id="respuestas-container">
                    <?php foreach ($respuestas as $respuesta): ?>
                        <div class="respuesta-container">
                            <input type="radio" name="respuesta_correcta" value="<?= $respuesta['ID_respuesta'] ?>" disabled>
                            <input type="text" name="opcion[]" class="opcionDiapo" id="opcionDiapo" readonly value="<?= htmlspecialchars($respuesta['texto']) ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const button = document.querySelector('.volver');
        //boton para volver a la pantalla inicial
        button.addEventListener('click', function (e) {
            window.location.href = "index.php";
        });

        // Esta función se utiliza para obtener y almacenar el título de la diapositiva en el almacenamiento local
        function obtenerValores() {
            var titolDiapo = document.getElementById('titol').value.toString();
            // Almacena los valores en localStorage para que estén disponibles en la nueva página
            localStorage.setItem('titolDiapo', titolDiapo);
        }
    </script>
</body>
</html>