<?php

// Incluye archivos de funciones necesarios
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");

// Obtener el valor de $id_presentacio de PHP
$id_presentacio = isset($_GET["id"]) ? $_GET["id"] : "";

if (isset($_GET["id"])) {
    // Si se proporciona un "id" en la URL, obtén el estilo de presentación desde la base de datos
    $estiloPresentacion = $dao->getEstiloPresentacion($id_presentacio);
} else {
    // Si no se proporciona "id" en la URL, muestra un mensaje de error
    $titol = "Error, no se encuentra la presentacion";
}
?>

<!DOCTYPE html>
<html lang="es" class="preview">
<head>
    <title>Previsualización de la Diapositiva</title>
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <link rel="stylesheet" href="Styles.css">
</head>
<body class="vista">
    <div class="titulo">
        <button class="boton-salir" id="volverButton"><svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/></svg></button>
    </div>
    <div class="aviso">
        <p id="aviso" style="color: red;"></p>
    </div>
    <div class="preview">
        <!-- Muestra la previsualización de la diapositiva con el estilo obtenido -->
        <div class="diapositiva-preview-<?php echo $estiloPresentacion;?>">
            <h1 id="tituloPrevisualizado"></h1>
            <div class="contenido">
                <h2 id="titulPregunta"></h2>
                <form class="respuestas" id="respuestas"></form>
                <p id="contenidoPrevisualizado"></p>
                <img id="imatgePrevisualizado" src="" alt="imagen" style="display: none;width: 250px; height: 250px; margin-right: 50px">
            </div>
            
        </div>
    </div>

    
    <script>
        // Obtiene los valores almacenados en localStorage
        var titolDiapo = localStorage.getItem('titolDiapo');
        var contingut = localStorage.getItem('contingut');
        var imatge = localStorage.getItem('rutaImg');
        var pregunta = localStorage.getItem('pregunta');
        var respuesta = localStorage.getItem('limitR');
        
        
        let preguntaElement = document.getElementById('titulPregunta');
        let respostaElement = document.getElementById('respuestas');
        let contenidoElement = document.getElementById('contenidoPrevisualizado');
        let tituloElement= document.getElementById('tituloPrevisualizado');
        let imatgeElement= document.getElementById('imatgePrevisualizado');
        let cont = document.querySelector('.contenido');

        // Variable para el mensaje de aviso
        var aviso = '';

        // Comprueba si no hay título
        if (!titolDiapo) {
            // Oculta el contenido
            tituloElement.style.display='none';
            cont.style.display = 'none';
            // Actualiza el mensaje de aviso
            aviso = 'La diapositiva no tiene contenido.';
        }else{
            if (!contingut) {
                if(!pregunta){
                    cont.style.display = 'flex';
                    contenidoElement.style.display =  'none';
                    // Muestra el título si no hay contenido
                    tituloElement.textContent = titolDiapo;
                    tituloElement.style.margin='200px';
                    tituloElement.style.fontSize = "4rem";
                    cont.style.display='none';
                    preguntaElement.style.display = 'none';
                    respostaElement.style.display = 'none';
                }else{
                    contenidoElement.style.display = 'none';
                    tituloElement.textContent = titolDiapo;

                    tituloElement.style.fontSize = "40px";
                    tituloElement.style.marginTop = "65px";
                    tituloElement.style.marginBottom = "50px";

                    
                    preguntaElement.textContent = pregunta;
                    if (respuesta != '') {
                        for (let i = 0; i < respuesta ; i++) {
                            let elemR =  localStorage.getItem('respuesta'+i);
                            
                            var respuestaContainer = document.createElement('div');
                            respuestaContainer.classList.add('.respuesta-container-preview');
                            respuestaContainer.innerHTML += '<label><input type="radio" name="respuesta" value="' + i + '"> ' + elemR + '</label><br>';
                            respostaElement.appendChild(respuestaContainer);
                        }
                    }
                }
                
            }else{
                if(!imatge){
                // Muestra los valores en la página
                    tituloElement.textContent = titolDiapo;
                    contenidoElement.textContent = contingut;
                    cont.style.display = 'flex';
                    imatgeElement.style.display = 'none';
                    document.getElementById('aviso').textContent = aviso;
                    
                    preguntaElement.style.display = 'none';
                    respostaElement.style.display = 'none';
                }else{
                    // Muestra título, contenido e imagen
                    tituloElement.textContent = titolDiapo;
                    contenidoElement.textContent = contingut;
                    imatgeElement.src = imatge;

                    cont.style.display = 'flex';
                    cont.style.flexDirection= 'row';
                    cont.style.justifyContent= 'space-around';

                    contenidoElement.style.display = 'flex';
                    contenidoElement.style.width = '500px';
                    contenidoElement.style.padding = '10px'

                    imatgeElement.style.display = 'flex';

                    
                    preguntaElement.style.display = 'none';
                    respostaElement.style.display = 'none';

                    document.getElementById('aviso').textContent = aviso;
                }
            }
        }   

        // Elimina los datos almacenados en localStorage
        localStorage.removeItem('rutaImg');
        localStorage.removeItem('titolDiapo');
        localStorage.removeItem('contingut');
        localStorage.removeItem('pregunta');
        localStorage.removeItem('respuesta');
        localStorage.removeItem('limitR');

        // Agrega un evento de clic al botón de volver para regresar a la página anterior
        document.getElementById("volverButton").addEventListener("click", function() {
        // Redirige a la página anterior
        window.history.back(); // Esto redirige a la página anterior en el historial del navegador
    });
    </script>
</body>
</html>
