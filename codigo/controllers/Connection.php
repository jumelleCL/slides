<?php
class Connection {
    // Declaración de una propiedad estática para almacenar la única instancia de PDO.
    private static $pdo;

    // Constructor privado para evitar la creación de instancias de esta clase.
    private final function __construct(){
        // Este constructor privado impide la creación de instancias de la clase Connection.
    }

    // Método estático para obtener una conexión a la base de datos.
    public static function getConnection($dbconfig){
        // Verifica si ya existe una instancia de PDO.
        if(!isset(self::$pdo)){
            // Si no existe, crea una nueva instancia de PDO utilizando la configuración proporcionada.

            // - $dbconfig['connection'] contiene la dirección del servidor y otros detalles de la conexión.
            // - $dbconfig['dbname'] contiene el nombre de la base de datos.
            // - $dbconfig['usr'] contiene el nombre de usuario.
            // - $dbconfig['pwd'] contiene la contraseña.
            // - $dbconfig['options'] contiene opciones de configuración de PDO, en este caso, el modo de error.

            $pdo = new PDO(
                $dbconfig['connection'] . ';dbname=' . $dbconfig['dbname'],
                $dbconfig['usr'],
                $dbconfig['pwd'],
                $dbconfig['options']
            );

            // Almacena la instancia de PDO en la propiedad estática para su posterior uso.
            self::$pdo = $pdo;
        }

        // Devuelve la instancia de PDO previamente almacenada.
        return self::$pdo;
    }
}