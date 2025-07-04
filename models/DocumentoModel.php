<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class DocumentoModel {

    public function __construct() {}

    public function obtenerNombreDeDocumentos(){
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT idfile,descripcion FROM tabla_documentos ORDER BY idfile";

            $statement = $pdo->query($sql);
		    $statement -> execute();
            $resultado    = $statement ->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }

}