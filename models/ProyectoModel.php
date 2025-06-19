<?php

require_once DATABASE_PATH."/conexionLogistica.php";

class ProyectoModel {
    public function __construct(){}

    public function obtenerProyectos() {
        $pdo = ConexionLogistica::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                        tb_proyecto1.ncodpry,
                        tb_proyecto1.ccodpry,
                        tb_proyecto1.cdespry
                    FROM
                        tb_proyecto1 
                    WHERE
                        tb_proyecto1.nflgactivo = 1  
                    ORDER BY
                        tb_proyecto1.ccodpry ASC ";

            $statement = $pdo->query($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error al obtener los datos"];
        }
    }
}