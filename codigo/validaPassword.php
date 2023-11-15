<?php

// Incluye archivos de funciones necesarios
include_once("controllers/baseDatos.php");
include_once("controllers/DAO.php");

// Inicia una sesión
session_start();

// Inicializa una matriz en la sesión para almacenar contraseñas válidas
if (!isset($_SESSION['contrasena_valida'])) {
    $_SESSION['contrasena_valida'] = array();
}

if (isset($_GET["url"])){
    $url_unica = $_GET["url"];

} else {
    $titol = "Error, no se encuentra la presentacion";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contrasena = $_POST["password"];
    $id_presentacio = $dao->getIdPorURL($url_unica);
    $passwordHash = $dao->getHashContrasena($id_presentacio);

    // Comprueba si la contraseña ingresada coincide con la contraseña almacenada en la base de datos
    if ($passwordHash && password_verify($contrasena, $passwordHash)) {
        $_SESSION['contrasena_valida'][$id_presentacio] = true;
        // Redirige a la página de vista previa si la contraseña es correcta
        header("Location: vistaPreviaClient.php?url=$url_unica");
        exit();
    } else {
        $error_message = "PIN incorrecto";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>verificació PIN</title>
    <link rel="icon" href="logoSlides.ico" type="image/x-icon">
    <link rel="stylesheet" href="Styles.css">
</head>

<body class="bodyVerificarPin">
    <div class="verificarPinContainer">
        <h2>Introduce el PIN</h2>
        <form method="post" class="verificarPinForm">
                <div class="passwordFieldContainer">
                    <input type="password" id="password" name="password" class="<?php echo isset($error_message) ? 'errorPin ' : ' '; ?>" placeholder="PIN"
                        maxlength="8">
                    <div class="limitInput"></div>
                    <svg class="eye-icon" id="togglePassword" xmlns="http://www.w3.org/2000/svg" height="1em"
                        viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <style>
                            svg {
                                fill: #000000
                            }
                        </style>
                        <path
                            d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zm151 118.3C226 97.7 269.5 80 320 80c65.2 0 118.8 29.6 159.9 67.7C518.4 183.5 545 226 558.6 256c-12.6 28-36.6 66.8-70.9 100.9l-53.8-42.2c9.1-17.6 14.2-37.5 14.2-58.7c0-70.7-57.3-128-128-128c-32.2 0-61.7 11.9-84.2 31.5l-46.1-36.1zM394.9 284.2l-81.5-63.9c4.2-8.5 6.6-18.2 6.6-28.3c0-5.5-.7-10.9-2-16c.7 0 1.3 0 2 0c44.2 0 80 35.8 80 80c0 9.9-1.8 19.4-5.1 28.2zm9.4 130.3C378.8 425.4 350.7 432 320 432c-65.2 0-118.8-29.6-159.9-67.7C121.6 328.5 95 286 81.4 256c8.3-18.4 21.5-41.5 39.4-64.8L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5l-41.9-33zM192 256c0 70.7 57.3 128 128 128c13.3 0 26.1-2 38.2-5.8L302 334c-23.5-5.4-43.1-21.2-53.7-42.3l-56.1-44.2c-.2 2.8-.3 5.6-.3 8.5z" />
                    </svg>
                    <svg class="eye-iconOpen" id="togglePasswordOpen" xmlns="http://www.w3.org/2000/svg" height="1em"
                        viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <style>
                            svg {
                                fill: #000000
                            }
                        </style>
                        <path
                            d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z" />
                    </svg>
                    
                </div>
                <div class="error-message-password">
                    <?php echo isset($error_message) ? '<svg class="iconoIncorrecte" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#000000}</style><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>' . $error_message  : ''; ?>
                </div>
                <input type="submit" class="boton-crear enviar-password" value="Enviar">
        </form>
    </div>
    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const toggleButtonOpen = document.getElementById('togglePasswordOpen');
        const errorMessage = document.querySelector('.error-message-password');
    
        toggleButton.addEventListener('click', function () {
             // Muestra la contraseña cuando se hace clic en el ícono del ojo
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.style.display = 'none';
                toggleButtonOpen.style.display = 'block';
            }

        })

        toggleButtonOpen.addEventListener('click', function () {
            // Oculta la contraseña cuando se hace clic en el ícono del ojo abierto
            if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                toggleButton.style.display = 'block';
                toggleButtonOpen.style.display = 'none';
            }

        })
        
        if (errorMessage.innerText !== '') {
            // Borra el mensaje de error después de un tiempo
            setTimeout(function () {
                errorMessage.innerText = '';
                passwordInput.classList.remove('errorPin');
            }, 2000);
        }
    </script>
</body>

</html>