<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";
require_once __DIR__ . "/DocumentoModel.php";

class PostulanteModel
{
    private $documentosModel;

    public function __construct()
    {
        $this->documentosModel = new DocumentoModel();
    }

    public function obtenerHistorialPostulantes()
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT 
                p.registro, 
                p.nombres, 
                p.documento,
                gf.descripcion AS faseDescripcion,
                gf.descripcion AS cargoDescripcion,
                gr.descripcion AS responsable,
                p.clave,
                l.cdespry AS nombreProyecto
                FROM postulante p
                LEFT JOIN general gf ON gf.cod = p.fase AND gf.clase = '09'
                LEFT JOIN general gc ON gc.cod = p.cargo AND gc.clase = '01'
                LEFT JOIN general gr ON gr.cod = p.responsable AND gr.clase = '08'
                LEFT JOIN logistica.tb_proyecto1 l ON l.ncodpry = p.proyecto
                ORDER BY p.registro DESC
                LIMIT 30;";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerPostulantePorId($idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT p.*,
                    gf.descripcion AS faseDescripcion,
                    gc.descripcion AS cargoDescripcion,
                    gr.descripcion AS responsable,
                    l.cdespry AS nombreProyecto
                    FROM postulante p
                    LEFT JOIN general gf ON gf.cod = p.fase AND gf.clase = '09'
                    LEFT JOIN general gc ON gc.cod = p.cargo AND gc.clase = '01'
                    LEFT JOIN general gr ON gr.cod = p.responsable AND gr.clase = '08'
                    LEFT JOIN logistica.tb_proyecto1 l ON l.ncodpry = p.proyecto
                    WHERE p.idreg=:idreg";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                "idreg" => $idPostulante
            ]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function buscarDocumento($documento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT idreg, estadocs 
                    FROM postulante 
                    WHERE documento =? and registrado > 1 
                        AND registro BETWEEN DATE_SUB(NOW(), INTERVAL 4 MONTH) AND NOW() 
                    order by registro desc limit 1";

            $statement = $pdo->prepare($sql);
            $statement->execute([$documento]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error al obtener los datos"];
        }
    }

    public function buscarReciente($documento)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $data = [];
            $sql = "SELECT idreg, estadocs 
                    FROM postulante 
                    WHERE documento =? and registrado > 1 
                        AND registro BETWEEN DATE_SUB(NOW(), INTERVAL 4 MONTH) AND NOW() 
                    order by registro desc limit 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$documento]);
            $result = $stmt->fetchAll();
            if (count($result) > 0) {
                $data[] = array(
                    "codigo" => $result[0]['idreg'],
                    "estado" => $result[0]['estadocs']
                );
            }
            return $data;
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function insertarPostulante($id, $datos, $registrado, $clave, $estadoCS, $enviado, $responsable, $proyecto)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "INSERT INTO postulante 
                    SET idreg=?, nombres=?, registrado=?, correo=?, fase=?, 
                        visto=?, clave=?, celular=?, cargo=?, estadocs=?, 
                        avance=?, enviado=?, responsable=?, proyecto=?, documento=?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $id,
                $datos->nombre,
                $registrado,
                $datos->correo,
                $datos->codfase,
                0,
                $clave,
                $datos->celular,
                $datos->codcargo,
                $estadoCS,
                0,
                $enviado,
                $responsable,
                $proyecto,
                $datos->documento
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error insertando postulante: " . $e->getMessage());
            return false;
        }
    }

    function insertarPorstulanteDesdeStatus($id, $preseleccionado, $requerimiento, $registrado, $pass, $doc, $enviado, $idResponsable)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "INSERT INTO postulante 
                    SET idreg=:idreg, nombres=:nombres, registrado=:registrado, correo=:correo, fase=:fase, 
                        visto=:visto, clave=:clave, celular=:celular, cargo=:cargo, estadocs=:estadocs, 
                        avance=:avance, enviado=:enviado, responsable=:responsable, proyecto=:proyecto, documento=:documento";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "idreg" => $id,
                "nombres" => $preseleccionado["apellidos_nombres"],
                "registrado" => $registrado,
                "correo" => $preseleccionado["email"],
                "fase" => $requerimiento["id_fase"],
                "visto" => 0,
                "clave" => $pass,
                "celular" => $preseleccionado["telefono_1"],
                "cargo" => $requerimiento["id_cargo"],
                "estadocs" => $doc,
                "avance" => 0,
                "enviado" => $enviado,
                "responsable" => $idResponsable,
                "proyecto" => $requerimiento["id_proyecto"],
                "documento" => $preseleccionado["documento"]
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error insertando postulante: " . $e->getMessage());
            return false;
        }
    }

    public function marcarCorreoEnviado($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "UPDATE postulante SET enviado = 1 WHERE idreg = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["id" => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error actualizando estado enviado: " . $e->getMessage());
            return false;
        }
    }


    public function consultarInvitacion($clave)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                    postulante.idreg,
                    postulante.estadocs,
                    postulante.registrado,
                    postulante.nombres,
                    postulante.documento
                FROM
                    postulante
                WHERE
                    clave = ?
                LIMIT 1";

            $statement = $pdo->prepare($sql);
            $statement->execute([$clave]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return [
                    "idreg" => $result['idreg'],
                    "registrado" => $result['registrado'],
                    "estado" => $result['estadocs'],
                    "nombres" => $result['nombres'],
                    "documento" => $result['documento'],
                    "existe" => true
                ];
            } else {
                return ["existe" => false];
            }
        } catch (PDOException $th) {
            return [
                "existe" => false,
                "error" => $th->getMessage()
            ];
        }
    }

    public function consultarInvitacionPorIdreg($idreg)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                    postulante.idreg,
                    postulante.estadocs,
                    postulante.registrado,
                    postulante.nombres,
                    postulante.documento
                FROM
                    postulante
                WHERE
                    postulante.idreg = ?
                LIMIT 1";

            $statement = $pdo->prepare($sql);
            $statement->execute([$idreg]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return [
                    "idreg" => $result['idreg'],
                    "registrado" => $result['registrado'],
                    "estado" => $result['estadocs'],
                    "nombres" => $result['nombres'],
                    "documento" => $result['documento'],
                    "existe" => true
                ];
            } else {
                return ["existe" => false];
            }
        } catch (PDOException $th) {
            return [
                "existe" => false,
                "error" => $th->getMessage()
            ];
        }
    }

    /**
     * Obtiene los postulantes cuyos nombres coincidan con un patrón.
     *
     * @param string $contenido Texto de búsqueda con comodines SQL (ej: '%Juan%', 'j%')
     * @return array Retorna un arreglo de postulantes con campos: numero, idreg, documento, nombres, registrado
     * @throws Exception Si ocurre un error en la consulta
     */
    public function obtenerPostulantesPorCaracteres($contenido)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        try {
            $sql = "SELECT
                    LPAD(@rownum := @rownum + 1, 3, '0') AS numero,
                    p.idreg,
                    p.documento,
                    p.nombres,
                    p.registrado
                FROM
                    (SELECT * FROM documentos.postulante 
                     WHERE nombres LIKE ? AND registrado > 8 
                     GROUP BY documento 
                     ORDER BY nombres ASC) AS p,
                    (SELECT @rownum := 0) r";

            $statement = $pdo->prepare($sql);
            $statement->execute([$contenido]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener legajos del postulante: " . $e->getMessage());
        }
    }

    /**
     * Obtiene un folder o varios folders.
     * @param string $id - Puede se el valor del documento o idpostulacion
     * @param string $contenido - Va a poner la condición si es para un documento o idpostulacion
     * @return array
     */
    public function obtenerFolders($id, $contenido, $validacion)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                        p.documento AS postulacionDocumento,
                        p.estadocs,
                        p.registro,
                        p.correo AS postulacionCorreo,
                        p.nombres AS postulacionNombreCompleto,
                        p.celular AS postulacionCelular,
                        p.clave AS postulacionClave,
                        pr.cdespry,
                        l.foto AS legajoFoto,
                        UPPER(l.paterno) AS legajoPaterno,
                        UPPER(l.materno) AS legajoMaterno,
                        UPPER(l.nomficha) AS legajoNombres,
                        l.ndoc AS legajoDocumento,
                        l.correo AS legajoCorreo,
                        l.celular AS legajoCelular,
                        MONTH(p.registro) AS mes,
                        YEAR(p.registro) AS anio,
                        tba.*
                    FROM
                        documentos.postulante p
                    INNER JOIN logistica.tb_proyecto1 pr 
                        ON p.proyecto = pr.ncodpry
                    LEFT JOIN documentos.legajo l 
                        ON l.idficha = (
                            SELECT idficha
                            FROM documentos.legajo
                            WHERE idpostulante = p.idreg
                            ORDER BY idficha DESC
                            LIMIT 1
                        )
                    INNER JOIN tb_adjuntos tba ON p.idreg = tba.idpostulante
                    WHERE
                        $contenido = ?
                        $validacion
                    ORDER BY p.registro DESC";
            $statement = $pdo->prepare($sql);
            $statement->execute([$id]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener folders del postulante: " . $e->getMessage());
        }
    }

    public function obtenerInformacionesDeLasPostulaciones($whereSQL, $parametros)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                        documentos.postulante.idreg,
                        documentos.postulante.nombres,
                        documentos.postulante.apellidos,
                        documentos.postulante.documento,
                        documentos.postulante.registro,
                        documentos.postulante.responsable,
                        documentos.postulante.estadocs,
                        documentos.postulante.correo,
                        documentos.postulante.celular,
                        documentos.postulante.clave,
                        cargos.descripcion AS cargo,
                        fase.descripcion AS fase,
                        documentos.tb_adjuntos.file1,
                        documentos.tb_adjuntos.file2,
                        documentos.tb_adjuntos.file3,
                        documentos.tb_adjuntos.file4,
                        documentos.tb_adjuntos.file5,
                        documentos.tb_adjuntos.file6,
                        documentos.tb_adjuntos.file7,
                        documentos.tb_adjuntos.file8,
                        documentos.tb_adjuntos.file9,
                        documentos.tb_adjuntos.file10,
                        documentos.tb_adjuntos.file11,
                        documentos.tb_adjuntos.file12,
                        documentos.tb_adjuntos.file13,
                        documentos.tb_adjuntos.file14,
                        documentos.tb_adjuntos.file15,
                        documentos.tb_adjuntos.file16,
                        documentos.tb_adjuntos.file17,
                        documentos.tb_adjuntos.file18,
                        documentos.tb_adjuntos.file19,
                        documentos.tb_adjuntos.file20,
                        documentos.tb_adjuntos.file21,
                        documentos.tb_adjuntos.file22,
                        documentos.tb_adjuntos.file23,
                        documentos.tb_adjuntos.file24,
                        documentos.tb_adjuntos.file25,
                        documentos.tb_adjuntos.file26,
                        documentos.tb_adjuntos.file27,
                        documentos.tb_adjuntos.freg,
                        logistica.tb_proyecto1.cdespry AS proyecto,
                        CASE
                            WHEN documentos.postulante.proyecto = 2 THEN 17
                            WHEN documentos.postulante.proyecto = 12 THEN 17
                            WHEN documentos.postulante.proyecto = 13 THEN 17
                            WHEN documentos.postulante.proyecto = 14 THEN 17
                            ELSE 18
                        END AS regimen
                    FROM
                        documentos.postulante
                        INNER JOIN documentos.general AS cargos ON postulante.cargo = cargos.cod
                        INNER JOIN documentos.tb_adjuntos ON postulante.idreg = tb_adjuntos.idpostulante
                        INNER JOIN documentos.general AS fase ON postulante.fase = fase.cod
                        INNER JOIN logistica.tb_proyecto1 ON documentos.postulante.proyecto = logistica.tb_proyecto1.ncodpry 
                    WHERE
                        cargos.clase = '01' 
                        AND fase.clase = '09'
                        AND $whereSQL 
                    GROUP BY
                        documentos.postulante.idreg 
                    ORDER BY
                        documentos.postulante.registro DESC";
            // LIMIT 100";
            $statement = $pdo->prepare($sql);
            $statement->execute($parametros);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener información de los postulantes" . $e->getMessage());
        }
    }

    public function obtenerFolder($idreg)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                        p.documento,
                        p.estadocs,
                        pr.cdespry,
                        l.foto AS legajoFoto,
                        UPPER(l.paterno) AS legajoPaterno,
                        UPPER(l.materno) AS legajoMaterno,
                        UPPER(l.nomficha) AS legajoNombres,
                        l.ndoc AS legajoDocumento,
                        l.correo AS legajoCorreo,
                        l.celular AS legajoCelular,
                        MONTH(p.registro) AS mes,
                        YEAR(p.registro) AS anio,
                        tba.*
                    FROM
                        documentos.postulante p
                    INNER JOIN logistica.tb_proyecto1 pr 
                        ON p.proyecto = pr.ncodpry
                    LEFT JOIN documentos.legajo l 
                        ON l.idficha = (
                            SELECT idficha
                            FROM documentos.legajo
                            WHERE idpostulante = p.idreg
                            ORDER BY idficha DESC
                            LIMIT 1
                        )
                    INNER JOIN tb_adjuntos tba ON p.idreg = tba.idpostulante
                    WHERE
                        p.idreg = ?
                        AND p.registrado < 8;";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idreg]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el folder" . $e->getMessage());
        }
    }

    public function actualizarEstadoDocumento($idreg, $posicion, $nuevoEstado)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        try {
            $sql = "UPDATE documentos.postulante
                SET estadocs = CONCAT(
                    SUBSTRING(estadocs, 1, :antes),
                    :nuevoEstado,
                    SUBSTRING(estadocs, :despues)
                )
                WHERE idreg = :idreg";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'antes' => $posicion - 1,
                'nuevoEstado' => $nuevoEstado,
                'despues' => $posicion + 1,
                'idreg' => $idreg
            ]);

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el estado del documento: " . $e->getMessage());
        }
    }

    public function autorizarPostulante($idreg)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        try {
            $ret = false;
            $sql = "UPDATE documentos.postulante SET registrado = ? WHERE idreg = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([2, $idreg]);
            $rowAffect = $statement->rowCount();

            if ($rowAffect > 0) {
                $ret = true;
            }

            return $ret;
        } catch (PDOException $e) {
            throw new Exception("Error al autorizar postulante: " . $e->getMessage());
        }
    }

    public function cerrarInscripcionPostulante($idreg)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        try {
            $ret = false;
            $sql = "UPDATE documentos.postulante SET registrado = ? WHERE idreg = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([9, $idreg]);
            $rowAffect = $statement->rowCount();

            if ($rowAffect > 0) {
                $ret = true;
            }

            return $ret;
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar estado especial: " . $e->getMessage());
        }
    }

    public function buscarPostulantes($nombres, $cargo, $fase, $proyecto)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        $nombres = $nombres === "-1" ? "%%" : "%$nombres%";
        $cargo = $cargo === "-1" ? "%%" : "%$cargo%";
        $fase = $fase === "-1" ? "%%" : "%$fase%";
        $proyecto = $proyecto === "-1" ? "%%" : "%$proyecto%";

        $sql = "SELECT
                    p.nombres,
                    p.documento,
                    p.correo,
                    p.celular,
                    p.enviado,
                    c.descripcion AS cargo,
                    f.descripcion AS fase,
                    pr.cdespry,
                    p.cargo AS codcargo,
                    p.fase AS codfase,
                    p.proyecto AS codproyecto,
                    p.idreg 
                FROM documentos.postulante p
                INNER JOIN documentos.general c ON p.cargo = c.cod AND c.clase = '01'
                INNER JOIN documentos.general f ON p.fase = f.cod AND f.clase = '09'
                INNER JOIN logistica.tb_proyecto1 pr ON p.proyecto = pr.ncodpry
                WHERE 
                    p.nombres LIKE ? AND
                    p.cargo LIKE ? AND
                    p.fase LIKE ? AND
                    p.proyecto LIKE ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombres, $cargo, $fase, $proyecto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
