<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class DetalleLegajoModel {
    public function __construct(){}

    function buscarDetalles($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $docData = [];
            $detalles = [];
            $sql = "SELECT
                    detallelegajo.idreg,
                    detallelegajo.idficha,
                    detallelegajo.postulante,
                    detallelegajo.nombre,
                    detallelegajo.parentesco,
                    detallelegajo.fechanac,
                    detallelegajo.dni,
                    detallelegajo.edad,
                    detallelegajo.instruccion,
                    detallelegajo.ocupacion,
                    detallelegajo.vivefamilia 
                FROM
                    detallelegajo 
                WHERE
                    detallelegajo.postulante = ?";

            $statement = $pdo->prepare($sql);
            $statement->execute(array($id));
            $rowCount = $statement->rowCount();

            if ($rowCount) {
                $respuesta = true;
                $docData = [];


                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $docData[] = $row;
                }
            }

            return $docData;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function detallesNuevaFicha($idFicha, $datos, $idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        //var_dump($datos);
        //try {
        foreach ($datos as $dato) {
            $idreg = uniqid();
            $sql = "INSERT INTO detallelegajo 
                        SET detallelegajo.idreg=?,
                            detallelegajo.idficha=?,
                            detallelegajo.postulante=?,
                            detallelegajo.nombre=?,
                            detallelegajo.parentesco=?,
                            detallelegajo.fechanac=?,
                            detallelegajo.dni=?,
                            detallelegajo.edad=?,
                            detallelegajo.instruccion=?,
                            detallelegajo.ocupacion=?,
                            detallelegajo.vivefamilia=?";

            $statement = $pdo->prepare($sql);
            $statement->execute(array(
                $idreg,
                $idFicha,
                $idPostulante,
                $dato['nombre'],
                $dato['parentesco'],
                $dato['fechanac'],
                $dato['dni'],
                $dato['edad'],
                $dato['instruccion'],
                $dato['ocupacion'],
                $dato['vivefamilia']
            ));
        }
        /*} catch ( PDOException $e) {
            echo "Error: " . $e->getMessage;
            return false;
        }*/
    }
}