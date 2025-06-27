<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class PreseleccionadoModel
{
    public function __construct()
    {
    }

    public function obtenerPorDocumento($documento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT p.* FROM preseleccionado p WHERE p.documento=:documento";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":documento" => $documento
            ]);

            // if ($statement->rowCount() > 0) {
            //     $preseleccionado = $statement->fetch(PDO::FETCH_ASSOC);

            //     return ["exitoso" => true, "preseleccionado" => $preseleccionado];
            // }

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["exitoso" => false];
        }
    }

    public function obtenerPorIdPreseleccionado($id_preseleccionado)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT p.* FROM preseleccionado p WHERE p.id_preseleccionado=:id_preseleccionado";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_preseleccionado" => $id_preseleccionado
            ]);

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["exitoso" => false];
        }
    }

    public function obtenerCandidatosPorRequerimiento($idRequerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT p.* 
                        FROM 
                        preseleccionado_requerimiento pr
                        INNER JOIN preseleccionado p ON p.id_preseleccionado = pr.id_preseleccionado
                        WHERE id_reque_proy=:id_reque_proy";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_reque_proy" => $idRequerimiento
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function insertarCandidato($candidato)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        try {
            $sqlVerificar = "SELECT COUNT(*) FROM preseleccionado WHERE documento = :documento";
            $stmtVerificar = $pdo->prepare($sqlVerificar);
            $stmtVerificar->execute([':documento' => $candidato["documento"]]);
            $existe = $stmtVerificar->fetchColumn();

            if ($existe > 0) {
                error_log("Ya existe un candidato con el DNI: " . $candidato["documento"]);
                return false;
            }

            $sql = "INSERT INTO preseleccionado(
                    id_preseleccionado,
                    apellidos_nombres,
                    documento,
                    fecha_nacimiento,
                    edad,
                    exactian,
                    fecha_ingreso_ultimo_proyecto,
                    fecha_cese_ultimo_proyecto,
                    nombre_ultimo_proyecto,
                    telefono_1,
                    telefono_2,
                    email,
                    departamento_residencia
                ) VALUES(
                    :id_preseleccionado,
                    :apellidos_nombres,
                    :documento,
                    :fecha_nacimiento,
                    :edad,
                    :exactian,
                    :fecha_ingreso_ultimo_proyecto,
                    :fecha_cese_ultimo_proyecto,
                    :nombre_ultimo_proyecto,
                    :telefono_1,
                    :telefono_2,
                    :email,
                    :departamento_residencia
                )";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_preseleccionado" => $candidato["id_preseleccionado"],
                ":apellidos_nombres" => $candidato["apellidos_nombres"],
                ":documento" => $candidato["documento"],
                ":fecha_nacimiento" => $candidato["fecha_nacimiento"],
                ":edad" => $candidato["edad"],
                ":exactian" => $candidato["exactian"],
                ":fecha_ingreso_ultimo_proyecto" => $candidato["fecha_ingreso_ultimo_proyecto"],
                ":fecha_cese_ultimo_proyecto" => $candidato["fecha_cese_ultimo_proyecto"],
                ":nombre_ultimo_proyecto" => $candidato["nombre_ultimo_proyecto"],
                ":telefono_1" => $candidato["telefono_1"],
                ":telefono_2" => $candidato["telefono_2"],
                ":email" => $candidato["email"],
                ":departamento_residencia" => $candidato["departamento_residencia"]
            ]);

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al insertar candidato: " . $e->getMessage());
            return false;
        }
    }


    public function insertarCandidatoProyecto($idProyecto, $idCandidato)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $id_prese_req = uniqid("PRE_RE");
            $sql = "INSERT INTO preseleccionado_requerimiento (
                        id_prese_reque,
                        id_reque_proy,
                        id_preseleccionado
                    ) VALUES(
                        :id_prese_reque,
                        :id_reque_proy,
                        :id_preseleccionado
                    )";
            $statement = $pdo->prepare($sql);

            $statement->execute([
                ":id_prese_reque" => $id_prese_req,
                ":id_reque_proy" => $idProyecto,
                ":id_preseleccionado" => $idCandidato
            ]);
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al asignar candidato a proyecto: " . $e->getMessage());
            return false;
        }
    }

    public function contarCandidatosCubiertosPorRequerimiento($idRequerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT COUNT(*) FROM preseleccionado_requerimiento WHERE id_reque_proy = :id_reque_proy";
            $statement = $pdo->prepare($sql);

            $statement->execute([
                ":id_reque_proy" => $idRequerimiento
            ]);
            $cantidad = $statement->fetchColumn();
            return $cantidad;
        } catch (PDOException $e) {
            error_log("Error al contar candidatos por requerimiento: " . $e->getMessage());
            return 0;
        }
    }

    public function obtenerCurCertPreseleccionado($idPreseleccionado, $tipo)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT pcc.*, cc.nombre
                    FROM preseleccionado_curso_certificacion AS pcc
                    LEFT JOIN curso_certificacion AS cc 
                    ON cc.id_curso_certificacion = pcc.id_curs_certi
                    WHERE pcc.id_preseleccionado = :id_preseleccionado AND cc.tipo=:tipo
                    ORDER BY pcc.fecha_registro DESC";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_preseleccionado" => $idPreseleccionado,
                ":tipo" => $tipo,
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerUnicoCurCertPreseleccionado($idPreseCursCerti)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT pcc.*, cc.nombre
                    FROM preseleccionado_curso_certificacion AS pcc
                    LEFT JOIN curso_certificacion AS cc 
                    ON cc.id_curso_certificacion = pcc.id_curs_certi
                    WHERE pcc.id_prese_curs_certi = :id_prese_curs_certi";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_prese_curs_certi" => $idPreseCursCerti
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }


    public function guardarCurCertPreseleccionado($pre_cur_cert)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
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
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizarCurCertPreseleccionado($pre_cur_cert)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "UPDATE preseleccionado_curso_certificacion
                        SET fecha_registro=null, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin WHERE id_prese_curs_certi = :id_prese_curs_certi";
            $statement = $pdo->prepare($sql);

            $statement->execute([
                ":id_prese_curs_certi" => $pre_cur_cert["id_prese_curs_certi"],
                ":fecha_inicio" => $pre_cur_cert["fecha_inicio"],
                ":fecha_fin" => $pre_cur_cert["fecha_fin"]
            ]);

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
