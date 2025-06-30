<?php

require_once DATABASE_PATH."/conexionDocumentos.php";

class PreseleCursCertModel{ 


    public function __construct(){}


    public function insertarPreCurCert($pre_cur_cert){
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try{
            $sql = "INSERT INTO preseleccionado_curso_certificacion(
                        id_prese_curs_certi,
                        id_preseleccionado,
                        id_curs_certi,
                        fecha_inicio,
                        fecha_fin
                    ) VALUES(
                        :id_prese_curs_certi,
                        :id_preseleccionado,
                        :id_curs_certi,
                        :fecha_inicio,
                        :fecha_fin
                    )";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_prese_curs_certi" => $pre_cur_cert["id_prese_curs_certi"],
                ":id_preseleccionado" => $pre_cur_cert["id_preseleccionado"],
                ":id_curs_certi" => $pre_cur_cert["id_curs_certi"],
                ":fecha_inicio" => $pre_cur_cert["fecha_inicio"],
                ":fecha_fin" => $pre_cur_cert["fecha_fin"]
            ]);

            return $statement->rowCount() > 0;
        }catch(Exception $e){
            error_log("Error al insertar la relacion de preseleccionado y curso-certificacion: " . $e->getMessage());
            return false;
        }
    }
}