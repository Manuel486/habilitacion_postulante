<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class PreseleccionadoModel
{
    public function __construct()
    {
    }

    public function obtenerPreseleccionados($idRequerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            // Paso 1: Generar columnas dinámicas de cursos (si existen)
            $sqlCols = "
            SELECT GROUP_CONCAT(DISTINCT
                CONCAT(
                    'MAX(CASE WHEN cc.nombre = ''',
                    cc.nombre,
                    ''' THEN DATE_FORMAT(pcc.fecha_inicio, \"%Y-%m-%d\") END) AS `',
                    cc.nombre,
                    '`'
                )
            ) AS columnas
            FROM curso_certificacion cc
        ";

            $stmtCols = $pdo->query($sqlCols);
            $columnasCursos = $stmtCols->fetchColumn();

            // Si no hay cursos, las columnas serán vacías
            $columnasCursos = $columnasCursos ?: ""; // Asigna cadena vacía si no hay resultados

            // Paso 2: Armar la consulta base + columnas dinámicas si existen
            $sql = "
            SELECT
                rp.id_requerimiento,
                rp.fecha_requerimiento,
                rp.numero_requerimiento,
                rp.tipo_requerimiento,
                rp.id_fase,
                rp.id_cargo,
                rp.cantidad,
                rp.regimen,
                p.apellidos_nombres,
                p.documento,
                p.fecha_nacimiento,
                p.edad,
                p.exactian,
                p.fecha_ingreso_ultimo_proyecto,
                p.fecha_cese_ultimo_proyecto,
                p.nombre_ultimo_proyecto,
                p.telefono_1,
                p.telefono_2,
                p.email
                " . ($columnasCursos ? ", $columnasCursos" : "") . "
            FROM preseleccionado_requerimiento pr
            LEFT JOIN requerimiento_proyecto rp ON rp.id_requerimiento = pr.id_reque_proy
            LEFT JOIN preseleccionado p ON p.id_preseleccionado = pr.id_preseleccionado
            LEFT JOIN preseleccionado_curso_certificacion pcc ON pcc.id_preseleccionado = p.id_preseleccionado
            LEFT JOIN curso_certificacion cc ON cc.id_curso_certificacion = pcc.id_curs_certi
            WHERE pr.id_reque_proy = :id_requerimiento
            GROUP BY rp.id_requerimiento, p.id_preseleccionado
        ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "id_requerimiento" => $idRequerimiento
            ]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($resultados)) {
                echo "No hay datos para exportar.";
            }

            return $resultados;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function obtenerPorDocumento($documento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT * FROM preseleccionado WHERE documento = :documento";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":documento" => $documento
            ]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function yaEstaAsociadoAlRequerimiento($id_preseleccionado, $id_requerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT 1 FROM preseleccionado_requerimiento 
                WHERE id_preseleccionado = :id_preseleccionado 
                AND id_reque_proy = :id_requerimiento";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_preseleccionado" => $id_preseleccionado,
                ":id_requerimiento" => $id_requerimiento
            ]);
            return $statement->fetchColumn() !== false;
        } catch (PDOException $e) {
            return false;
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

    public function obtenerPreseleccionadosPorRequerimiento($idRequerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT 
                    p.*,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM preseleccionado_curso_certificacion pcc 
                            WHERE pcc.id_preseleccionado = p.id_preseleccionado
                            AND pcc.fecha_fin < CURDATE()
                        ) THEN 1 ELSE 0 
                    END AS tiene_caduco,
                    
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM preseleccionado_curso_certificacion pcc 
                            WHERE pcc.id_preseleccionado = p.id_preseleccionado
                            AND pcc.fecha_fin >= CURDATE()
                            AND pcc.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                        ) THEN 1 ELSE 0 
                    END AS tiene_cerca,
                    
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM preseleccionado_curso_certificacion pcc 
                            WHERE pcc.id_preseleccionado = p.id_preseleccionado
                            AND pcc.fecha_fin > DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                        ) THEN 1 ELSE 0 
                    END AS tiene_vigente

                FROM 
                    preseleccionado_requerimiento pr
                INNER JOIN 
                    preseleccionado p ON p.id_preseleccionado = pr.id_preseleccionado
                WHERE 
                    pr.id_reque_proy = :id_reque_proy";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_reque_proy" => $idRequerimiento
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function insertarPreseleccionado($preseleccionado)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        try {
            $sqlVerificar = "SELECT COUNT(*) FROM preseleccionado WHERE documento = :documento";
            $stmtVerificar = $pdo->prepare($sqlVerificar);
            $stmtVerificar->execute([':documento' => $preseleccionado["documento"]]);
            $existe = $stmtVerificar->fetchColumn();

            if ($existe > 0) {
                error_log("Ya existe un preseleccionado con el documento: " . $preseleccionado["documento"]);
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
                ":id_preseleccionado" => $preseleccionado["id_preseleccionado"],
                ":apellidos_nombres" => $preseleccionado["apellidos_nombres"],
                ":documento" => $preseleccionado["documento"],
                ":fecha_nacimiento" => $preseleccionado["fecha_nacimiento"],
                ":edad" => $preseleccionado["edad"],
                ":exactian" => $preseleccionado["exactian"] ?? "",
                ":fecha_ingreso_ultimo_proyecto" => $preseleccionado["fecha_ingreso_ultimo_proyecto"] ?? "",
                ":fecha_cese_ultimo_proyecto" => $preseleccionado["fecha_cese_ultimo_proyecto"] ?? "",
                ":nombre_ultimo_proyecto" => $preseleccionado["nombre_ultimo_proyecto"] ?? "",
                ":telefono_1" => $preseleccionado["telefono_1"] ?? "",
                ":telefono_2" => $preseleccionado["telefono_2"] ?? "",
                ":email" => $preseleccionado["email"],
                ":departamento_residencia" => $preseleccionado["departamento_residencia"] ?? ""
            ]);

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al insertar preseleccionado: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarPreseleccionado($preseleccionado)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "UPDATE preseleccionado SET 
                        apellidos_nombres=:apellidos_nombres,
                        documento=:documento,
                        fecha_nacimiento=:fecha_nacimiento,
                        edad=:edad,
                        exactian=:exactian,
                        fecha_ingreso_ultimo_proyecto=:fecha_ingreso_ultimo_proyecto,
                        fecha_cese_ultimo_proyecto=:fecha_cese_ultimo_proyecto,
                        nombre_ultimo_proyecto=:nombre_ultimo_proyecto,
                        telefono_1=:telefono_1,
                        telefono_2=:telefono_2,
                        email=:email,
                        departamento_residencia=:departamento_residencia
                    WHERE id_preseleccionado=:id_preseleccionado";

            $statement = $pdo->prepare($sql);

            $success = $statement->execute([
                ":id_preseleccionado" => $preseleccionado["id_preseleccionado"],
                ":apellidos_nombres" => $preseleccionado["apellidos_nombres"],
                ":documento" => $preseleccionado["documento"],
                ":fecha_nacimiento" => $preseleccionado["fecha_nacimiento"],
                ":edad" => $preseleccionado["edad"],
                ":exactian" => $preseleccionado["exactian"] ?? "",
                ":fecha_ingreso_ultimo_proyecto" => $preseleccionado["fecha_ingreso_ultimo_proyecto"] ?? "",
                ":fecha_cese_ultimo_proyecto" => $preseleccionado["fecha_cese_ultimo_proyecto"] ?? "",
                ":nombre_ultimo_proyecto" => $preseleccionado["nombre_ultimo_proyecto"] ?? "",
                ":telefono_1" => $preseleccionado["telefono_1"] ?? "",
                ":telefono_2" => $preseleccionado["telefono_2"] ?? "",
                ":email" => $preseleccionado["email"],
                ":departamento_residencia" => $preseleccionado["departamento_residencia"] ?? ""
            ]);

            return $success;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizarPreseleccionadoRequerimiento($preseleccionado_requerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "UPDATE preseleccionado_requerimiento SET 
                        poliza=:poliza,
                        viabilidad=:viabilidad,
                        observacion=:observacion,
                        ingreso_obra=:ingreso_obra,
                        estado=:estado,
                        observacion2=:observacion2,
                        alfa=:alfa,
                        viabilidad2=:viabilidad2,
                        rrhh=:rrhh
                    WHERE id_preseleccionado=:id_preseleccionado
                    AND id_reque_proy=:id_reque_proy";

            $statement = $pdo->prepare($sql);

            $success = $statement->execute([
                ":id_preseleccionado" => $preseleccionado_requerimiento["id_preseleccionado"],
                ":id_reque_proy" => $preseleccionado_requerimiento["id_reque_proy"],
                ":poliza" => $preseleccionado_requerimiento["poliza"] ?? "",
                ":viabilidad" => $preseleccionado_requerimiento["viabilidad"] ?? "",
                ":observacion" => $preseleccionado_requerimiento["observacion"] ?? "",
                ":ingreso_obra" => $preseleccionado_requerimiento["ingreso_obra"] ?? "",
                ":estado" => $preseleccionado_requerimiento["estado"] ?? "",
                ":observacion2" => $preseleccionado_requerimiento["observacion2"] ?? "",
                ":alfa" => $preseleccionado_requerimiento["alfa"] ?? "",
                ":viabilidad2" => $preseleccionado_requerimiento["viabilidad2"] ?? "",
                ":rrhh" => $preseleccionado_requerimiento["rrhh"] ?? ""
            ]);

            return $success;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarPreseleccionadoRequerimiento($idRequerimiento, $idPreseleccionado)
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
                "id_prese_reque" => $id_prese_req,
                "id_reque_proy" => $idRequerimiento,
                "id_preseleccionado" => $idPreseleccionado
            ]);
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al asignar preseleccionado a proyecto: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarPreseleccionadoRequerimiento($idRequerimiento, $idPreseleccionado)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "DELETE FROM preseleccionado_requerimiento 
                    WHERE id_preseleccionado=:id_preseleccionado 
                    AND id_reque_proy=:id_reque_proy";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                "id_preseleccionado" => $idPreseleccionado,
                "id_reque_proy" => $idRequerimiento
            ]);

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminarCursCertPreseleccionado($idPreCursCerti)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "DELETE FROM preseleccionado_curso_certificacion 
                    WHERE id_prese_curs_certi=:id_prese_curs_certi";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                "id_prese_curs_certi" => $idPreCursCerti
            ]);

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }



    //     public function eliminarFase($id)
    // {
    //     $pdo = ConexionDocumentos::getInstancia()->getConexion();
    //     try {
    //         $sql = "DELETE FROM general WHERE id = :id AND clase = '09'";
    //         $statement = $pdo->prepare($sql);
    //         $statement->execute([':id' => $id]);

    //         return $statement->rowCount() > 0;
    //     } catch (PDOException $e) {
    //         return false;
    //     }
    // }

    public function contarPreseleccionadosCubiertosPorRequerimiento($idRequerimiento)
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
            error_log("Error al contar preseleccionados por requerimiento: " . $e->getMessage());
            return 0;
        }
    }

    public function obtenerCurCertPreseleccionado($idPreseleccionado, $tipo)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT pcc.*, cc.nombre
                    FROM preseleccionado_curso_certificacion AS pcc
                    LEFT JOIN curso_certificacion AS cc ON cc.id_curso_certificacion = pcc.id_curs_certi
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

    public function obtenerInformacionPreselecRequerimiento($idPreseleccionado, $idRequerimiento){
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT * FROM preseleccionado_requerimiento 
                    WHERE id_preseleccionado=:id_preseleccionado
                    AND id_reque_proy=:id_reque_proy";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_preseleccionado" => $idPreseleccionado,
                ":id_reque_proy" => $idRequerimiento,
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerAlertasCertiCurso($idPreseleccionado){
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try{

            $sql = "SELECT 
                        cc.nombre,
                        pcc.fecha_inicio,
                        pcc.fecha_fin,
                        DATEDIFF(pcc.fecha_fin, CURDATE()) AS dias_restantes,
                        CASE
                            WHEN pcc.fecha_fin < CURDATE() THEN 'caduco'
                            WHEN pcc.fecha_fin >= CURDATE() AND pcc.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'cerca'
                            ELSE 'vigente'
                        END AS estado
                    FROM 
                        preseleccionado_curso_certificacion pcc
                    LEFT JOIN 
                        curso_certificacion AS cc ON cc.id_curso_certificacion = pcc.id_curs_certi
                    WHERE pcc.id_preseleccionado=:id_preseleccionado";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ":id_preseleccionado" => $idPreseleccionado
            ]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
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
