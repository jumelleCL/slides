<?php

class DAO {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtiene todas las presentaciones de la base de datos
    public function getPresentacions() {
        $sql = "SELECT titol, ID_Presentacio, publicada, url_unica FROM Presentacions";
        $statement = ($this->pdo)->query($sql);

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement;
    }

    // Obtiene el título de una presentación por su ID
    public function getTitolPorID($id_presentacio) {
        $sql = "SELECT titol FROM Presentacions WHERE ID_Presentacio = :id_presentacio";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_presentacio' => $id_presentacio]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['titol'];
        } else {
            return "Título no encontrado";
        }
    }
    // Obtiene la descripción de una presentación por su ID
    public function getDescPorID($id_presentacio) {
        $sql = "SELECT descripcio FROM Presentacions WHERE ID_Presentacio = :id_presentacio";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_presentacio' => $id_presentacio]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['descripcio'];
        } else {
            return "Descripción no encontrada";
        }
    }

    // Obtiene el ID de una presentación por su URL única
    public function getIdPorURL($url_unica) {
        $sql = "SELECT ID_Presentacio FROM Presentacions WHERE url_unica = :url_unica";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':url_unica' => $url_unica]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['ID_Presentacio'];
        } else {
            return "ID no encontrado";
        }
    }

    // Obtiene la URL única de una presentación por su ID
    public function getURLPorID($id_presentacio) {
        $sql = "SELECT url_unica FROM Presentacions WHERE ID_Presentacio = :id_presentacio";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_presentacio' => $id_presentacio]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['url_unica'];
        } else {
            return "URL no encontrada";
        }
    }
    public function getEstadoPublicacion($idPresentacion) {
        $sql = "SELECT publicada FROM Presentacions WHERE ID_Presentacio = :idPresentacion";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':idPresentacion', $idPresentacion, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Verificar si se obtuvo un resultado
        if ($result) {
            return $result['publicada'] ? 1 : 0;
        } else {
            return 'No existe la presentación con ID ' . $idPresentacion;
        }
    }

    // Obtiene el último ID insertado
    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }

    // Obtiene el título de una diapositiva por su ID
    public function getTitolDiapoPorID($id_diapositiva) {
        $sql = "SELECT titol FROM Diapositives WHERE ID_Diapositiva = :id_diapositiva LIMIT 1";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_diapositiva' => $id_diapositiva]);

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        return $row['titol'];
    }

    // Obtiene el contenido de una diapositiva por su ID
    public function getContingutPorID($id_diapositiva) {
        $sql = "SELECT contingut FROM Diapositives WHERE ID_Diapositiva = :id_diapositiva LIMIT 1";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_diapositiva' => $id_diapositiva]);

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        return $row['contingut'];
    }

    // Obtiene la imagen de una diapositiva por su ID
    public function getImatgePorID($id_diapositiva) {
        $sql = "SELECT imatge FROM Diapositives WHERE ID_Diapositiva = :id_diapo LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_diapo' => $id_diapositiva]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        return $row['imatge'];
    }

    // Obtiene el último orden de una diapositiva en una presentación
    public function getLastOrden($id_presentacio) {
        $sql = "SELECT orden FROM Diapositives WHERE ID_Presentacio = :id_presentacio ORDER BY orden DESC LIMIT 1";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_presentacio' => $id_presentacio]);

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        if (is_array($row)) {
            return $row['orden'];
        } else {
            return 'NULL';
        }
    }

    // Obtiene el orden de una diapositiva por su ID
    public function getOrdenPorID($id_diapo) {
        $sql = "SELECT orden FROM Diapositives WHERE ID_Diapositiva = :id_diapo";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_diapo' => $id_diapo]);

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        return $row['orden'];
    }

    // Inserta una nueva presentación en la base de datos
    public function setPresentacions($titol, $descripcio, $estil, $pin) {
        $sql = "INSERT INTO Presentacions (titol, descripcio, estil, pin) VALUES (:titol, :descripcio, :estil, :pin)";
        $statement = ($this->pdo)->prepare($sql);

        try {
            $statement->execute([
                "titol" => $titol,
                "descripcio" => $descripcio,
                "estil" => $estil,
                "pin" => $pin
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    // Edita una presentación en la base de datos
    public function editarPresentacio($titol, $descripcio, $id) {
        $sql = "UPDATE Presentacions SET titol = (:titol), descripcio = (:descripcio) WHERE ID_Presentacio = (:id)";
        $statement = ($this->pdo)->prepare($sql);

        try {
            $statement->execute([
                "titol" => $titol,
                "descripcio" => $descripcio,
                "id" => $id
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    // Inserta una nueva diapositiva con imagen en la base de datos
    public function setDiapositivesImatge($titol, $contingut, $imatge, $id_presentacio) {
        $sql = "INSERT INTO Diapositives (titol, contingut, imatge, orden, ID_Presentacio) VALUES (:titol, :contingut, :imatge, :orden, :id_presentacio)";
        $statement = ($this->pdo)->prepare($sql);

        $orden = $this->getLastOrden($id_presentacio);
        if ($orden === 'NULL') {
            $orden = 1;
        } else {
            $orden = ($this->getLastOrden($id_presentacio)) + 1;
        }
        try {
            $statement->execute([
                ":titol" => $titol,
                ":contingut" => $contingut,
                ":imatge" => $imatge,
                ":orden" => $orden,
                ':id_presentacio' => $id_presentacio
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    // Inserta una nueva diapositiva sin imagen en la base de datos
    public function setDiapositives($titol, $contingut, $id_presentacio) {
        $sql = "INSERT INTO Diapositives (titol, contingut, orden, ID_Presentacio) VALUES (:titol, :contingut, :orden, :id_presentacio)";
        $statement = ($this->pdo)->prepare($sql);

        $orden = $this->getLastOrden($id_presentacio);
        if ($orden === 'NULL') {
            $orden = 1;
        } else {
            $orden = ($this->getLastOrden($id_presentacio)) + 1;
        }
        try {
            $statement->execute([
                ":titol" => $titol,
                ":contingut" => $contingut,
                ":orden" => $orden,
                ':id_presentacio' => $id_presentacio
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    // Inserta una nueva diapositiva sin imagen y sin contenido en la base de datos
    public function setDiapositivesTitol($titol, $id_presentacio, $id_pregunta = null) {
        $sql = "INSERT INTO Diapositives (titol, orden, ID_Presentacio, ID_pregunta) VALUES (:titol, :orden, :id_presentacio, :id_pregunta)";
        $statement = ($this->pdo)->prepare($sql);

        $orden = $this->getLastOrden($id_presentacio);
        if ($orden === 'NULL') {
            $orden = 1;
        } else {
            $orden = ($this->getLastOrden($id_presentacio)) + 1;
        }
        try {
            $statement->execute([
                ":titol" => $titol,
                ":orden" => $orden,
                ':id_presentacio' => $id_presentacio,
                ":id_pregunta" => $id_pregunta
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    // Edita una diapositiva con imagen en la base de datos
    public function alterDiapositivesImatge($titol, $contingut, $imatge, $id_diapositiva) {
        $sql = "UPDATE  Diapositives SET titol = :titol, contingut = :contingut, imatge = :imatge WHERE ID_Diapositiva = :id_diapo";
        $statement = ($this->pdo)->prepare($sql);

        try {
            $statement->execute([
                "titol" => $titol,
                "contingut" => $contingut,
                "imatge" => $imatge,
                ':id_diapo' => $id_diapositiva
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    // Edita una diapositiva sin imagen en la base de datos
    public function alterDiapositives($titol, $contingut, $id_diapositiva) {
        $sql = "UPDATE  Diapositives SET titol = :titol, contingut = :contingut WHERE ID_Diapositiva = :id_diapo";
        $statement = ($this->pdo)->prepare($sql);

        try {
            $statement->execute([
                "titol" => $titol,
                "contingut" => $contingut,
                ':id_diapo' => $id_diapositiva
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    // Edita el título de una diapositiva en la base de datos
    public function alterDiapositivesTitol($titol, $id_diapositiva) {
        $sql = "UPDATE  Diapositives SET titol = :titol WHERE ID_Diapositiva = :id_diapo";
        $statement = ($this->pdo)->prepare($sql);

        try {
            $statement->execute([
                "titol" => $titol,
                ':id_diapo' => $id_diapositiva
            ]);
        } catch (PDOException $e) {
            echo "Error al guardar datos: " . $e->getMessage();
        }
    }

    public function changeOrdenUp($id_diapo) {
        try {
            // Obtener el orden anterior de la diapositiva
            $ordenAnterior = $this->getOrdenPorID($id_diapo);
            
            // Verificar si el orden anterior es mayor que 1
            if ($ordenAnterior > 1) {
                // Preparar la consulta SQL para actualizar el orden a -1
                $sql1 = "UPDATE Diapositives SET orden = -1 WHERE orden = :orden1";
                $statement1 = $this->pdo->prepare($sql1);
                
                // Preparar la consulta SQL para actualizar el orden a :orden1
                $sql2 = "UPDATE Diapositives SET orden = :orden1 WHERE orden = :orden2";
                $statement2 = $this->pdo->prepare($sql2);
                
                // Preparar la consulta SQL para actualizar el orden a :orden2
                $sql3 = "UPDATE Diapositives SET orden = :orden2 WHERE orden = -1";
                $statement3 = $this->pdo->prepare($sql3);
                
                try {
                    // Ejecutar la primera consulta para establecer el orden en -1
                    $statement1->execute(['orden1' => $ordenAnterior - 1]);
                    
                    // Ejecutar la segunda consulta para intercambiar órdenes
                    $statement2->execute(['orden1' => $ordenAnterior - 1, 'orden2' => $ordenAnterior]);
                    
                    // Ejecutar la tercera consulta para actualizar el orden a su nuevo valor
                    $statement3->execute(['orden2' => $ordenAnterior]);
                } catch (PDOException $th) {
                    echo 'Error al intercambiar el orden: ';
                    echo $ordenAnterior;
                }
            }
        } catch (PDOException $th) {
            echo 'Error al intercambiar el orden';
        }
    }
    
    public function changeOrdenDown($id_diapo) {
        try {            
            // Obtener el orden anterior de la diapositiva
            $ordenAnterior = $this->getOrdenPorID($id_diapo);
            
            // Obtener el valor máximo del orden en la tabla
            $sql = "SELECT orden FROM Diapositives ORDER BY orden DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
    
            if ($ordenAnterior <= $row['orden']) {
                // Preparar la consulta SQL para actualizar el orden a -1
                $sql1 = "UPDATE Diapositives SET orden = -1 WHERE orden = :orden1";
                $statement1 = $this->pdo->prepare($sql1);
                
                // Preparar la consulta SQL para actualizar el orden a :orden1
                $sql2 = "UPDATE Diapositives SET orden = :orden1 WHERE orden = :orden2";
                $statement2 = $this->pdo->prepare($sql2);
                
                // Preparar la consulta SQL para actualizar el orden a :orden2
                $sql3 = "UPDATE Diapositives SET orden = :orden2 WHERE orden = -1";
                $statement3 = $this->pdo->prepare($sql3);
                try {
                    // Ejecutar la primera consulta para establecer el orden en -1
                    $statement1->execute(['orden1' => $ordenAnterior + 1]);
                    
                    // Ejecutar la segunda consulta para intercambiar órdenes
                    $statement2->execute(['orden1' => $ordenAnterior + 1, 'orden2' => $ordenAnterior]);
                    
                    // Ejecutar la tercera consulta para actualizar el orden a su nuevo valor
                    $statement3->execute(['orden2' => $ordenAnterior]);
                } catch (PDOException $th) {
                    echo 'Error al intercambiar el orden: ';
                    echo $ordenAnterior;
                }
            }
        } catch (PDOException $th) {
            echo 'Error al intercambiar el orden';
        }
    }
    
    public function getDiapositives($id_presentacio) {
        // Preparar la consulta SQL para obtener las diapositivas ordenadas por orden ASC
        $sql = "SELECT titol, ID_Diapositiva FROM Diapositives WHERE ID_Presentacio = :id_presentacio ORDER BY orden ASC";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_presentacio' => $id_presentacio]);
        
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement;
    }
    public function getPresentacioPorID($id_diapo){
        $sql = "SELECT ID_Presentacio FROM Diapositives WHERE ID_Diapositiva = :id_diapo LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_diapo' => $id_diapo]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        return $row['ID_Presentacio'];
    }
    public function eliminarDiapo($id_diapo){
        try {
            $this->pdo->beginTransaction();
            $id_presentacio = $this->getPresentacioPorID($id_diapo); 
            $orden = $this->getOrdenPorID($id_diapo) +1;

            // Preparar la consulta SQL para eliminar una diapositiva por su ID
            $sql = "DELETE from Diapositives WHERE ID_Diapositiva = :id_diapo";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':id_diapo', $id_diapo);
            $statement->execute(); 
    
            $this->pdo->commit();
            for ($i=$orden; $i <= $id_presentacio; $i++) { 
                $sqlOrden = "UPDATE Diapositives SET orden = :ordenNew WHERE orden= :ordenOld AND ID_Presentacio = :id_presentacio ";
                $stmt = $this->pdo->prepare($sqlOrden);
                $stmt->execute([
                    ':ordenNew' => ($i-1),
                     'ordenOld'=>$i, 
                     ':id_presentacio' =>$id_presentacio
                    ]);
            }
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollback();
            return false;
        }
    }
    
    public function eliminarPresentacion($id_presentacion) {
        try {
            $this->pdo->beginTransaction();
    
            // Eliminar las diapositivas relacionadas
            $sql_eliminar_diapositivas = "DELETE FROM Diapositives WHERE ID_Presentacio = :id_presentacion";
            $statement_eliminar_diapositivas = $this->pdo->prepare($sql_eliminar_diapositivas);
            $statement_eliminar_diapositivas->execute([':id_presentacion' => $id_presentacion]);
    
            // Luego, eliminar la propia presentación
            $sql_eliminar_presentacion = "DELETE FROM Presentacions WHERE ID_Presentacio = :id_presentacion";
            $statement_eliminar_presentacion = $this->pdo->prepare($sql_eliminar_presentacion);
            $statement_eliminar_presentacion->execute([':id_presentacion' => $id_presentacion]);
    
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollback();
            return false;
        }
    }
    
    public function editarEstilsPresentacio($id_presentacion, $estils) {
        // Preparar la consulta SQL para actualizar los estilos de la presentación
        $sql = "UPDATE Presentacions SET estil = :estils WHERE ID_Presentacio = (:id_presentacion)";
        $statement = ($this->pdo)->prepare($sql);
    
        try {
            $statement->execute([
                "estils" => $estils,
                "id_presentacion" => $id_presentacion
            ]);
        } catch (PDOException $e) {
            echo "Error al actualizar estilos: " . $e->getMessage();
        }
    }
    
    public function getEstiloPresentacion($id_presentacio) {
        if (isset($id_presentacio)) {
            // Preparar la consulta SQL para obtener el estilo de la presentación
            $sql = "SELECT estil FROM Presentacions WHERE ID_Presentacio = :id_presentacio";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([':id_presentacio' => $id_presentacio]);
            
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        
            return $result ? $result['estil'] : null;
        } else {
            return null;  
        }
    }
    
    public function getPublicacionPresentacion($id_presentacion) {
        // Preparar la consulta SQL para obtener el estado de publicación de la presentación
        $sql = "SELECT publicada FROM Presentacions WHERE ID_Presentacio = :id_presentacion";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id_presentacion' => $id_presentacion]);
    
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            return $result['publicada'] == true; // Verifica si está publicada
        } else {
            return false; // Si no se encuentra, consideramos que no está publicada
        }
    }
    
    public function generarURLUnica() {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 10; // Cambia la longitud de la URL según tus necesidades
        $url_unica = '';
        for ($i = 0; $i < $longitud; $i++) {
            $url_unica .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $url_unica;
    }
    
    public function publicarPresentacion($id_presentacion) {
        // Generar una URL única para la presentación
        $url_unica = $this->generarURLUnica();
        
        // Preparar la consulta SQL para marcar la presentación como publicada con la URL única
        $sql = "UPDATE Presentacions SET publicada = TRUE, url_unica = :url_unica WHERE ID_Presentacio = :id_presentacion";
        $statement = $this->pdo->prepare($sql);
    
        try {
            $statement->execute([':url_unica' => $url_unica, ':id_presentacion' => $id_presentacion]);
            return true; // Devuelve la URL única generada
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function despublicarPresentacion($id_presentacion) {
        // Preparar la consulta SQL para marcar la presentación como no publicada y eliminar la URL única
        $sql = "UPDATE Presentacions SET publicada = FALSE, url_unica = NULL WHERE ID_Presentacio = :id_presentacion";
        $statement = $this->pdo->prepare($sql);
        
        try {
            $statement->execute([':id_presentacion' => $id_presentacion]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getHashContrasena($id_presentacion) {
        // Preparar la consulta SQL para obtener el pin (contraseña) de la presentación
        $sql = "SELECT pin FROM Presentacions WHERE ID_Presentacio = :id_presentacion";
        $stmt = $this->pdo->prepare($sql);
    
        try {
            $stmt->bindParam(':id_presentacion', $id_presentacion, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el pin: " . $e->getMessage());
        }
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['pin'] : false;
    }

    public function setPregunta($pregunta) {
        $sql = "INSERT INTO pregunta (pregunta) 
                VALUES (:pregunta)";
    
        $stmt = $this->pdo->prepare($sql);
    
        try {
            $stmt->bindParam(':pregunta', $pregunta, PDO::PARAM_STR);
    
            $stmt->execute();
    
            // Después de la ejecución, obtén el ID de la pregunta recién insertada
            $id_pregunta = $this->pdo->lastInsertId();
    
            return $id_pregunta;
        } catch (PDOException $e) {
            throw new Exception("Error al insertar pregunta: " . $e->getMessage());
        }
    }

    public function getPregunta($id_diapositiva) {
        $sql = "SELECT d.ID_pregunta, p.pregunta 
                FROM Diapositives d
                JOIN pregunta p ON d.ID_pregunta = p.ID_pregunta
                WHERE d.ID_diapositiva = :id_diapositiva";
    
        $stmt = $this->pdo->prepare($sql);
    
        try {
            $stmt->bindParam(':id_diapositiva', $id_diapositiva, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la pregunta: " . $e->getMessage());
        }
    
        $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $pregunta ? $pregunta : null;
    }
    
    public function setRespuesta($id_pregunta, $texto, $correcta) {
        $sql = "INSERT INTO respuesta (texto, correcta, ID_pregunta) 
                VALUES (:texto, :correcta, :id_pregunta)";
    
        $stmt = $this->pdo->prepare($sql);
    
        try {
            $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
            $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
            $stmt->bindParam(':correcta', $correcta, PDO::PARAM_INT);
    
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar respuesta: " . $e->getMessage());
        }
    }

    public function getRespuestas($id_pregunta) {
        $sql = "SELECT * FROM respuesta WHERE ID_pregunta = :id_pregunta";
        $stmt = $this->pdo->prepare($sql);
    
        try {
            $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener las respuestas: " . $e->getMessage());
        }
    
        $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuestas ? $respuestas : array();
    }

    public function updatePregunta($id_pregunta, $nuevoTexto) {
        $sql = "UPDATE pregunta SET pregunta = :nuevoTexto WHERE ID_pregunta = :id_pregunta";
        $stmt = $this->pdo->prepare($sql);
    
        try {
            $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
            $stmt->bindParam(':nuevoTexto', $nuevoTexto, PDO::PARAM_STR);
    
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar la pregunta: " . $e->getMessage());
        }
    }

    public function updateRespuesta($id_respuesta, $nuevoTexto, $correcta) {
        $sql = "UPDATE respuesta SET texto = :nuevoTexto, correcta = :correcta WHERE ID_respuesta = :id_respuesta";
        $stmt = $this->pdo->prepare($sql);
    
        try {
            $stmt->bindParam(':id_respuesta', $id_respuesta, PDO::PARAM_INT);
            $stmt->bindParam(':nuevoTexto', $nuevoTexto, PDO::PARAM_STR);
            $stmt->bindParam(':correcta', $correcta, PDO::PARAM_INT);
    
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar la respuesta: " . $e->getMessage());
        }
    }

    public function getDiapositivesVista($id_presentacio) {
        $sql = "SELECT
            d.titol,
            d.contingut,
            d.imatge,
            d.orden,
            p.pregunta,
            d.ID_pregunta,
            d.ID_diapositiva AS pregunta_diapo_id,
            NULL AS respuesta_diapo_id,
            NULL AS opcion_respuesta,
            NULL AS correcta_respuesta
        FROM Diapositives d
        LEFT JOIN pregunta p ON d.ID_pregunta = p.ID_pregunta
        WHERE d.ID_Presentacio = :id_presentacio
    
        UNION
    
        SELECT
            d.titol,
            d.contingut,
            d.imatge,
            d.orden,
            NULL AS pregunta,
            NULL AS pregunta_diapo_id,
            NULL AS pregunta_diapo_id,
            d.ID_diapositiva AS respuesta_diapo_id,
            r.texto AS opcion_respuesta,
            r.correcta
        FROM Diapositives d
        LEFT JOIN pregunta p ON d.ID_pregunta = p.ID_pregunta
        LEFT JOIN respuesta r ON p.ID_pregunta = r.ID_pregunta
        WHERE d.ID_Presentacio = :id_presentacio
        ORDER BY orden;";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_presentacio', $id_presentacio, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = array();
        $currentPreguntaID = null;
        $diapositiva['ID_Diapositiva'] = null;
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $currentDiapoID = $row['pregunta_diapo_id'] ?? $row['respuesta_diapo_id'];
    
            // Verifica si la diapositiva actual es diferente de la anterior
            if ($currentDiapoID != $diapositiva['ID_Diapositiva']) {
                // Si es diferente, guarda la diapositiva actual y comienza una nueva
                if ($diapositiva !== null) {
                    $result[] = $diapositiva;
                }
    
                $diapositiva = array(
                    'titol' => $row['titol'],
                    'contingut' => $row['contingut'],
                    'imatge' => $row['imatge'],
                    'orden' => $row['orden'],
                    'ID_Diapositiva' => $currentDiapoID,
                    'pregunta' => $row['pregunta'],
                    'es_pregunta' => !empty($row['pregunta']), // Indicador de si es una pregunta o no
                    'pregunta_id' => $row['ID_pregunta'],
                    'respuestas' => array(),
                );
            }
    
            if (!empty($row['opcion_respuesta'])) {
                // Agregar respuesta a la pregunta actual
                $diapositiva['respuestas'][] = array(
                    'respuesta_texto' => $row['opcion_respuesta'],
                    'correcta' => $row['correcta_respuesta']
                );
            }
        }


        // Agregar la última diapositiva al resultado
        if ($diapositiva !== null) {
            $result[] = $diapositiva;
        }

        return $result;
    }
    
}
    
    


