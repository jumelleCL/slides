<?php

// Incluye archivos de funciones necesarios
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");
// Verifica si se ha proporcionado un parámetro "id" en la URL
if (isset($_GET["id"])) {
    $id_presentacio = $_GET["id"];
    $from = isset($_GET["from"]) ? $_GET["from"] : "Página desconocida"; // Obtén el valor de "from"
    $titol = $dao->getTitolPorID($id_presentacio);
    $desc = $dao->getDescPorID($id_presentacio);
    $diapositivas = $dao->getDiapositivesVista($id_presentacio);
    $url_unica = $dao->getURLPorID($id_presentacio);

} else {
    // Si no se proporciona "id" en la URL, muestra un mensaje de error
    $titol = "Error, no se encuentra la presentacion";
    $desc = "";
    $diapositivas = array();
}
$slideIndex = 0;
$id_diapo = '';
if (isset($_GET["id_diapo"])) {
    $id_diapo = $_GET["id_diapo"];
    // Buscar el índice de la diapositiva seleccionada en el array de diapositivas
    $slideIndex = 0; // Valor predeterminado (primera diapositiva)
    foreach ($diapositivas as $index => $diapositiva) {
        if ($diapositiva['ID_Diapositiva'] == $id_diapo) {
            $slideIndex = $index;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Vista Previa de Diapositiva Cliente</title>
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <link rel="stylesheet" href="Styles.css">
</head>
<body class="vista">
    <div class="titulo">
    <a href="<?php
        // Genera un enlace que apunta a la vista previa de la presentación
        echo 'vistaPreviaClient.php?url=' . $url_unica;
    ?>"><svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/></svg></a>
    </div>
    <div class="preview">
    <?php if (empty($diapositivas)): ?>
        <!-- Si no hay diapositivas en la presentación, muestra un mensaje de aviso -->
        <div class="aviso">Esta presentación no tiene diapositivas.</div>
    <?php else: ?>
    <div class="diapositiva-preview-<?php echo $estiloPresentacion;?>">
        <h1></h1>
        <div class="contenido">
            <p></p>
            <h2></h2>
            <div class="respuestas"></div>
            <img id="imagen" src="" alt="imagen" style="width: 250px; height: 250px; margin-right: 50px">
        </div>
    </div>
    <div class="controles">
        <button id="anterior"><svg class="rotate" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ffffff}</style><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg></button>
        <button id="siguiente"><svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg></button>
    </div>
    </div>
    <?php endif; ?>
    <script>
        <?php if (!empty($diapositivas)): ?>
        // Comprueba si hay diapositivas disponibles para mostrar
        var diapositivas = <?php echo json_encode($diapositivas); ?>;
        var currentSlide = 0; // Inicializa el índice de la diapositiva actual
        var totalSlides = diapositivas.length; // Obtiene el número total de diapositivas
        var preguntasRespondidas = [];
        var valorRespuestas = [];

        var anteriorButton = document.getElementById("anterior"); // Obtiene el botón de diapositiva anterior
        var siguienteButton = document.getElementById("siguiente"); // Obtiene el botón de diapositiva siguiente

        function mostrarDiapositiva(slideIndex, direccion) {

            // Función para mostrar una diapositiva en función del índice
            var diapositiva = diapositivas[slideIndex];
            var direccionDiapositiva = direccion;
            document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> h1').textContent = diapositiva.titol;
            document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> p').textContent = diapositiva.contingut;
            document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> h2').textContent = diapositiva.pregunta;
            document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> img').src = diapositiva.imatge;
            var tituloElement = document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> h1');
            var contenidoElement = document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> p');
            var imatgeElement = document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> img');
            var img = document.getElementById("imagen");
            const cont = document.querySelector('.contenido');
            const respuestasForm = document.querySelector('.respuestas');
            var radioButtons = document.querySelectorAll('.respuestas');
            var numeroDiapositiva = 1;


            respuestasForm.addEventListener('change', function(event) {
                var pregunta_id = diapositiva.pregunta_id;
                var respuesta_value = event.target.value;

                var preguntaYaRespondida = preguntasRespondidas.find(item => item.pregunta_id === pregunta_id);

                if (respuesta_value !== undefined) {
                    // Si la pregunta ha sido respondida, actualiza la propiedad respondida a true
                    if (preguntaYaRespondida) {
                        if (numeroDiapositiva == currentSlide) {
                            if (preguntaYaRespondida.respondida !== undefined) {
                                preguntaYaRespondida.respuesta = respuesta_value;
                                preguntaYaRespondida.respondida = true;
                            }
                        }
                        
                    } 
                }

                siguienteButton.disabled =  totalSlides === -1;
            });
            
            if (diapositiva.contingut === null) {
                // Si el contenido es nulo, ocultar el contenido
                contenidoElement.style.display = 'none';
                imatgeElement.style.display='none';
                contenidoElement.style.padding = '70px';
                contenidoElement.style.width = null;
                cont.style.display = 'block';
                respuestasForm.innerHTML = '';


                if (diapositiva.es_pregunta === true) {
                    tituloElement.style.fontSize = "40px";
                    tituloElement.style.marginTop = "65px";
                    tituloElement.style.marginBottom = "50px";

                    preguntaRespuesta = preguntasRespondidas.find(item => item.pregunta_id === diapositiva.pregunta_id);
                    numeroDiapositiva = currentSlide + 1;

                    if ((!preguntaRespuesta ) || (preguntaRespuesta.id_diapositiva === diapositiva.ID_Diapositiva)) {
                        
                        if (!preguntaRespuesta) {
                            preguntasRespondidas.push({ pregunta_id: diapositiva.pregunta_id, id_diapositiva: diapositiva.ID_Diapositiva, respondida: false, respuesta: -1 });
                        }
                        
                        // Si es una pregunta, muestra el título de la pregunta y las respuestas
                        document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> h1').textContent = diapositiva.titol;

                            if (diapositiva.respuestas && diapositiva.respuestas.length > 0) {
                                var respuestaContainer = document.createElement('div');
                                respuestaContainer.classList.add('.respuesta-container-preview')
                                diapositiva.respuestas.forEach(function(respuesta, index) {

                                    respuestaContainer.innerHTML += '<label class="respuestaVacia-preview<?php echo $estiloPresentacion;?>"><input type="radio" name="respuesta" value="' + index + '"> ' + respuesta.respuesta_texto + '</label><br>';
                                    respuestasForm.appendChild(respuestaContainer);
                                });
                            } else {
                                document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> p').textContent = 'No hay respuestas disponibles';
                            } 

                    } else if ((preguntaRespuesta.respondida === false) && (preguntaRespuesta.id_diapositiva !== diapositiva.ID_Diapositiva)) {

                        currentSlide = slideIndex;
                        
                        if (direccionDiapositiva === 'front') {

                                mostrarDiapositiva(currentSlide + 1, 'front')
                             
                        } else {
                            mostrarDiapositiva(currentSlide - 1, 'back')
                        }

                        return;
                      
                    } else if ((preguntaRespuesta.respondida === true) && (preguntaRespuesta.id_diapositiva !== diapositiva.ID_Diapositiva) && (diapositiva.pregunta_id === preguntaRespuesta.pregunta_id)) {
                        // Si es una pregunta, muestra el título de la pregunta y las respuestas

                        document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> h1').textContent = diapositiva.titol;

                            if (diapositiva.respuestas && diapositiva.respuestas.length > 0) {
                                var respuestaContainer = document.createElement('div');
                                respuestaContainer.classList.add('.respuesta-container-preview')
                                diapositiva.respuestas.forEach(function(respuesta, index) {

                                    respuestaContainer.innerHTML += '<label class="respuesta-preview ' + (respuesta.correcta === 1 ? 'respuesta-correcta' : '') + '"><input type="radio" name="respuesta" value="' + index + '" ' + ((index == preguntaRespuesta.respuesta) ? 'checked' : '') + ' disabled> ' + respuesta.respuesta_texto + '</label><br>';
                                    respuestasForm.appendChild(respuestaContainer);
                                });
                            } else {
                                document.querySelector('.diapositiva-preview-<?php echo $estiloPresentacion;?> p').textContent = 'No hay respuestas disponibles';
                            }  

                            
                    }
                } else {
                    tituloElement.style.fontSize = "4rem";
                    tituloElement.style.marginTop = "200px";
                }

            } else {

                if(diapositiva.imatge != null){       
                    // Si hay una imagen en la diapositiva, ajusta el diseño   
                    tituloElement.style.fontSize = "40px";
                    tituloElement.style.marginTop = "65px";

                    cont.style.display = 'flex';
                    cont.style.flexDirection= 'row';
                    cont.style.justifyContent= 'space-around';


                    tituloElement.textContent = diapositiva.titol;
                    contenidoElement.textContent = diapositiva.contingut;
                    contenidoElement.style.display = 'flex';
                    contenidoElement.style.width = '500px';
                    contenidoElement.style.padding = '10px';
                    contenidoElement.style.marginLeft = '35px'

                    imatgeElement.src = diapositiva.imatge;
                    imatgeElement.style.display = 'flex';
                    respuestasForm.innerHTML = '';
                }else{
                    // Si no hay una imagen, muestra solo el contenid
                    contenidoElement.style.display = 'flex';
                    imatgeElement.style.display = 'none';
                    respuestasForm.innerHTML = '';

                    tituloElement.style.fontSize = "40px";
                    tituloElement.style.marginTop = "65px";
                }
            }
            currentSlide = slideIndex;

            if (currentSlide < totalSlides - 1) {
                var siguienteDiapositiva = preguntasRespondidas.find(item => item.pregunta_id === diapositivas[currentSlide + 1].pregunta_id);
            }

            // Habilitar o deshabilitar botones según la posición de la diapositiva
            if (
                currentSlide === totalSlides - 2 && // La diapositiva actual es la penúltima
                diapositivas[currentSlide + 1].es_pregunta === true &&
                siguienteDiapositiva.respondida === false
            ) {
                siguienteButton.disabled = true;
                
            } else if (currentSlide === totalSlides - 1) {
                
                siguienteButton.disabled = true;
            } else {
                siguienteButton.disabled = false;
            }

            anteriorButton.disabled = currentSlide === 1;
            
        }


        document.getElementById("anterior").addEventListener("click", function() {
            // Asocia una función a la acción de hacer clic en el botón anterior
            mostrarDiapositiva(currentSlide - 1, 'back'); // Muestra la diapositiva anterior
        });

        document.getElementById("siguiente").addEventListener("click", function() {
            // Asocia una función a la acción de hacer clic en el botón siguiente
            mostrarDiapositiva(currentSlide + 1, 'front'); // Muestra la diapositiva siguiente
        });

        // Mostrar la primera diapositiva al cargar la página
        mostrarDiapositiva(1);
        <?php endif; ?>
    </script>
</body>
</html>