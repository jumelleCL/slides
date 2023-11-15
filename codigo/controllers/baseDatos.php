<?php

// Carga la configuración de la aplicación desde 'config.php'.
$config = require_once 'config.php';

// Incluye las clases 'Connection' y 'DAO' necesarias.
require_once 'Connection.php';
require_once 'DAO.php';

// Crea una instancia de la clase 'DAO' utilizando una conexión basada en la configuración.
$dao = new DAO(Connection::getConnection($config['db']));

// Manejo de formularios:

// Comprueba si se envió un formulario para agregar una presentación:
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["anadirPresentacio"])) {
    // Obtiene los datos del formulario (título, descripción, estilo, contraseña).
    $titol = $_POST["titol"];
    $descripcio = $_POST["descripcio"];
    $estilPresentacio = 'fondoBlanco';
    $password = $_POST["password"];

    // Realiza validaciones de datos y crea un hash de la contraseña.
    if ($password === "") {
        $password = null;
    } else {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    // Inserta la presentación en la base de datos y redirige al usuario.
    if ($titol != '' && $descripcio != '') {
        // Insertar los datos en la base de datos y obtener el ID generado
        $dao->setPresentacions($titol, $descripcio, $estilPresentacio, $hashPassword);

        // Obtener el ID generado automáticamente
        $lastInsertId = $dao->getLastInsertId();

        // Obtén el mensaje que deseas pasar
        $mensaje = "Presentación creada correctamente";

        // Codifica el mensaje usando una función de cifrado
        $mensajeCodificado = base64_encode($mensaje);

        // Redirigir a CrearDiapositives.php con el ID de la presentación como parámetro en la URL
        header("Location: CrearDiapositivesTitol.php?id=" . $lastInsertId . "&mensaje=" . $mensajeCodificado);
        $_POST['titol'] = null;
        $_POST['descripcio'] = null;
        exit();
    }else{
        // Redirigir de nuevo a CrearPresentacio.php
        header("Location: CrearPresentacio.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cambiarPresentacion"])) {
    // Obtener los datos del formulario
    $titol = $_POST["titol"];
    $descripcio = $_POST["descripcio"];
    $id = $_POST['id_presentacio'];
    if ($titol != '' && $descripcio != '') {
            // Insertar los datos en la base de datos y obtener el ID generado
        $dao->editarPresentacio($titol, $descripcio,$id);

        header("Location: editarDiapositivesTitol.php?id=" . $id);
        $_POST['titol'] = null;
        $_POST['descripcio'] = null;
    }else{
        header("Location: editarDiapositivesTitol.php?id=".$id);
    }
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["anadirDiapositiva"])) {
    if (isset($_FILES['imatge'])) {
        // Obtener los datos del formulario
        $titol = $_POST["titol"];
        $contingut = $_POST["contingut"];
        $id_presentacio = $_POST["id_presentacio"];

        if(strlen($titol) > 25){
            // Redirigir de nuevo a CrearDiapositives.php
            header("Location: CrearDiapositivesContingut.php?id=" . $id_presentacio);
        } elseif(strlen($contingut) > 640){
            // Redirigir de nuevo a CrearDiapositives.php
            header("Location: CrearDiapositivesContingut.php?id=" . $id_presentacio);
        } else{
        // Sube la imagen al servidor si existe y no hubo errores:
        if ($_FILES["imatge"]["error"] == UPLOAD_ERR_OK) { 
            $folderLocation = "uploaded";
            if (!file_exists($folderLocation)) { 
                // Crea un directorio 'uploaded' si no existe:
                mkdir($folderLocation);
                if(!file_exists($folderLocation)){
                    // Intenta crear el directorio con permisos de superusuario si falla:
                    $command = "sudo mkdir $folderLocation";
                    exec($command, $output, $returnCode);
                }
            }
            move_uploaded_file($_FILES["imatge"]["tmp_name"], "$folderLocation/" . basename($_FILES["imatge"]["name"])); 
            
            $imatge = $folderLocation ."/".basename($_FILES["imatge"]["name"]);
        }
        // Insertar los datos en la base de datos
        $dao->setDiapositivesImatge($titol, $contingut, $imatge, $id_presentacio); 
                
        // Redirigir de nuevo a CrearDiapositives.php
        header("Location: CrearDiapositivesImatge.php?id=" . $id_presentacio); 
        }   
    }else if (isset($_POST['contingut']) && (isset($_FILES['imatge']) == FALSE)) {

        // Obtener los datos del formulario
        $titol = $_POST["titol"];
        $contingut = $_POST["contingut"];
        $id_presentacio = $_POST["id_presentacio"];
        // Insertar los datos en la base de datos
        if(strlen($titol) > 25){
            // Redirigir de nuevo a CrearDiapositives.php
            header("Location: CrearDiapositivesContingut.php?id=" . $id_presentacio);
        } elseif(strlen($contingut) > 640){
            // Redirigir de nuevo a CrearDiapositives.php
            header("Location: CrearDiapositivesContingut.php?id=" . $id_presentacio);
        } else{
            // Insertar los datos en la base de datos
            $dao->setDiapositives($titol, $contingut, $id_presentacio); 
                    
            // Redirigir de nuevo a CrearDiapositives.php
            header("Location: CrearDiapositivesContingut.php?id=" . $id_presentacio);
        }
     }else if (isset($_POST['pregunta']) && isset($_POST['opcion']) && isset($_POST['respuesta_correcta'])) {
        $id_presentacio = $_POST["id_presentacio"];
        $titol = $_POST["titol"];
        $pregunta = $_POST["pregunta"];

        $dao->setPregunta($pregunta);
        $id_pregunta = $dao->getLastInsertId();
        $dao->setDiapositivesTitol($titol, $id_presentacio, $id_pregunta);
        $dao->setDiapositivesTitol($titol, $id_presentacio, $id_pregunta);
        $id_diapo = $dao->getLastInsertId();
        
        $respuestas = $_POST['opcion'];
        $respuesta_correcta_index = $_POST['respuesta_correcta'] - 1;


        foreach ($respuestas as $numeroRespuesta => $respuestaTexto) {
            $correcta = ($numeroRespuesta == $respuesta_correcta_index) ? 1 : 0;

            $dao->setRespuesta($id_pregunta, $respuestaTexto, $correcta);
            
        }

        header("Location: CrearDiapositivesPregunta.php?id=$id_presentacio&mensaje=Diapositiva creada con éxito");

        
     }else {
        
        $titol = $_POST["titol"];
        $id_presentacio = $_POST["id_presentacio"];

        if(strlen($titol) > 25){
            // Redirigir de nuevo a CrearDiapositives.php
            header("Location: CrearDiapositivesTitol.php?id=" . $id_presentacio);
        }else{
            // Insertar los datos en la base de datos
            $dao->setDiapositivesTitol($titol, $id_presentacio); 
                    
            // Redirigir de nuevo a CrearDiapositives.php
            header("Location: CrearDiapositivesTitol.php?id=" . $id_presentacio);
        }
     }   
    exit();              
}

// Comprueba si se envió un formulario para agregar o editar una diapositiva:
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["anadirEditarDiapositiva"])) {
        // Comprueba si el formulario contiene contenido (texto) para la diapositiva.
        if (isset($_POST['contingut'])) {
            // Comprueba si se adjuntó un archivo de imagen y tiene un tamaño mayor que cero:
            if (isset($_FILES['imatge']) && ($_FILES['imatge']['size']>0)) {
                // Obtiene los datos del formulario (título, contenido, ID de presentación).
                $titol = $_POST["titol"];
                $contingut = $_POST["contingut"];
                $id_presentacio = $_POST["id_presentacio"];
                if(strlen($titol) > 25){
                    // Redirigir de nuevo a CrearDiapositives.php
                    header("Location: editarDiapositivesContingut.php?id=" . $id_presentacio);
                } elseif(strlen($contingut) > 640){
                    // Redirigir de nuevo a CrearDiapositives.php
                    header("Location: editarDiapositivesContingut.php?id=" . $id_presentacio);
                } else{
                // Sube la imagen al servidor si existe y no hubo errores:
                if ($_FILES["imatge"]["error"] == UPLOAD_ERR_OK) { 
                    $folderLocation = "uploaded";
                    if (!file_exists($folderLocation)) { 
                        // Crea un directorio 'uploaded' si no existe:
                        mkdir($folderLocation);
                        if(!file_exists($folderLocation)){
                            // Intenta crear el directorio con permisos de superusuario si falla:
                            $command = "sudo mkdir $folderLocation";
                            exec($command, $output, $returnCode);
                        }
                    }
                    // Mueve el archivo de imagen a la ubicación del servidor:
                    move_uploaded_file($_FILES["imatge"]["tmp_name"], "$folderLocation/" . basename($_FILES["imatge"]["name"])); 
                    
                    $imatge = $folderLocation ."/".basename($_FILES["imatge"]["name"]);
                }
                if (isset($_POST['id_diapo'])) {  
                    // Comprueba si se proporciona un ID de diapositiva existente:
                    $editDiapo = $_POST['id_diapo'];
                    // Realiza una operación de edición en la base de datos para una diapositiva con imagen.
                    $dao->alterDiapositivesImatge($titol, $contingut, $imatge, $editDiapo);
                    header("Location: editarDiapositivesImatge.php?id=" . $id_presentacio);
                    
                }else {
                        // Insertar los datos en la base de datos
                    $dao->setDiapositivesImatge($titol, $contingut, $imatge,$id_presentacio); 
                        
                    // Redirigir de nuevo a CrearDiapositives.php
                    header("Location: editarDiapositivesImatge.php?id=" . $id_presentacio);
                }
            }
            }else{
            // Obtener los datos del formulario
            $titol = $_POST["titol"];
            $contingut = $_POST["contingut"];
            $id_presentacio = $_POST["id_presentacio"];
            if(strlen($titol) > 25){
                // Redirigir de nuevo a CrearDiapositives.php
                header("Location: editarDiapositivesContingut.php?id=" . $id_presentacio);
            } elseif(strlen($contingut) > 640){
                // Redirigir de nuevo a CrearDiapositives.php
                header("Location: editarDiapositivesContingut.php?id=" . $id_presentacio);
            } else{
            if (isset($_POST['id_diapo'])) {  
                $editDiapo = $_POST['id_diapo'];
                $dao->alterDiapositives($titol, $contingut, $editDiapo);
                header("Location: editarDiapositivesContingut.php?id=" . $id_presentacio);
                
            }else {
                    // Insertar los datos en la base de datos
                $dao->setDiapositives($titol, $contingut,$id_presentacio); 
                    
                // Redirigir de nuevo a CrearDiapositives.php
                header("Location: editarDiapositivesContingut.php?id=" . $id_presentacio);         
            } 
            
        }
        
    }
       }else {
            
            // Obtener los datos del formulario
            $titol = $_POST["titol"];
            $id_presentacio = $_POST["id_presentacio"];
            if(strlen($titol) > 25){
                // Redirigir de nuevo a CrearDiapositives.php
                header("Location: editarDiapositivesTitol.php?id=" . $id_presentacio);
            }else{

            if (isset($_POST['id_diapo'])) {  
                $editDiapo = $_POST['id_diapo'];
                $dao->alterDiapositivesTitol($titol, $editDiapo);
                
                header("Location: editarDiapositivesTitol.php?id=" . $id_presentacio );
                
            }else {
                    // Insertar los datos en la base de datos
                $dao->setDiapositivesTitol($titol,$id_presentacio); 
                    
                // Redirigir de nuevo a CrearDiapositives.php
                header("Location: editarDiapositivesTitol.php?id=" . $id_presentacio);         
            }
        }         
    }
        

    exit();          
}

// Redirige a una página de edición dependiendo del tipo de contenido de la diapositiva o la acción seleccionada.

// Editar una diapositiva existente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_diapo"])) {
    $id = $_POST['id'];
    $id_diapo = $_POST['id_diapo'];

    // Obtiene el contenido de la diapositiva por su ID
    $cont = $dao->getContingutPorID($id_diapo);
    $pregunta = $dao->getPregunta($id_diapo);
    if($cont != NULL ){
        // Comprueba si la diapositiva tiene contenido:
        $imatge = $dao->getImatgePorID($id_diapo);

        if ($imatge != NULL) {
            // Comprueba si la diapositiva contiene una imagen y redirige al editor de imagen si es el caso.
            header("Location: editarDiapositivesImatge.php?id=" . $id . "&id_diapo=" . $id_diapo);
        } else {
            // Si no contiene imagen, redirige al editor de contenido de texto.
            header("Location: editarDiapositivesContingut.php?id=" . $id . "&id_diapo=" . $id_diapo);
        }
    } elseif ($pregunta !== NULL) {
        header("Location: editarDiapositivesPregunta.php?id=".$id."&id_diapo=".$id_diapo);

    }else {
        // Si no contiene contenido, redirige al editor de título.
        header("Location: editarDiapositivesTitol.php?id=".$id."&id_diapo=".$id_diapo);
    }
}

// Obtener información y redirigir a las páginas correspondientes para agregar una nueva diapositiva.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["getInfoDiapo"])) {
    $id = $_POST['id'];
    $id_diapo = $_POST['id_diapo'];
    $pregunta = $dao->getPregunta($id_diapo);
    $cont = $dao->getContingutPorID($id_diapo);
    $imatge = $dao->getImatgePorID($id_diapo);

    if ($cont != NULL) {
        // Comprueba si la diapositiva tiene contenido:

        if ($imatge != NULL) {
            // Comprueba si la diapositiva contiene una imagen y redirige al formulario correspondiente.
            header("Location: CrearDiapositivesImatge.php?id=" . $id . "&id_diapo=" . $id_diapo);
        } else {
            // Si no contiene imagen, redirige al formulario de contenido de texto.
            header("Location: CrearDiapositivesContingut.php?id=" . $id . "&id_diapo=" . $id_diapo);
        }
    }else if ($pregunta != NULL) {
        header("Location: CrearDiapositivesPregunta.php?id=".$id."&id_diapo=".$id_diapo);
    }else {
        // Si no contiene contenido, redirige al formulario de título.
        header("Location: CrearDiapositivesTitol.php?id=".$id."&id_diapo=".$id_diapo);
    }
}

// Similar a la sección anterior, pero se redirige a las páginas correspondientes para previsualizar la diapositiva.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["getInfoDiapoVista"])) {
    $id = $_POST['id'];
    $id_diapo = $_POST['id_diapo'];
    $pregunta = $dao->getPregunta($id_diapo);
    $cont = $dao->getContingutPorID($id_diapo);
    $imatge = $dao->getImatgePorID($id_diapo);

    if ($cont != NULL) {
        // Comprueba si la diapositiva tiene contenido:

        if ($imatge != NULL) {
            // Comprueba si la diapositiva contiene una imagen y redirige a la vista previa correspondiente.
            header("Location: vistaPreviaClientImatge.php?id=" . $id . "&id_diapo=" . $id_diapo);
        } else {
            // Si no contiene imagen, redirige a la vista previa de contenido de texto.
            header("Location: vistaPreviaClientContingut.php?id=" . $id . "&id_diapo=" . $id_diapo);
        }
    }else if ($pregunta !== NULL) {
        header("Location: vistaPreviaClientPregunta.php?id=" . $id . "&id_diapo=" . $id_diapo);
    }else {
        // Si no contiene contenido, redirige a la vista previa de título.
        header("Location: vistaPreviaClientTitol.php?id=" . $id . "&id_diapo=" . $id_diapo);
    }
}

// Cambia el orden de una diapositiva hacia arriba (si es posible).
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ordenDiapoUp"])) {
    $id_diapo = $_POST['id_diapo'];

    try {
        // Intenta cambiar el orden de la diapositiva hacia arriba.
        $dao->changeOrdenUp($id_diapo);
    } catch (PDOException $th) {
        // En caso de error, muestra un mensaje.
        echo 'No se logró reordenar las diapositivas';
    }
}

// Cambia el orden de una diapositiva hacia abajo (si es posible).
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ordenDiapoDown"])) {
    $id_diapo = $_POST['id_diapo'];

    try {
        // Intenta cambiar el orden de la diapositiva hacia abajo.
        $dao->changeOrdenDown($id_diapo);
    } catch (PDOException $th) {
        // En caso de error, muestra un mensaje.
        echo 'No se logró reordenar las diapositivas';
    }
}

// Elimina una diapositiva y redirige a la página de edición de títulos con un mensaje de confirmación o error.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminarDiapo"])) {
    $id = $_POST['id'];
    $id_diapo = $_POST['id_diapo'];

    // Elimina la diapositiva con el ID especificado.
    $dao->eliminarDiapo($id_diapo);
    $mensaje = 'Diapositiva eliminada correctamente';
    $mensajeCodificado = base64_encode($mensaje);

    if ($dao == TRUE) {
        // Si la eliminación tuvo éxito, muestra un mensaje de éxito.
        header("Location: editarDiapositivesTitol.php?id=" . $id . "&feedEliminado=".$mensajeCodificado);
    } else {
        // Si la eliminación falla, muestra un mensaje de error.
        echo 'No se pudo eliminar la diapositiva';
    }
}

// Comprueba si se envió un formulario para eliminar una presentación.
if (isset($_POST['form']) && $_POST['form'] === 'eliminar') {
    $id_presentacion = $_POST['id_presentacion'];

    // Elimina la presentación con el ID especificado.
    $result = $dao->eliminarPresentacion($id_presentacion);

    if ($result) {
        // Si la eliminación tuvo éxito, muestra un mensaje de éxito.
        echo '<div id="message-container" class="mensaje-exito">Presentación eliminada correctamente.</div>';
    } else {
        // Si la eliminación falla, muestra un mensaje de error.
        echo '<div id="message-container" class="mensaje-error">No se pudo eliminar la presentación.</div>';
    }
}

// Redirige a la página de edición de títulos de presentación cuando se selecciona editar presentación.
if (isset($_POST['editar_presentacion'])) {
    $id_presentacion = $_POST['id_presentacion'];
    
    header("Location: editarDiapositivesTitol.php?id=" . $id_presentacion);
    exit();
}

// Redirige a la página visualización de presentación cuando se selecciona previsualizar presentación.
if (isset($_POST['previsualizar_presentacion'])) {
    $id_presentacion = $_POST['id_presentacion'];
    $from = $_POST['from'];

    // Construir la URL de redirección con ambos valores
    $redireccion_url = "vistaPrevia.php?id=" . $id_presentacion . "&from=" . $from;

    // Redirigir a vistaPrevia.php
    header("Location: " . $redireccion_url);
    exit();
}

// Redirige a la página visualización de presentación como cliente cuando se selecciona previsualizar presentación.
if (isset($_POST['previsualizar_client'])) {
    $id_presentacion = $_POST['id_presentacion'];
    $id_diapo = $_POST['id_diapo'];
    $from = $_POST['from'];

    // Construir la URL de redirección con ambos valores
    $redireccion_url = "previsualitzarClient.php?id=" . $id_presentacion . "&id_diapo=".$id_diapo . "&from=" . $from;

    // Redirigir a vistaPrevia.php
    header("Location: " . $redireccion_url);
    exit();
}

// Redirige a la página de edición de presentación cuando se selecciona la opción "Editar Presentación".
if (isset($_POST['editarPres'])) {
    $id_presentacion = $_GET['id'];
    
    header("Location: editarPresentacio.php?id=" . $id_presentacion);
    exit();
}

// Comprueba si se proporciona un parámetro "id" en la URL y recupera el estilo de presentación correspondiente.
if (isset($_GET['id'])) {
    $id_presentacio = $_GET['id'];
    $estiloPresentacion = $dao->getEstiloPresentacion($id_presentacio);
}

// Redirige a la página de previsualización de diapositivas para una presentación específica.
if (isset($_POST['previsualizar_diapo'])) {
    $id_presentacion = $_POST['id_presentacio'];

    $redireccion_url = "previsualitzarDiapositiva.php?id=" . $id_presentacion;

    header("Location: " . $redireccion_url);
    exit();
}

// Maneja la publicación o despublicación de una presentación.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["publicar_presentacion"])) {
    $id_presentacion = $_POST['id_presentacion'];

    // Verifica si la presentación está publicada actualmente.
    $estaba_publicada = $dao->getPublicacionPresentacion($id_presentacion);
    

    if ($estaba_publicada) {
        // Si la presentación estaba publicada, ahora la despublica.
        $result = $dao->despublicarPresentacion($id_presentacion);
    } else {
        // Si la presentación no estaba publicada, ahora la publica.
        $result = $dao->publicarPresentacion($id_presentacion);
    }

    if ($result && $estaba_publicada) {
        // Muestra un mensaje de éxito si la presentación se despublica correctamente.
        echo '<div id="message-container" class="mensaje-exito" style="position: fixed;">Presentación despublicada correctamente.</div>';
    } elseif ($result && !$estaba_publicada) {
        // Muestra un mensaje de éxito si la presentación se publica correctamente.
        echo '<div id="message-container" class="mensaje-exito" style="position: fixed;">Presentación publicada correctamente.</div>';
    } else {
        // Muestra un mensaje de error si no se pudo completar la operación.
        echo '<div id="message-container" class="mensaje-error" style="position: fixed;">No se pudo completar la operación.</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["anadirEditarDiapositivaPregunta"])) {
    try {
        $id_presentacio = $_POST["id_presentacio"];
        $titol = $_POST["titol"];
        $preguntaTexto = $_POST["pregunta"];

        if (isset($_POST['id_diapo'])) {
            // Editar diapositiva existente
            $editDiapo = $_POST['id_diapo'];
            $nuevasRespuestas = $_POST['opcion'];
            $respuesta_correcta_index = $_POST['respuesta_correcta'] - 1;

            $dao->alterDiapositivesTitol($titol, $editDiapo);
            $pregunta = $dao->getPregunta($editDiapo);
            $dao->updatePregunta($pregunta['ID_pregunta'], $preguntaTexto);

            $respuestas = $dao->getRespuestas($pregunta['ID_pregunta']);

            foreach ($nuevasRespuestas as $index => $nuevaRespuestaTexto) {
                $correcta = ($index == $respuesta_correcta_index) ? 1 : 0;
                if (isset($respuestas[$index])) {
                    $respuesta = $respuestas[$index];
                    $dao->updateRespuesta($respuesta['ID_respuesta'], $nuevaRespuestaTexto, $correcta);
                } else {
                    $dao->setRespuesta($pregunta['ID_pregunta'], $nuevaRespuestaTexto, $correcta);
                }
            }

            header("Location: editarDiapositivesPregunta.php?id=" . $id_presentacio . "&id_diapo=".$editDiapo);
            
        } else {
            // Agregar nueva diapositiva con pregunta y respuestas
            $id_presentacio = $_POST["id_presentacio"];
            $titol = $_POST["titol"];
            $pregunta = $_POST["pregunta"];

            $dao->setPregunta($pregunta);
            $id_pregunta = $dao->getLastInsertId();
            $dao->setDiapositivesTitol($titol, $id_presentacio, $id_pregunta);
            $dao->setDiapositivesTitol($titol, $id_presentacio, $id_pregunta);
            $id_diapo = $dao->getLastInsertId();
            
            $respuestas = $_POST['opcion'];
            $respuesta_correcta_index = $_POST['respuesta_correcta'] - 1;


            foreach ($respuestas as $numeroRespuesta => $respuestaTexto) {
                $correcta = ($numeroRespuesta == $respuesta_correcta_index) ? 1 : 0;

                $dao->setRespuesta($id_pregunta, $respuestaTexto, $correcta);
                
            }

            header("Location: editarDiapositivesPregunta.php?id=" . $id_presentacio . "&id_diapo=".$id_diapo);
        }
        exit(); 
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}










