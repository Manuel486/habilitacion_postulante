<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class CursoCertificacionModel
{

    public function __construct()
    {
    }

    public function insertarCursoCertificacion($curso_certificacion)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $pdo = ConexionDocumentos::getInstancia()->getConexion();
            $sql = "INSERT INTO(
                        id_curso_certificacion,
                        nombre,
                        tipo,
                        duracion,
                        unidad_duracion
                    ) VALUES(
                        :id_curso_certificacion,
                        :nombre,
                        :tipo,
                        :duracion,
                        :unidad_duracion
                    )";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_curso_certificacion" => $curso_certificacion["id_curso_certificacion"],
                ":nombre" => $curso_certificacion["nombre"],
                ":tipo" => $curso_certificacion["tipo"],
                ":duracion" => $curso_certificacion["duracion"],
                ":unidad_duracion" => $curso_certificacion["unidad_duracion"]
            ]);
            return $statement->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerCursosCertificaciones()
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT * FROM curso_certificacion";

            $statement = $pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerCursoCertPorId($id_curso_certificacion){
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT * FROM curso_certificacion WHERE id_curso_certificacion=:id_curso_certificacion";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_curso_certificacion" => $id_curso_certificacion
            ]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

   
}