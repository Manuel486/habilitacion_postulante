<?php

require_once DATABASE_PATH . "/conexionHabilitacion.php";

class Requerimiento
{
    public function __construct() {}

    public function insertarRequerimiento($requerimiento)
    {
        $pdo = ConexionHabilitacion::getInstancia()->getConexion();
        try {
            $sql = "INSERT INTO requerimiento_proyecto(
                        id_requerimiento,
                        fecha_registro,
                        id_proyecto,
                        fecha_requerimiento,
                        numero_requerimiento,
                        tipo_requerimiento,
                        id_fase,
                        id_cargo,
                        cantidad,
                        regimen) 
                    VALUES (
                        :id_requerimiento, 
                        ,
                        :id_proyecto, 
                        :fecha_requerimiento, 
                        :numero_requerimiento,
                        :tipo_requerimiento,
                        :id_fase,
                        :id_cargo,
                        :cantidad,
                        :regimen
                    )";
            $statement = $pdo->prepare($sql);

            $success = $statement->execute([
                ':id_requerimiento' => $requerimiento["id_requerimiento"],
                ':id_proyecto' => $requerimiento["id_proyecto"],
                ':fecha_requerimiento' => $requerimiento["fecha_requerimiento"],
                ':numero_requerimiento' => $requerimiento["numero_requerimiento"],
                ':tipo_requerimiento' => $requerimiento["tipo_requerimiento"],
                ':id_fase' => $requerimiento["id_fase"],
                ':id_cargo' => $requerimiento["id_cargo"],
                ':cantidad' => $requerimiento["cantidad"],
                ':regimen' => $requerimiento["regimen"],
            ]);

            return $success;
        } catch (PDOException $e) {
            return false;
        }
    }

    // public function guardarCargo($codigo, $descripcion)
    // {
    //     $pdo = ConexionDocumentos::getInstancia()->getConexion();
    //     try {
    //         $sql = "INSERT INTO general (clase, cod, descripcion) 
    //             VALUES ('01', :cod, :descripcion)";

    //         $statement = $pdo->prepare($sql);

    //         $success = $statement->execute([
    //             ':cod' => $codigo,
    //             ':descripcion' => $descripcion
    //         ]);

    //         return $success; // Devuelve true si se insertó correctamente
    //     } catch (PDOException $e) {
    //         return false;
    //     }
    // }

    // public function obtenerProyectos() {
    //     $pdo = ConexionLogistica::getInstancia()->getConexion();
    //     try {
    //         $sql = "SELECT
    //                     tb_proyecto1.ncodpry,
    //                     tb_proyecto1.ccodpry,
    //                     tb_proyecto1.cdespry
    //                 FROM
    //                     tb_proyecto1 
    //                 WHERE
    //                     tb_proyecto1.nflgactivo = 1  
    //                 ORDER BY
    //                     tb_proyecto1.ccodpry ASC ";

    //         $statement = $pdo->query($sql);
    //         $statement->execute();
    //         return $statement->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         return ["error" => "Error al obtener los datos"];
    //     }
    // }
}
