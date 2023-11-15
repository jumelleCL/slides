<?php
// Declaración del arreglo de configuración.
$config = [
    'db' => [
        // Define la información de conexión a la base de datos. Puede haber una conexión alternativa comentada.
        'connection' => 'mysql:host=192.168.1.12',
        //'connection' => 'mysql:host=localhost:3306',

        // Nombre de la base de datos a la que se conectará.
        'dbname' => 'slides',

        // Nombre de usuario de la base de datos.
        'usr' => 'root',

        // Contraseña del usuario de la base de datos.
        'pwd' => 1234,

        // Opciones de configuración para PDO, en este caso, se establece el modo de error para lanzar excepciones en caso de errores.
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ]
];

// Devuelve el arreglo de configuración para que se pueda utilizar en otras partes del código.
return $config;