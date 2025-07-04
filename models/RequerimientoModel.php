<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class RequerimientoModel
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
                        gc.descripcion as 'nombreCargo',
                        tp.cdespry as 'nombreProyecto'
                    from 
                    documentos.requerimiento_proyecto rp
                    LEFT JOIN documentos.general gf ON gf.cod = rp.id_fase AND gf.clase='09'
                    LEFT JOIN documentos.general gc ON gc.cod = rp.id_cargo AND gc.clase='01'
                    LEFT JOIN logistica.tb_proyecto1 tp ON tp.ncodpry = rp.id_proyecto
                    ORDER BY fecha_registro DESC";

            $statement = $pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerRequerimientoPorId($id_requerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT 
                        rp.*,
                        gf.descripcion as 'nombreFase',
                        gc.descripcion as 'nombreCargo',
                        tp.cdespry as 'nombreProyecto'
                    from documentos.requerimiento_proyecto rp
                    LEFT JOIN documentos.general gf ON gf.cod = rp.id_fase AND gf.clase='09'
                    LEFT JOIN documentos.general gc ON gc.cod = rp.id_cargo AND gc.clase='01'
                    LEFT JOIN logistica.tb_proyecto1 tp ON tp.ncodpry = rp.id_proyecto
                    WHERE id_requerimiento=:id_requerimiento";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                "id_requerimiento" => $id_requerimiento
            ]);
            return $statement->fetch(PDO::FETCH_ASSOC);
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
