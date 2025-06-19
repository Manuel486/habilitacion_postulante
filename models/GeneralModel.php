<?php

require_once DATABASE_PATH."/conexionDocumentos.php";

class GeneralModel
{
    public function __construct() {}

    public function obtenerFases()
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                        general.id,
                        general.cod,
                        general.descripcion
                    FROM
                        general 
                    WHERE
                        general.clase = '09' 
                        AND general.cod <> '00' 
                    ORDER BY
                        general.descripcion ASC ";

            $statement = $pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error al obtener los datos"];
        }
    }

    public function obtenerCargos()
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                        general.id,
                        general.cod,
                        general.descripcion
                    FROM
                        general 
                    WHERE
                        general.clase = '01' 
                        AND general.cod <> '00' 
                    ORDER BY
                        general.descripcion ASC ";

            $statement = $pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error al obtener los datos"];
        }
    }
}
