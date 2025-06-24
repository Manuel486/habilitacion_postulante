<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class Requerimiento
{
    public function __construct()
    {
    }

    public function obtenerRequerimientos()
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT 
                        rp.id_requerimiento,
                        rp.fecha_registro,
                        rp.numero_requerimiento,
                        rp.fecha_requerimiento,
                        rp.tipo_requerimiento,
                        rp.cantidad,
                        rp.regimen,
                        gf.descripcion as 'nombreFase',
                        gc.descripcion as 'nombreCargo'
                    from 
                    requerimiento_proyecto rp
                    LEFT JOIN general gf ON gf.cod = rp.id_fase AND gf.clase='09'
                    LEFT JOIN general gc ON gc.cod = rp.id_cargo AND gc.clase='01'
                    ORDER BY fecha_registro DESC";

            $statement = $pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function insertarRequerimiento($requerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "INSERT INTO requerimiento_proyecto(
                        id_requerimiento,
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

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al insertar requerimiento: " . $e->getMessage());
            return false;
        }
    }
}
