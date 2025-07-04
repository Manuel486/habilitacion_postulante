<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";

class AdjuntosModel
{
    public function __construct() {}

    function insertarCodigoAdjuntos($idreg, $estado)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $id = uniqid();
            $sql = "INSERT tb_adjuntos SET idreg=?,idpostulante=?,estado_documento=?";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($id, $idreg, $estado));
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function updateAdjuntos($id, $newId)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $datos = $this->buscarDatosAdjuntos($id);
            $respuesta = false;
            $codigo = time();

            $sql = "INSERT INTO tb_adjuntos 
                    SET tb_adjuntos.idreg =?,
                        tb_adjuntos.idpostulante=?,
                        tb_adjuntos.file1=?,
                        tb_adjuntos.file2=?,
                        tb_adjuntos.file3=?,
                        tb_adjuntos.file4=?,
                        tb_adjuntos.file5=?,
                        tb_adjuntos.file6=?,
                        tb_adjuntos.file7=?,
                        tb_adjuntos.file8=?,
                        tb_adjuntos.file9=?,
                        tb_adjuntos.file10=?,
                        tb_adjuntos.file11=?,
                        tb_adjuntos.file12=?,
                     
                        tb_adjuntos.file15=?,
                        tb_adjuntos.file16=?,
                        tb_adjuntos.file17=?,
                        tb_adjuntos.file18=?,
                        tb_adjuntos.file19=?,
                        tb_adjuntos.file20=?,
                        tb_adjuntos.file21=?,
                        tb_adjuntos.file22=?";

            $statement = $pdo->prepare($sql);
            $statement->execute(array(
                $codigo,
                $newId,
                $newId . '.pdf',
                $datos[0]['file2'],
                $datos[0]['file3'],
                $datos[0]['file4'],
                $datos[0]['file5'],
                $datos[0]['file6'],
                $datos[0]['file7'],
                $datos[0]['file8'],
                $datos[0]['file9'],
                $datos[0]['file10'],
                $datos[0]['file11'],
                $datos[0]['file12'],

                $datos[0]['file15'],
                $datos[0]['file16'],
                $datos[0]['file17'],
                $datos[0]['file18'],
                $datos[0]['file19'],
                $datos[0]['file20'],
                $datos[0]['file21'],
                $datos[0]['file22']
            ));

            $rowCount = $statement->rowCount();

            if ($rowCount) {
                $respuesta = true;
            }

            return array("respuesta" => $respuesta);
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function buscarDatosAdjuntos($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                         tb_adjuntos.idreg,
                         tb_adjuntos.idpostulante,
                         tb_adjuntos.file1,
                         tb_adjuntos.file2,
                         tb_adjuntos.file3,
                         tb_adjuntos.file4,
                         tb_adjuntos.file5,
                         tb_adjuntos.file6,
                         tb_adjuntos.file7,
                         tb_adjuntos.file8,
                         tb_adjuntos.file9,
                         tb_adjuntos.file10,
                         tb_adjuntos.file11,
                         tb_adjuntos.file12,
                         tb_adjuntos.file14,
                         tb_adjuntos.file13,
                         tb_adjuntos.file15,
                         tb_adjuntos.file16,
                         tb_adjuntos.file17,
                         tb_adjuntos.file18,
                         tb_adjuntos.file19,
                         tb_adjuntos.file20,
                         tb_adjuntos.file21,
                         tb_adjuntos.file22
                     FROM
                         tb_adjuntos 
                     WHERE
                         tb_adjuntos.idpostulante = ? 
                         LIMIT 1";

            $statement = $pdo->prepare($sql);
            $statement->execute(array($id));
            $rowCount = $statement->rowCount();

            if ($rowCount) {
                $respuesta = true;

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

    public function actualizarArchivo($idreg, $numeroArchivo, $contenidoArchivo)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $campo = "file" . $numeroArchivo;
            $sql = "UPDATE tb_adjuntos SET `$campo` = ? WHERE idpostulante = ?";
            $stmt = $pdo->prepare($sql);
            $resultado = $stmt->execute([$contenidoArchivo, $idreg]);

            return $resultado;
        } catch (PDOException $e) {
            echo "Error al actualizar archivo: " . $e->getMessage();
            return false;
        }
    }
}
