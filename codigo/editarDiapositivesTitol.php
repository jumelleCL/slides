<?php

// Incluir los archivos necesarios
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");

// Verificar si se proporciona el parámetro "id" en la URL
if (isset($_GET["id"])) {
    $id_presentacio = intval($_GET["id"]);
    $titol = $dao->getTitolPorID($id_presentacio);
    $diapo = $dao->getDiapositives($id_presentacio);
    $url_unica = $dao->getURLPorID($id_presentacio);
    $presen = $dao->getPresentacions();
    $row = $presen->fetch();
    $estado = $dao->getEstadoPublicacion($id_presentacio);
} else {
    $titol = "Título no disponible";
}

$editDiapo = FALSE;
$id_diapo = '';
if (isset($_GET["id_diapo"])) {
    $id_diapo = $_GET["id_diapo"];
    if ($id_diapo != '') {
        $titolDiapo = $dao->getTitolDiapoPorID($id_diapo);
        $contingut = $dao->getContingutPorID($id_diapo);
        $editDiapo = TRUE;
    }
    
}else{
    $editDiapo = FALSE;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pantalla Editar Diapositivas Titol</title>
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <link rel="stylesheet" href="Styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <div id="message-container" class="mensaje-exito" style="display: none;"></div>
</head>
<body id="crearDiapositivasContingut">
    <?php
    if (isset($_GET['mensaje'])) {
        // Obtiene el mensaje codificado desde la URL
        $mensajeCodificado = $_GET['mensaje'];
    
        // Descifra el mensaje usando la función de decodificación
        $mensaje = base64_decode($mensajeCodificado);
    
        // Muestra el mensaje
        echo '<div id="mensaje-exito" class="mensaje-exito" style="display: none;">' . $mensaje . '</div>';
    }
    ?>
    <?php
    if (isset($_GET['feedEliminado'])) {
        $msjCod = $_GET['feedEliminado'];
        $msj = base64_decode($msjCod);
        echo '<div id="eliminadaDiapo" class="mensaje-exito" style="display: none;">' . $msj . '</div>';
    }
    ?>
    <div class="up">
        <div class="volver">
            <button> 
            <svg  class='volverButton' xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                Home
            </button>
        </div>
        <div class="presentacionV2">
            <div class="presentacion-guardada">
                <!-- Contenido de la presentación y botones -->
                <div class='buttons-editar'>
                    <p id="titulo-guardado" class="tituloGuardado"><?php echo $titol; ?> 
                    <form method='post'>
                        <input type="hidden" name="id_presentacion" value="<?= $id_presentacio; ?>">
                        <input type="hidden" name="from" value="Editar">
                        <button class='buttons' type="submit" name="previsualizar_presentacion">
                        <!-- Boton editar estilo de la presentacion -->    
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>                    
                        </button>
                    </form>
                </div>
                <div class="editarEstils">
                    <!-- Formulario para editar estilos de la presentación -->
                    <form method="post">
                        <button class="buttons-header editarEstilsPres" name="editarEstilsPres"> 
                        <!-- Boton editar estilo de la presentacion -->
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                            viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M512 256c0 .9 0 1.8 0 2.7c-.4 36.5-33.6 61.3-70.1 61.3H344c-26.5 0-48 21.5-48 48c0 3.4 .4 6.7 1 9.9c2.1 10.2 6.5 20 10.8 29.9c6.1 13.8 12.1 27.5 12.1 42c0 31.8-21.6 60.7-53.4 62c-3.5 .1-7 .2-10.6 .2C114.6 512 0 397.4 0 256S114.6 0 256 0S512 114.6 512 256zM128 288a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM288 96a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm96 96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                        </svg> Modificar estilos
                        </button>
                    </form>
                    
                    <div class="vertical-line"></div>
                    <form method="post" class="form-inline">
                        <input type="hidden" name="id_presentacion" value="<?= $id_presentacio; ?>">
                        <button class="buttons-header" type="submit" name="editarPres"> 
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                            </svg>
                        Editar presentación
                    </button>
                    
                    </form><div class="vertical-line"></div>
                    <!-- Boton publicar la presentación -->
                    <form method="post" class="form-inline">
                        <input type="hidden" name="id_presentacion" value="<?= $id_presentacio; ?>">
                        <button class='buttons-header' type="submit" name="publicar_presentacion"> 
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M352 224H305.5c-45 0-81.5 36.5-81.5 81.5c0 22.3 10.3 34.3 19.2 40.5c6.8 4.7 12.8 12 12.8 20.3c0 9.8-8 17.8-17.8 17.8h-2.5c-2.4 0-4.8-.4-7.1-1.4C210.8 374.8 128 333.4 128 240c0-79.5 64.5-144 144-144h80V34.7C352 15.5 367.5 0 386.7 0c8.6 0 16.8 3.2 23.2 8.9L548.1 133.3c7.6 6.8 11.9 16.5 11.9 26.7s-4.3 19.9-11.9 26.7l-139 125.1c-5.9 5.3-13.5 8.2-21.4 8.2H384c-17.7 0-32-14.3-32-32V224zM80 96c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16H400c8.8 0 16-7.2 16-16V384c0-17.7 14.3-32 32-32s32 14.3 32 32v48c0 44.2-35.8 80-80 80H80c-44.2 0-80-35.8-80-80V112C0 67.8 35.8 32 80 32h48c17.7 0 32 14.3 32 32s-14.3 32-32 32H80z" />
                            </svg><?php echo $estado == 1 ? 'Despublicar' : 'Publicar'; ?> presentación
                        </button>
                        
                    </form><div class="vertical-line"></div>
                    <!-- Boton para copiar url de la presentacion -->
                    <button class='buttons-header' data-url="<?= $url_unica; ?>" onclick="copiarURL(this)"> <svg
                            xmlns="http://www.w3.org/2000/svg" height="1em"
                            viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z" />
                        </svg>
                        copiar link
                    </button>
                </div>
                
            </div>
        </div>
        
    </div>

    <div class="down">
        <div class="left">
            <div class="nuevaDiapositiva">
                <!-- Botones para agregar diferentes tipos de diapositivas -->
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
                                        <button type='submit' name="editar_diapo" class="button-diapo"><?= $row['titol']; ?></button>
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
                                <form method = 'post'>  
                                    <input type="hidden" name="id_diapo" value="<?= $row['ID_Diapositiva'];?>">
                                    <input type="hidden" name="id" value="<?= $id_presentacio; ?>">
                                    <button type='submit' name='eliminarDiapo' class='eliminarDiapo'>
                                        <!-- Boton eliminar diapositiva -->
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                    </button>
                                </form>
                            
                    </div>
                <?php endwhile ?>  
            </div>
        </div>
        <div class="right">
            <form method="POST" id="formDiapo" onsubmit="return validateForm();">
                <!-- Campo oculto para enviar el ID -->
                <?php if ($editDiapo) {
                    echo "<input type='hidden' name='id_diapo' value='$id_diapo'>";}?>
                <input type="hidden" name="id_presentacio" value="<?= $id_presentacio; ?>">
                <span id="titolError" class="error"></span>
                <!-- Campo de entrada de título de diapositiva -->
                <input type="text" id="titol" name="titol" class="titolDiapo" placeholder="Titulo" maxlength="25" required <?php if ($editDiapo === TRUE) {
                   ?> value="<?= $titolDiapo ?>" <?php ;
                   } ?> >
                <!-- Botón para guardar la diapositiva -->
                <input type="submit" name="anadirEditarDiapositiva" class="boton-crear" <?php if ($editDiapo === TRUE) {
                    echo 'value="Guardar diapositiva" ';
                }else{
                    echo 'value="Añadir diapositiva" ';
                }
                ?> >
            </form>
            <div class='buttons-diapositiva'>
                <form method="post" action="previsualitzarDiapositiva.php"> 
                    <input type="hidden" name="id_presentacio" value="<?= $id_presentacio; ?>">
                    <input type="hidden" name="id_diapo" value="<?= $id_diapo; ?>">
                    <button type="submit" onclick="obtenerValores()" name='previsualizar_diapo'>
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>                    </button>
                </form>
                
                
                
            </div>
        </div>
    </div>

    <script>
        // Selección de elementos del DOM
        const button = document.querySelector('.volver');
        const buttonEstils = document.querySelector('.editarEstilsPres');
        const titulInput = document.getElementById('titol');
        const previsualizar = document.querySelector('.buttons-diapositiva');

        // Si el campo de título está vacío, oculta el elemento "previsualizar"
        if (titulInput.value =='') {
            previsualizar.style.display = 'none';
        }

        // Agrega un evento para detectar pulsaciones de teclas en el campo de título
        titulInput.addEventListener('keyup', function(){
            if (titulInput.value == '') {
                previsualizar.style.display = 'none';
            }else{
                previsualizar.style.display = 'flex';
            }
        })

        // Event listeners para botones
        document.querySelector("button[name='tipusTitol']").addEventListener("click", function() {            
            window.location.href = "editarDiapositivesTitol.php?id=<?php echo $id_presentacio; ?>";
            
        });
        document.querySelector("button[name='tipusContingut']").addEventListener("click", function() {
            window.location.href = "editarDiapositivesContingut.php?id=<?php echo $id_presentacio; ?>";
            
        });
        document.querySelector("button[name='tipusImatge']").addEventListener("click", function() {
            window.location.href = "editarDiapositivesImatge.php?id=<?php echo $id_presentacio; ?>";
            
        });
        document.querySelector("button[name='tipusSeleccioSimple']").addEventListener("click", function () {
            window.location.href = "editarDiapositivesPregunta.php?id=<?php echo $id_presentacio; ?>";

        });
        button.addEventListener('click', function (e) {
            window.location.href = "index.php";
        });

        buttonEstils.addEventListener('click', function (e) {
            e.preventDefault();
            const url = "seleccionarEstilos.php?id=<?php echo $id_presentacio; ?>&type=Titol&id_diapo=<?=$id_diapo?>";
            console.log(url);
            window.location.href = url;
        });

        // Función para obtener valores del campo de título y almacenarlos en el almacenamiento local
        function obtenerValores() {
            var titolDiapo = document.getElementById('titol').value;
            // Almacena los valores en localStorage para que estén disponibles en la nueva página
            localStorage.setItem('titolDiapo', titolDiapo);
        }

        // Función para copiar una URL al portapapeles
        function copiarURL(buttoncopy) {
        var url = buttoncopy.getAttribute('data-url');

        // Verifica si el URL es nulo
        if (url == "") {
            // Crear un mensaje de error y mostrarlo en el messageContainer
            var messageContainer = document.getElementById('message-container');
            messageContainer.textContent = 'No se puede copiar la URL ya que la presentación no esta publicada.';
            messageContainer.style.backgroundColor = 'red';
            messageContainer.style.display = 'block';

            // Ocultar el mensaje después de 3 segundos (3000 milisegundos)
            setTimeout(function() {
                messageContainer.style.display = 'none';
            }, 5000);

            return; // Salir de la función si el URL es nulo
        }

        // Construye una URL completa
        const urlCompleta = `/vistaPreviaClient.php?url=${url}`;

        // Crea un elemento de entrada oculto y copia la URL en él
        const input = document.createElement('input');
        input.style.position = 'fixed';
        input.style.opacity = 0;

        input.value = urlCompleta;

        document.body.appendChild(input);

        // Selecciona y copia el contenido del campo de entrada
        input.select();
        document.execCommand('copy');

        // Elimina el campo de entrada
        document.body.removeChild(input);

        // Crear un mensaje de éxito y mostrarlo en el messageContainer
        var messageContainer = document.getElementById('message-container');
        messageContainer.textContent = 'URL copiada correctamente';
        messageContainer.style.display = 'block';

        // Ocultar el mensaje después de 3 segundos (3000 milisegundos)
        setTimeout(function() {
            messageContainer.style.display = 'none';
        }, 3000);
    }

    function mostrarMensajeExito() {
            var mensajeExito = document.getElementById("mensaje-exito");
            mensajeExito.style.display = "block";
            mensajeExito.innerText = "Estilo cambiado exitosamente.";
            
            setTimeout(function() {
                mensajeExito.style.display = "none";
                window.location.href = "editarDiapositivesTitol.php?id=<?=$id_presentacio?>&id_diapo=<?=$id_diapo?>"
            }, 1000); // 3000 milisegundos = 3 segundos
        }
        <?php if (isset($_GET['mensaje'])) {
            ?> mostrarMensajeExito(); <?php
        } ?>

        function mostrarMensajeExitoEliminado() {
            var mensajeExito = document.getElementById("eliminadaDiapo");
            mensajeExito.style.display = "block";
            mensajeExito.style.position = 'fixed';
            
            setTimeout(function() {
                mensajeExito.style.display = "none";
                window.location.href = "editarDiapositivesTitol.php?id=<?=$id_presentacio?>&id_diapo=<?=$id_diapo?>"
            }, 1000); // 3000 milisegundos = 3 segundos
            
        }
        <?php if (isset($_GET['feedEliminado'])) {
            ?> mostrarMensajeExitoEliminado(); <?php
        } ?>
    </script>
    <script src="controllers/Diapositives.js"></script>
</body>
</html>