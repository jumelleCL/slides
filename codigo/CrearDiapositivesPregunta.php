
<?php
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");

if (isset($_GET["id"])) {
    $id_presentacio = $_GET["id"];
    $titol = $dao->getTitolPorID($id_presentacio);
    $diapo = $dao->getDiapositives($id_presentacio);
    
} else {
    $titol = "Título no disponible";
}
$infoDiapo = FALSE;
$id_diapo = '';
$titolDiapo = "";
$contingut = "";

if (isset($_GET["id_diapo"])) {
    $id_diapo = $_GET["id_diapo"];
    if ($id_diapo != '') {
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
    <title>Pantalla Crear Diapositivas Pregunta</title>
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <link rel="stylesheet" href="Styles.css">
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
                        <input type="hidden" name="from" value="Crear">
                        <button class='buttons' type="submit" name="previsualizar_presentacion">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>                    
                        </button>
                    </form>
                </div>
            </div>
            
            
        </div>
    </div>

    <div class="down">
        <div class="left">
            <div class="nuevaDiapositiva">
                <button name="tipusTitol" class="buttonType">Titulo</button>
                <button name="tipusContingut" class="buttonType">Contenido</button>
                <button name="tipusImatge" class="buttonType">Imagen</button>
                <button name="tipusSeleccioSimple" class="buttonType">Pregunta</button>
            </div>
            <div class="diapositivas">
                <?php while ($row = $diapo->fetch()) : ?>
                    <div class='diapo'>   
                        <div>
                            <form method='post'>
                                <input type="hidden" name="id" value="<?= $id_presentacio?>">
                                <input type="hidden" name="id_diapo" value="<?= $row['ID_Diapositiva'];?>">
                                <button type='submit' name="getInfoDiapo" class="button-diapo"><?= $row['titol']; ?></button>
                            </form>
                        </div>
                        
                        <div class='buttons-orden'>
                            <form method="post"  class='button-upDown'>
                                <input type="hidden" name="id_diapo" value="<?= $row['ID_Diapositiva'];?>">
                                    <button type="submit" name="ordenDiapoUp">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M182.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>                    </button>
                            </form>
                            <form method="post" class='button-upDown'>
                                <input type="hidden" name="id_diapo" value="<?= $row['ID_Diapositiva'];?>">
                                    <button type="submit" name="ordenDiapoDown">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M182.6 470.6c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128z"/></svg>                    </button>
                            </form>
                        </div>
                            
                    </div>
                <?php endwhile ?>  
            </div>
        </div>
        <div class="right">
            <form method="POST" id="formDiapo"  onsubmit="return validatePregunta();">
                <input type="hidden" name="id_presentacio" value="<?= $id_presentacio; ?>">
                <div class="preguntaSimple"><?php if ($infoDiapo === TRUE) {?>
                        <input type="text" name="titol" id="titol"  class="titolDiapoPregunta" maxlength ="25" value=' <?=$titolDiapo?>' readOnly >
                        <?php
                        echo '<textarea class="preguntaDiapo" name="pregunta" id="pregunta" maxlength ="65" readonly>' . htmlspecialchars($pregunta['pregunta']) . '</textarea>';
                        ?>
                        <div id="respuestas-container">
                        <?php
                        foreach ($respuestas as $respuesta) {
                            echo '<div class="respuesta-container">';
                            
                            echo '<input type="radio" name="respuesta_correcta" maxlength ="110" value="' . $respuesta['ID_respuesta'] . '" disabled';
                            if ($respuesta['correcta'] == 1) {
                                echo ' checked';
                            }
                            echo '>';
                            
                            echo '<input type="text" name="opcion[]" class="opcionDiapo" id="opcionDiapo" maxlength ="110" readonly value="' . htmlspecialchars($respuesta['texto']) . '">';
                            
                            echo '</div>';
                        }

                        echo '</div>';

                        ?>
                        </div>
                    <?php
                    }else {?>
                        <input type="text" name="titol" id="titol" class="titolDiapoPregunta" placeholder="Titulo diapositiva" maxlength="25" required/>
                        <textarea name="pregunta" id="pregunta" class="preguntaDiapo" maxlength ="65" placeholder="Escribe tu pregunta" required></textarea>
    
                        <div id="respuestas-container">
                        <div class="respuesta-container">
                        <input type="radio" name="respuesta_correcta" value="1" checked>
                        <input type="text" name="opcion[]" placeholder="Respuesta 1" class="opcionDiapo" id="opcionDiapo" maxlength ="110"  required>
                        </div>
    
                        <div class="respuesta-container">
                        <input type="radio" name="respuesta_correcta" value="2">
                        <input type="text" name="opcion[]" placeholder="Respuesta 2" class="opcionDiapo" id="opcionDiapo" maxlength ="110" required>
                        </div>
                        </div>
                       

                        <button type="button" class="anadirRespuesta" onclick="agregarRespuesta()">Agregar Respuesta</button>


                        </div>

                        <input type="submit" name="anadirDiapositiva" class="boton-crear" value="Añadir diapositiva">
                    <?php
                    } ?>                
                    
            </form>
        
            
            <div class='buttons-diapositiva'>
                <!-- Boton previsualizar diapositiva -->
                <form method="post" action="previsualitzarDiapositiva.php">
                    <input type="hidden" name="id_presentacio" value="<?= $id_presentacio; ?>">
                    <button type='submit' onclick="obtenerValores()" name='previsualizar_diapo'>
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const button = document.querySelector('.volver');
        const titulInput = document.getElementById('titol'); // Campo de entrada de título
        const previsualizar = document.querySelector('.buttons-diapositiva'); // Botón de previsualización
        
        // Oculta el botón de previsualización si el campo de entrada de título está vacío
        if (titulInput.value =='') {
            previsualizar.style.display = 'none';
        }

        // Muestra u oculta el botón de previsualización en función del contenido del campo de entrada del título
        titulInput.addEventListener('keyup', function(){
            if (titulInput.value == '') {
                previsualizar.style.display = 'none';
            }else{
                previsualizar.style.display = 'flex';
            }
        })

        document.querySelector("button[name='tipusTitol']").addEventListener("click", function() {            
            window.location.href = "CrearDiapositivesTitol.php?id=<?php echo $id_presentacio; ?>";
            
        });
        document.querySelector("button[name='tipusContingut']").addEventListener("click", function() {
            window.location.href = "CrearDiapositivesContingut.php?id=<?php echo $id_presentacio; ?>";
            
        });
        document.querySelector("button[name='tipusImatge']").addEventListener("click", function() {
            window.location.href = "CrearDiapositivesImatge.php?id=<?php echo $id_presentacio; ?>";
            
        });
        document.querySelector("button[name='tipusSeleccioSimple']").addEventListener("click", function() {
            window.location.href = "CrearDiapositivesPregunta.php?id=<?php echo $id_presentacio; ?>";
        });
        button.addEventListener('click', function (e) {
            window.location.href = "index.php";
        });

        function validateForm() {
            const titol = document.forms["formDiapoCont"]["titol"].value;
            let isValid = true;
            
            if (titol.trim() === "") {
                isValid = false;
                document.getElementById("titolError").innerText = "El campo 'Titol' no puede estar vacío";
            } else {
                document.getElementById("titolError").innerText = "";
            }
            
            return isValid;
        }

        function obtenerValores() {
            var titolDiapo = document.getElementById('titol').value;
            var pregunta = document.getElementById('pregunta').value;
            var resposta = document.querySelectorAll('#opcionDiapo');
            let limitR = resposta.length;
            
            for (let i = 0; i < limitR ; i++) {
                localStorage.setItem(('respuesta'+i) , resposta[i].value);
            };
            // Almacena los valores en localStorage para que estén disponibles en la nueva página
            localStorage.setItem('titolDiapo', titolDiapo);
            localStorage.setItem('pregunta', pregunta);
            localStorage.setItem('limitR', limitR);
        }


        function agregarRespuesta() {
    // Contador para dar nombres únicos a los campos
    if (document.querySelectorAll('input[type="radio"]').length <= 5) {
        var contador = document.querySelectorAll('input[type="radio"]').length + 1;

        // Crea un nuevo contenedor para la respuesta
        var respuestaContainer = document.createElement('div');
        respuestaContainer.classList.add('respuesta-container');

        // Crea un nuevo campo de tipo radio
        var inputRadio = document.createElement('input');
        inputRadio.type = 'radio';
        inputRadio.name = 'respuesta_correcta';
        inputRadio.value = contador;

        // Crea un nuevo campo de texto para la respuesta
        var inputTexto = document.createElement('input');
        inputTexto.type = 'text';
        inputTexto.id = 'opcionDiapo';
        inputTexto.name = 'opcion[]';  // Usa un array para almacenar las respuestas
        inputTexto.placeholder = 'Respuesta ' + contador;
        inputTexto.setAttribute('required', ''); // Corrección aquí
        inputTexto.setAttribute('maxlength','110');

        // Agrega la clase "opcionDiapo" a ambos campos
        inputTexto.classList.add('opcionDiapo');

        // Agrega los nuevos campos al contenedor principal
        respuestaContainer.appendChild(inputRadio);
        respuestaContainer.appendChild(inputTexto);

        // Agrega el botón de eliminar respuesta
        var botonEliminar = document.createElement('span');
        botonEliminar.innerHTML = '<svg class="eliminarRespuesta" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#000000}</style><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>';
        botonEliminar.onclick = function() {
            // Elimina el contenedor de respuesta al hacer clic en el botón
            respuestaContainer.remove();
        };
        respuestaContainer.appendChild(botonEliminar);

        // Agrega el contenedor al contenedor principal
        document.getElementById('respuestas-container').appendChild(respuestaContainer);
    }
}




    </script>
    <script src="controllers/Diapositives.js"></script>
    
</body>
</html>