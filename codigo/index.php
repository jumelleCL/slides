<?php

// Incluye archivos de funciones necesarios
include_once("controllers/baseDatos.php");
require_once 'controllers/DAO.php';

// Obtiene las presentaciones desde la base de datos
$presen = $dao->getPresentacions();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Pantalla Home</title>
    <link rel="stylesheet" href="Styles.css">
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <script>
    // Evento que se ejecuta cuando el contenido HTML está completamente cargado
    document.addEventListener("DOMContentLoaded", function() {
        var messageContainer = document.getElementById("message-container");

        // Verifica si existe un mensaje y lo oculta después de 3 segundos (3000 milisegundos)
        if (messageContainer) {
            setTimeout(function() {
                messageContainer.style.display = "none";
            }, 3000); // 3000 ms = 3 segundos
        }
    });
</script>
<div id="message-container" class="mensaje-exito" style="display: none;"></div>
</head>
<body>
    <h1 class="titolHome">Slides</h1>
    <hr class="line">

    <div class="overlay" id="overlay"></div>
    <div id="confirmacion-eliminar" class="confirm-box">
        <p>¿Estás seguro de que deseas eliminar esta presentación?</p>
        <button id="confirmar-eliminar">Confirmar</button>
        <button id="cancelar-eliminar">Cancelar</button>
    </div>
    <div class="container">
        <!-- Icono SVG para "Crear una nueva presentación" -->
        <a href="CrearPresentacio.php" class="button"><svg class='iconoCrear' xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>.iconoCrear{fill:#ffffff}</style><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg> Crear una nueva presentación</a>
    </div>
    <div class="presentacionsBD">
        <?php while ($row = $presen->fetch()) : ?>
                    
            <table class='pres'>   
                <tbody class='bodyTable <?php echo $row['publicada'] ? 'publicada' : 'no-publicada'; ?>'>
                        <tr>
                            <td class='titolPres'><?= $row['titol']; ?></td>
                        </tr>
                        <tr class="button-row">    
                            <td>
                                <form method='post'class='form-inline'>
                                    <input type="hidden" name="id_presentacion" value="<?= $row['ID_Presentacio']; ?>">
                                    <!-- Botón para editar la presentación -->
                                    <!-- Icono SVG para "Editar presentación" -->
                                    <button class='buttons tooltip bottom' type="submit" name="editar_presentacion"><svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 512 512"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                                        <span class="tiptext">Editar presentación</span>
                                    </button>
                                </form>
                                <form method="post" class="form-inline" onsubmit="return confirmarEliminacion(this);">
                                    <input type="hidden" name="id_presentacion" value="<?= $row['ID_Presentacio']; ?>">
                                    <input type="hidden" name="form" value="eliminar">
                                    <!-- Botón para eliminar la presentación -->
                                    <!-- Icono SVG para "Eliminar presentación" -->
                                    <button class='buttons tooltip bottom' type="submit" name="eliminar_presentacion"><svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 448 512"><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                        <span class="tiptext">Eliminar presentación</span>
                                    </button>
                                </form>
                                <!-- Botón para clonar la presentación -->
                                <button class='buttons tooltip bottom'><svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 512 512"><path d="M288 448H64V224h64V160H64c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V384H288v64zm-64-96H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H224c-35.3 0-64 28.7-64 64V288c0 35.3 28.7 64 64 64z"/></svg>
                                    <span class="tiptext">Clonar presentación</span>
                                </button>
                                <form method='post' class="form-inline">
                                    <input type="hidden" name="id_presentacion" value="<?= $row['ID_Presentacio']; ?>">
                                    <input type="hidden" name="from" value="Home">
                                    <!-- Botón para previsualizar la presentación -->
                                    <!-- Icono SVG para "Previsualizar presentación" -->
                                    <button class='buttons tooltip bottom' type="submit" name="previsualizar_presentacion"><svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                                        <span class="tiptext">Previsualizar presentación</span>
                                    </button>
                                </form>
                                <!-- Boton publicar -->
                                <form method="post" class="form-inline">
                                    <input type="hidden" name="id_presentacion" value="<?= $row['ID_Presentacio']; ?>">
                                    <button class='buttons tooltip bottom' type="submit" name="publicar_presentacion">
                                    <!-- Icono SVG para "Publicar/Despublicar presentación" -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M352 224H305.5c-45 0-81.5 36.5-81.5 81.5c0 22.3 10.3 34.3 19.2 40.5c6.8 4.7 12.8 12 12.8 20.3c0 9.8-8 17.8-17.8 17.8h-2.5c-2.4 0-4.8-.4-7.1-1.4C210.8 374.8 128 333.4 128 240c0-79.5 64.5-144 144-144h80V34.7C352 15.5 367.5 0 386.7 0c8.6 0 16.8 3.2 23.2 8.9L548.1 133.3c7.6 6.8 11.9 16.5 11.9 26.7s-4.3 19.9-11.9 26.7l-139 125.1c-5.9 5.3-13.5 8.2-21.4 8.2H384c-17.7 0-32-14.3-32-32V224zM80 96c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16H400c8.8 0 16-7.2 16-16V384c0-17.7 14.3-32 32-32s32 14.3 32 32v48c0 44.2-35.8 80-80 80H80c-44.2 0-80-35.8-80-80V112C0 67.8 35.8 32 80 32h48c17.7 0 32 14.3 32 32s-14.3 32-32 32H80z"/></svg>
                                        <span class="tiptext"><?php echo $row['publicada'] ? 'Despublicar presentación' : 'Publicar presentación'; ?></span>
                                    </button>
                                </form>
                                <!-- Boton para copiar url de la presentacion -->
                                <!-- Icono SVG para "Copiar URL" -->
                                <button class='button-copy tooltip bottom' data-url="<?= $row['url_unica']; ?>" onclick="copiarURL(this)"><svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
                                    <span class="tiptext">Copiar Link</span>
                                </button>

                            </td>
                        </tr>
                    
                </tbody>
            </table>
         <?php endwhile ?>
    </div>
    <div>    <p class="description">Este sitio web te permite crear y gestionar presentaciones de diapositivas de forma sencilla y eficiente. ¡Comienza a crear tu presentación ahora!</p>
</div>
<script>
    // Función para mostrar una confirmación antes de eliminar una presentación 
    function confirmarEliminacion(form) {
        document.getElementById('overlay').style.display = 'flex';
        document.getElementById('confirmacion-eliminar').style.display = 'block';
        
        // Al hacer clic en "Confirmar", el formulario se enviará
        document.getElementById('confirmar-eliminar').onclick = function() {
            form.submit();
        };
        
        // Al hacer clic en "Cancelar", se ocultará la caja de confirmación
        document.getElementById('cancelar-eliminar').onclick = function() {
            document.getElementById('confirmacion-eliminar').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        };
        
        // Evita que el formulario se envíe directamente en este punto
        return false;
    }

    // Función para copiar la URL de la presentación al portapapeles
    function copiarURL(buttoncopy) {
        var url = buttoncopy.getAttribute('data-url');

        // Verifica si el URL es nulo
        if (url == "") {
            // Crear un mensaje de error y mostrarlo en el messageContainer
            var messageContainer = document.getElementById('message-container');
            messageContainer.textContent = 'No se puede copiar la URL ya que la presentación no esta publicada.';
            messageContainer.style.backgroundColor = 'red';
            messageContainer.style.position = 'fixed';
            messageContainer.style.display = 'block';

            // Ocultar el mensaje después de 3 segundos (3000 milisegundos)
            setTimeout(function() {
                messageContainer.style.display = 'none';
            }, 5000);

            return; // Salir de la función si el URL es nulo
        }

        const urlCompleta = `/vistaPreviaClient.php?url=${url}`;

        const input = document.createElement('input');
        input.style.position = 'fixed';
        input.style.opacity = 0;

        input.value = urlCompleta;

        document.body.appendChild(input);

        input.select();
        document.execCommand('copy');

        document.body.removeChild(input);

        // Crear un mensaje de éxito y mostrarlo en el messageContainer
        var messageContainer = document.getElementById('message-container');
        messageContainer.textContent = 'URL copiada correctamente';
        messageContainer.style.position = 'fixed';
        messageContainer.style.display = 'block';

        // Ocultar el mensaje después de 3 segundos (3000 milisegundos)
        setTimeout(function() {
            messageContainer.style.display = 'none';
        }, 3000);
    }

</script>

</body>


</html>