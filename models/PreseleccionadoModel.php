<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class PreseleccionadoModel
{
    public function __construct()
    {
    }

    public function obtenerPreseleccionados($whereIdRequerimiento,$executeRequerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sqlCols = "
                SELECT GROUP_CONCAT(DISTINCT
                    CONCAT(
                        'MAX(CASE WHEN cc.nombre = ''',
                        cc.nombre,
                        ''' THEN DATE_FORMAT(pcc.fecha_inicio, \"%d-%m-%Y\") END) AS `',
                        cc.nombre,
                        '`'
                    )
                ) AS columnas
                FROM curso_certificacion cc";

            $stmtCols = $pdo->query($sqlCols);
            $columnasCursos = $stmtCols->fetchColumn();

            $columnasCursos = $columnasCursos ?: "";

            $sql = "
            SELECT
                rp.id_requerimiento AS 'Id del requerimiento',
                DATE_FORMAT(rp.fecha_requerimiento, \"%d-%m-%Y\") AS 'Fecha requerimiento',
                rp.numero_requerimiento AS 'Numero del requerimiento',
                rp.tipo_requerimiento AS 'Tipo de requerimiento',
                gf.descripcion AS 'Fase',
                gc.descripcion AS 'Cargo',
                rp.cantidad AS 'Cantidad',
                rp.regimen AS 'Regimen',
                p.apellidos_nombres AS 'Nombre completo',
                p.documento AS 'Documento',
                p.fecha_nacimiento AS 'Fecha de nacimiento',
                p.edad AS 'Edad',
                p.exactian AS 'Exactian',
                p.fecha_ingreso_ultimo_proyecto AS 'Fecha ingreso ultimo proyecto',
                p.fecha_cese_ultimo_proyecto AS 'Fecha cese ultimo proyecto',
                p.nombre_ultimo_proyecto AS 'Nombre ultimo proyecto',
                p.telefono_1 AS 'Telefono 1',
                p.telefono_2 AS 'Telefono 2',
                p.email AS 'Email',
                pr.cuarta_vacuna AS '4ta Vacuna',
                pr.fecha_examen_medico AS 'Fecha examen medico',
                pr.clinica AS 'Clinica',
                pr.resultado AS 'Resultado',
                pr.pase_medico AS 'Pase medico',
                p.departamento_residencia AS 'Departamento residencia',
                pr.pm AS 'PM',
                pr.informe_medico AS 'Informe medico',
                pr.poliza AS 'POLIZA',
                pr.viabilidad AS 'VIABILIDAD',
                pr.observacion AS 'OBSERVACION',
                pr.ingreso_obra AS 'INGRESO A OBRA',
                pr.estado AS 'ESTADO',
                pr.observacion2 AS 'OBSERVACION 2',
                pr.alfa AS 'ALFA',
                pr.viabilidad2 AS 'VIABILIDAD2',
                pr.rrhh AS 'RR.HH'
                " . ($columnasCursos ? ", $columnasCursos" : "") . "
            FROM preseleccionado_requerimiento pr
            LEFT JOIN requerimiento_proyecto rp ON rp.id_requerimiento = pr.id_reque_proy
            LEFT JOIN preseleccionado p ON p.id_preseleccionado = pr.id_preseleccionado
            LEFT JOIN preseleccionado_curso_certificacion pcc ON pcc.id_preseleccionado = p.id_preseleccionado
            LEFT JOIN curso_certificacion cc ON cc.id_curso_certificacion = pcc.id_curs_certi
            LEFT JOIN general gf ON gf.cod = rp.id_fase AND gf.clase='09'
            LEFT JOIN general gc ON gc.cod = rp.id_cargo AND gc.clase='01'
            $whereIdRequerimiento
            GROUP BY rp.id_requerimiento, p.id_preseleccionado";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($executeRequerimiento);
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

    public function obtenerNombreColumnas()
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sqlCols = "
                SELECT GROUP_CONCAT(DISTINCT
                    CONCAT(
                        'MAX(CASE WHEN cc.nombre = ''',
                        cc.nombre,
                        ''' THEN DATE_FORMAT(pcc.fecha_inicio, \"%d-%m-%Y\") END) AS `',
                        cc.nombre,
                        '`'
                    )
                ) AS columnas
                FROM curso_certificacion cc";

            $stmtCols = $pdo->query($sqlCols);
            $columnasCursos = $stmtCols->fetchColumn();

            $columnasCursos = $columnasCursos ?: "";

            $sql = "
            SELECT
                rp.id_requerimiento AS 'Id del requerimiento',
                DATE_FORMAT(rp.fecha_requerimiento, \"%d-%m-%Y\") AS 'Fecha requerimiento',
                rp.numero_requerimiento AS 'Numero del requerimiento',
                rp.tipo_requerimiento AS 'Tipo de requerimiento',
                gf.descripcion AS 'Fase',
                gc.descripcion AS 'Cargo',
                rp.cantidad AS 'Cantidad',
                rp.regimen AS 'Regimen',
                p.apellidos_nombres AS 'Nombre completo',
                p.documento AS 'Documento',
                p.fecha_nacimiento AS 'Fecha de nacimiento',
                p.edad AS 'Edad',
                p.exactian AS 'Exactian',
                p.fecha_ingreso_ultimo_proyecto AS 'Fecha ingreso ultimo proyecto',
                p.fecha_cese_ultimo_proyecto AS 'Fecha cese ultimo proyecto',
                p.nombre_ultimo_proyecto AS 'Nombre ultimo proyecto',
                p.telefono_1 AS 'Telefono 1',
                p.telefono_2 AS 'Telefono 2',
                p.email AS 'Email',
                pr.cuarta_vacuna AS '4ta Vacuna',
                pr.fecha_examen_medico AS 'Fecha examen medico',
                pr.clinica AS 'Clinica',
                pr.resultado AS 'Resultado',
                pr.pase_medico AS 'Pase medico',
                p.departamento_residencia AS 'Departamento residencia',
                pr.pm AS 'PM',
                pr.informe_medico AS 'Informe medico',
                pr.poliza AS 'POLIZA',
                pr.viabilidad AS 'VIABILIDAD',
                pr.observacion AS 'OBSERVACION',
                pr.ingreso_obra AS 'INGRESO A OBRA',
                pr.estado AS 'ESTADO',
                pr.observacion2 AS 'OBSERVACION 2',
                pr.alfa AS 'ALFA',
                pr.viabilidad2 AS 'VIABILIDAD2',
                pr.rrhh AS 'RR.HH'
                " . ($columnasCursos ? ", $columnasCursos" : "") . "
            FROM preseleccionado_requerimiento pr
            LEFT JOIN requerimiento_proyecto rp ON rp.id_requerimiento = pr.id_reque_proy
            LEFT JOIN preseleccionado p ON p.id_preseleccionado = pr.id_preseleccionado
            LEFT JOIN preseleccionado_curso_certificacion pcc ON pcc.id_preseleccionado = p.id_preseleccionado
            LEFT JOIN curso_certificacion cc ON cc.id_curso_certificacion = pcc.id_curs_certi
            LEFT JOIN general gf ON gf.cod = rp.id_fase AND gf.clase='09'
            LEFT JOIN general gc ON gc.cod = rp.id_cargo AND gc.clase='01'
            GROUP BY rp.id_requerimiento, p.id_preseleccionado
            LIMIT 0";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $columnas = [];
            $columnCount = $stmt->columnCount();

            for ($i = 0; $i < $columnCount; $i++) {
                $meta = $stmt->getColumnMeta($i);
                $columnas[] = $meta['name'];              
            }

            return $columnas;
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
                "documento" => $documento
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
                "id_preseleccionado" => $id_preseleccionado,
                "id_requerimiento" => $id_requerimiento
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
                "id_reque_proy" => $idRequerimiento
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
                "id_preseleccionado" => $preseleccionado["id_preseleccionado"],
                "apellidos_nombres" => $preseleccionado["apellidos_nombres"],
                "documento" => $preseleccionado["documento"],
                "fecha_nacimiento" => $preseleccionado["fecha_nacimiento"],
                "edad" => $preseleccionado["edad"],
                "exactian" => $preseleccionado["exactian"] ?? "",
                "fecha_ingreso_ultimo_proyecto" => $preseleccionado["fecha_ingreso_ultimo_proyecto"] ?? "",
                "fecha_cese_ultimo_proyecto" => $preseleccionado["fecha_cese_ultimo_proyecto"] ?? "",
                "nombre_ultimo_proyecto" => $preseleccionado["nombre_ultimo_proyecto"] ?? "",
                "telefono_1" => $preseleccionado["telefono_1"] ?? "",
                "telefono_2" => $preseleccionado["telefono_2"] ?? "",
                "email" => $preseleccionado["email"],
                "departamento_residencia" => $preseleccionado["departamento_residencia"] ?? ""
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
                "id_preseleccionado" => $preseleccionado["id_preseleccionado"],
                "apellidos_nombres" => $preseleccionado["apellidos_nombres"],
                "documento" => $preseleccionado["documento"],
                "fecha_nacimiento" => $preseleccionado["fecha_nacimiento"],
                "edad" => $preseleccionado["edad"],
                "exactian" => $preseleccionado["exactian"] ?? "",
                "fecha_ingreso_ultimo_proyecto" => $preseleccionado["fecha_ingreso_ultimo_proyecto"] ?? "",
                "fecha_cese_ultimo_proyecto" => $preseleccionado["fecha_cese_ultimo_proyecto"] ?? "",
                "nombre_ultimo_proyecto" => $preseleccionado["nombre_ultimo_proyecto"] ?? "",
                "telefono_1" => $preseleccionado["telefono_1"] ?? "",
                "telefono_2" => $preseleccionado["telefono_2"] ?? "",
                "email" => $preseleccionado["email"],
                "departamento_residencia" => $preseleccionado["departamento_residencia"] ?? ""
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
                "id_preseleccionado" => $preseleccionado_requerimiento["id_preseleccionado"],
                "id_reque_proy" => $preseleccionado_requerimiento["id_reque_proy"],
                "poliza" => $preseleccionado_requerimiento["poliza"] ?? "",
                "viabilidad" => $preseleccionado_requerimiento["viabilidad"] ?? "",
                "observacion" => $preseleccionado_requerimiento["observacion"] ?? "",
                "ingreso_obra" => $preseleccionado_requerimiento["ingreso_obra"] ?? "",
                "estado" => $preseleccionado_requerimiento["estado"] ?? "",
                "observacion2" => $preseleccionado_requerimiento["observacion2"] ?? "",
                "alfa" => $preseleccionado_requerimiento["alfa"] ?? "",
                "viabilidad2" => $preseleccionado_requerimiento["viabilidad2"] ?? "",
                "rrhh" => $preseleccionado_requerimiento["rrhh"] ?? ""
            ]);

            return $success;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizarPreseleccionadoRequerimientoInfMedica($informacion_medica)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "UPDATE preseleccionado_requerimiento SET 
                        cuarta_vacuna=:cuarta_vacuna,
                        fecha_examen_medico=:fecha_examen_medico,
                        clinica=:clinica,
                        resultado=:resultado,
                        pase_medico=:pase_medico,
                        pm=:pm,
                        informe_medico=:informe_medico
                    WHERE id_preseleccionado=:id_preseleccionado
                    AND id_reque_proy=:id_reque_proy";

            $statement = $pdo->prepare($sql);

            $success = $statement->execute([
                "id_preseleccionado" => $informacion_medica["id_preseleccionado"],
                "id_reque_proy" => $informacion_medica["id_reque_proy"],
                "cuarta_vacuna" => $informacion_medica["cuarta_vacuna"] ?? "",
                "fecha_examen_medico" => $informacion_medica["fecha_examen_medico"] ?? "",
                "clinica" => $informacion_medica["clinica"] ?? "",
                "resultado" => $informacion_medica["resultado"] ?? "",
                "pase_medico" => $informacion_medica["pase_medico"] ?? "",
                "pm" => $informacion_medica["pm"] ?? "",
                "informe_medico" => $informacion_medica["informe_medico"] ?? "",
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

    public function contarPreseleccionadosCubiertosPorRequerimiento($idRequerimiento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT COUNT(*) FROM preseleccionado_requerimiento WHERE id_reque_proy = :id_reque_proy";
            $statement = $pdo->prepare($sql);

            $statement->execute([
                "id_reque_proy" => $idRequerimiento
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
                "id_preseleccionado" => $idPreseleccionado,
                "tipo" => $tipo,
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
                "id_preseleccionado" => $idPreseleccionado,
                "id_reque_proy" => $idRequerimiento,
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
                "id_preseleccionado" => $idPreseleccionado
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
                "id_prese_curs_certi" => $idPreseCursCerti
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
                "id_prese_curs_certi" => $pre_cur_cert["id_prese_curs_certi"],
                "id_preseleccionado" => $pre_cur_cert["id_preseleccionado"],
                "id_curs_certi" => $pre_cur_cert["id_curs_certi"],
                "fecha_inicio" => $pre_cur_cert["fecha_inicio"],
                "fecha_fin" => $pre_cur_cert["fecha_fin"]
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
                "id_prese_curs_certi" => $pre_cur_cert["id_prese_curs_certi"],
                "fecha_inicio" => $pre_cur_cert["fecha_inicio"],
                "fecha_fin" => $pre_cur_cert["fecha_fin"]
            ]);

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
