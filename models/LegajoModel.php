<?php

require_once DATABASE_PATH . "/conexionDocumentos.php";
require_once __DIR__ . "/DetalleLegajoModel.php";

class LegajoModel
{

    private $detalleLegajoModel;

    public function __construct()
    {
        $this->detalleLegajoModel = new DetalleLegajoModel();
    }

    function nuevaFicha($id, $nuevoCodigo)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $datos = $this->buscarDatosFicha($id);
            $detalles = $this->detalleLegajoModel->buscarDetalles($id);
            $codigo = uniqid("fi");

            $sql = "INSERT INTO documentos.legajo 
                        SET legajo.idficha=?,
                            legajo.idpostulante=?,
                            legajo.foto=?,
                            legajo.paterno=?,
                            legajo.materno=?,
                            legajo.nomficha=?,
                            legajo.edad=?,
                            legajo.tdoc=?,
                            legajo.ndoc=?,
                            legajo.fnac=?,
                            legajo.direcc=?,
                            legajo.ubigeo=?,
                            legajo.celular=?,
                            legajo.telefono=?,
                            legajo.correo=?,
                            legajo.ubigeonac=?,
                            legajo.nacional=?,
                            legajo.banco=?,
                            legajo.cuenta=?,
                            legajo.genero=?,
                            legajo.estado=?,
                            legajo.vivefamilia=?,
                            legajo.vubic=?,
                            legajo.vtenen=?,
                            legajo.vtipo=?,
                            legajo.vmaterial=?,
                            legajo.sagua=?,
                            legajo.sluz=?,
                            legajo.sdesa=?,
                            legajo.scable=?,
                            legajo.sinter=?,
                            legajo.stel=?,
                            legajo.sotro=?,
                            legajo.tipoafiliado=?,
                            legajo.enfermedad=?,
                            legajo.enfermedadtexto=?,
                            legajo.tratamiento=?,
                            legajo.tratamientotexto=?,
                            legajo.familiarcronica=?,
                            legajo.familiartext=?,
                            legajo.familiartratamiento=?,
                            legajo.familiartratamientotexto=?,
                            legajo.familiardrogas=?,
                            legajo.familiardrogastexto=?,
                            legajo.ingresotrabajador=?,
                            legajo.ingresoconyuje=?,
                            legajo.ingresootros=?,
                            legajo.gastopromedio=?,
                            legajo.emergenciacontacto=?,
                            legajo.emergenciaparentesco=?,
                            legajo.emergenciafijo=?,
                            legajo.emergenciacelular=?,
                            legajo.emergenciadireccion=?,
                            legajo.referencia=?,
                            legajo.fechaficha=?,
                            legajo.lat=?,
                            legajo.lon=?,
                            legajo.adjuntocroquis=?,
                            legajo.lugarnacextranj=?,
                            legajo.trabajadoringr=?,
                            legajo.conyujeingr=?,
                            legajo.otrosingr=?,
                            legajo.otrosingrtext=?,
                            legajo.totalaportantes=?,
                            legajo.pension=?";

            $statement = $pdo->prepare($sql);
            $statement->execute(array(
                $codigo,
                $nuevoCodigo,
                $datos[0]['foto'],
                $datos[0]['paterno'],
                $datos[0]['materno'],
                $datos[0]['nomficha'],
                $datos[0]['edad'],
                $datos[0]['tdoc'],
                $datos[0]['ndoc'],
                $datos[0]['fnac'],
                $datos[0]['direcc'],
                $datos[0]['ubigeo'],
                $datos[0]['celular'],
                $datos[0]['telefono'],
                $datos[0]['correo'],
                $datos[0]['ubigeonac'],
                $datos[0]['nacional'],
                $datos[0]['banco'],
                $datos[0]['cuenta'],
                $datos[0]['genero'],
                $datos[0]['estado'],
                $datos[0]['vivefamilia'],
                $datos[0]['vubic'],
                $datos[0]['vtenen'],
                $datos[0]['vtipo'],
                $datos[0]['vmaterial'],
                $datos[0]['sagua'],
                $datos[0]['sluz'],
                $datos[0]['sdesa'],
                $datos[0]['scable'],
                $datos[0]['sinter'],
                $datos[0]['stel'],
                $datos[0]['sotro'],
                $datos[0]['tipoafiliado'],
                $datos[0]['enfermedad'],
                $datos[0]['enfermedadtexto'],
                $datos[0]['tratamiento'],
                $datos[0]['tratamientotexto'],
                $datos[0]['familiarcronica'],
                $datos[0]['familiartext'],
                $datos[0]['familiartratamiento'],
                $datos[0]['familiartratamientotexto'],
                $datos[0]['familiardrogas'],
                $datos[0]['familiardrogastexto'],
                $datos[0]['ingresotrabajador'],
                $datos[0]['ingresoconyuje'],
                $datos[0]['ingresootros'],
                $datos[0]['gastopromedio'],
                $datos[0]['emergenciacontacto'],
                $datos[0]['emergenciaparentesco'],
                $datos[0]['emergenciafijo'],
                $datos[0]['emergenciacelular'],
                $datos[0]['emergenciadireccion'],
                $datos[0]['referencia'],
                $datos[0]['fechaficha'],
                $datos[0]['lat'],
                $datos[0]['lon'],
                $datos[0]['adjuntocroquis'],
                $datos[0]['lugarnacextranj'],
                $datos[0]['trabajadoringr'],
                $datos[0]['conyujeingr'],
                $datos[0]['otrosingr'],
                $datos[0]['otrosingrtext'],
                $datos[0]['totalaportantes'],
                $datos[0]['pension']
            ));

            $rowCount = $statement->rowCount();

            if ($rowCount) {
                $respuesta = true;
                $this->detalleLegajoModel->detallesNuevaFicha($codigo, $detalles, $nuevoCodigo);
            }

            return array("respuesta" => $respuesta);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function buscarDatosFicha($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT
                   legajo.idficha,
                   legajo.idpostulante,
                   legajo.reg,
                   legajo.foto,
                   legajo.paterno,
                   legajo.materno,
                   legajo.nomficha,
                   legajo.edad,
                   legajo.tdoc,
                   legajo.ndoc,
                   legajo.fnac,
                   legajo.direcc,
                   legajo.ubigeo,
                   legajo.celular,
                   legajo.telefono,
                   legajo.correo,
                   legajo.ubigeonac,
                   legajo.nacional,
                   legajo.banco,
                   legajo.cuenta,
                   legajo.genero,
                   legajo.estado,
                   legajo.vivefamilia,
                   legajo.vubic,
                   legajo.vtenen,
                   legajo.vtipo,
                   legajo.vmaterial,
                   legajo.sagua,
                   legajo.sluz,
                   legajo.sdesa,
                   legajo.scable,
                   legajo.sinter,
                   legajo.stel,
                   legajo.sotro,
                   legajo.tipoafiliado,
                   legajo.enfermedad,
                   legajo.enfermedadtexto,
                   legajo.tratamiento,
                   legajo.tratamientotexto,
                   legajo.familiarcronica,
                   legajo.familiartext,
                   legajo.familiartratamiento,
                   legajo.familiartratamientotexto,
                   legajo.familiardrogas,
                   legajo.familiardrogastexto,
                   legajo.ingresotrabajador,
                   legajo.ingresoconyuje,
                   legajo.ingresootros,
                   legajo.gastopromedio,
                   legajo.emergenciacontacto,
                   legajo.emergenciaparentesco,
                   legajo.emergenciafijo,
                   legajo.emergenciacelular,
                   legajo.emergenciadireccion,
                   legajo.referencia,
                   legajo.fechaficha,
                   legajo.lat,
                   legajo.lon,
                   legajo.adjuntocroquis,
                   legajo.lugarnacextranj,
                   legajo.trabajadoringr,
                   legajo.conyujeingr,
                   legajo.otrosingr,
                   legajo.otrosingrtext,
                   legajo.totalaportantes,
                   legajo.pension 
               FROM
                   legajo 
               WHERE
                   legajo.idpostulante = ?
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

    function guardarFicha($ficha, $datosFamiliares, $datosEstudios, $datosExperiencias, $estadodocs, $idpostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $id = uniqid("fi");

            $agua       = isset($ficha['servicio1']) ? 1 : 0;
            $luz        = isset($ficha['servicio2']) ? 1 : 0;
            $desague    = isset($ficha['servicio3']) ? 1 : 0;
            $cable      = isset($ficha['servicio4']) ? 1 : 0;
            $internet   = isset($ficha['servicio5']) ? 1 : 0;
            $telefono   = isset($ficha['servicio6']) ? 1 : 0;
            $otros      = isset($ficha['servicio7']) ? 1 : 0;
            $banco      = isset($ficha['codbanco'])  ? $ficha['codbanco'] : "01";

            $ubigeonac = strlen($ficha['ubignac']) == 6 ? $ficha['ubignac'] : "150130";
            $ubigeodir = strlen($ficha['ubigdir']) == 6 ? $ficha['ubigdir'] : "150130";
            // vivefamilia=?, ,ingresotrabajador=?,ingresoconyuje=?,ingresootros=?,gastopromedio=?,iba debajo antes 
            $sql = "INSERT INTO legajo SET idficha = ?,foto=?,paterno=?,materno=?,nomficha=?,
                                        edad=?,tdoc=?,ndoc=?,fnac=?,direcc=?,
                                        ubigeo=?,celular=?,telefono=?,correo=?,ubigeonac=?,
                                        nacional=?,banco=?,cuenta=?,genero=?,estado=?,pension=?,
                                        vubic=?,vtenen=?,vtipo=?,vmaterial=?,sagua=?,
                                        sluz=?,sdesa=?,scable=?,sinter=?,stel=?,sotro=?,
                                        tipoafiliado=?,enfermedad=?,enfermedadtexto=?,tratamiento=?,tratamientotexto=?,
                                        familiarcronica=?,familiartext=?,familiartratamiento=?,familiartratamientotexto=?,familiardrogas=?,
                                        familiardrogastexto=?,
                                        emergenciacontacto= ?,emergenciaparentesco= ?,emergenciafijo= ?,emergenciacelular= ?,emergenciadireccion= ?,
                                        referencia= ?,fechaficha= ?,idpostulante=?,lat=?,lon=?,lugarnacextranj=?,adjuntocroquis=?,
                                        trabajadoringr=?,conyujeingr=?,otrosingr=?,otrosingrtext=?,totalaportantes=?";
            $statement = $pdo->prepare($sql);
            $statement->execute(array(
                $id,
                // $ficha['ruta_foto'],
                $idpostulante . ".jpg",
                $ficha['apat'],
                $ficha['amat'],
                $ficha['nombreficha'],
                $ficha['edad'],
                $ficha['coddocumento'],
                rtrim($ficha['dnice']),
                $ficha['fnac'],
                $ficha['direccion'],
                $ubigeodir,
                $ficha['celular'],
                $ficha['teleficha'],
                $ficha['correoficha'],
                $ubigeonac,
                $ficha['codpais'],/*$ficha['codbanco']*/
                $banco,
                $ficha['cuenta'],
                $ficha['sexo'],
                $ficha['civil'],
                $ficha['pension'],
                /*$ficha['vivefamilia'],*/
                $ficha['vivienda'],
                $ficha['tenencia'],
                $ficha['tipo'],
                $ficha['material'],
                $agua,
                $luz,
                $desague,
                $cable,
                $internet,
                $telefono,
                $otros,
                $ficha['afiliado'],
                $ficha['enfermedad'],
                $ficha['emfermedadcual'],
                $ficha['tratamiento'],
                $ficha['tratamientocual'],
                $ficha['familiar'],
                $ficha['familiarcual'],
                $ficha['tratfamiliar'],
                $ficha['tratfamiliarcual'],
                $ficha['drogas'],
                $ficha['drogascual'],/*$ficha['sueldoTrabajador'],$ficha['sueldoConyuje'],$ficha['sueldoOtros'],$ficha['gastoFamiliar'],*/
                $ficha['personaEmergencia'],
                $ficha['parentescoEmergencia'],
                $ficha['fijoEmergencia'],
                $ficha['celularEmergencia'],
                $ficha['direccionEmergencia'],
                $ficha['referencia'],
                $ficha['fechaElabora'],
                $idpostulante,
                $ficha['latitud'],
                $ficha['longitud'],
                $ficha['nacimientoext'],
                $ficha['ruta_croquis'],
                $ficha['ingresotrab'],
                $ficha['ingresoconyuje'],
                $ficha['ingresootros'],
                $ficha['aporteOtros'],
                $ficha['gastoFamiliar']
            ));
            $rowaffect = $statement->rowCount($sql);
            $error  = $pdo->errorInfo();

            if ($rowaffect > 0) {
                $this->grabarDatosFamilares($datosFamiliares, $id, $idpostulante);
                $this->grabarEstudios($datosEstudios, $id, $idpostulante);
                $this->grabarExperiencias($datosExperiencias, $id, $idpostulante);

                $estado_postulante = $this->actualizarEstado($idpostulante, $estadodocs);
                $this->actualizarCodigoAdjuntos($idpostulante);

                $json = array(
                    "mensaje" => "Ficha Registrada",
                    "estado" => $estado_postulante,
                    "confirmado" => true
                );

                echo json_encode($json);
            } else {
                echo "Hay un error";
                var_dump($error);
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function grabarDatosFamilares($datosFamiliares, $idFicha, $idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        foreach ($datosFamiliares as $familiar) {
            try {
                $idreg = uniqid();
                $sql = "INSERT INTO detallelegajo SET 
                        idreg = ?, 
                        idficha = ?, 
                        nombre = ?, 
                        parentesco = ?, 
                        dni = ?, 
                        fechanac = ?, 
                        instruccion = ?, 
                        vivefamilia = ?, 
                        ocupacion = ?, 
                        postulante = ?";

                $statement = $pdo->prepare($sql);
                $statement->execute(array(
                    $idreg,
                    $idFicha,
                    $familiar[0], // nombre
                    $familiar[1], // parentesco
                    $familiar[2], // dni
                    $familiar[3], // fecha nacimiento
                    $familiar[4], // instrucción
                    $familiar[5], // vivefamilia
                    $familiar[6], // ocupación
                    $idPostulante
                ));
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        return true;
    }

    function grabarEstudios($datosEstudios, $idFicha, $idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        foreach ($datosEstudios as $estudio) {
            try {
                $idreg = uniqid();
                $sql = "INSERT INTO detestudioslegajo SET 
                        idreg = ?, 
                        idficha = ?, 
                        tipoinstruccion = ?, 
                        institucion = ?, 
                        añoInicio = ?, 
                        añoFin = ?, 
                        carrera = ?, 
                        grado = ?, 
                        postulante = ?";

                $statement = $pdo->prepare($sql);
                $statement->execute(array(
                    $idreg,
                    $idFicha,
                    $estudio[0], // Tipo de instruccion
                    $estudio[1], // Nombre de la institucion
                    $estudio[2], // Año de inicio
                    $estudio[3], // Año de termino
                    $estudio[4], // Carrerra profesional
                    $estudio[5], // Titulo o grado obtenido
                    $idPostulante
                ));
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        return true;
    }

    function grabarExperiencias($datosExperiencias, $idFicha, $idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();

        foreach ($datosExperiencias as $experiencia) {
            try {
                $idreg = uniqid();
                $sql = "INSERT INTO dexperiencialegajo SET 
                        idreg=?,
                        idficha=?,
                        empresa=?,
                        cargo=?,
                        tiempo=?,
                        motivoretiro=?,
                        ultremuneracion=?,
                        nombjefe=?,
                        direccion=?,
                        telefono=?,
                        postulante=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array(
                    $idreg,
                    $idFicha,
                    $experiencia[0], // empresa
                    $experiencia[1], // cargo
                    $experiencia[2], // tiempo
                    $experiencia[3], // motivoretiro
                    $experiencia[4], // ultremuneracion
                    $experiencia[5], // nombjefe
                    $experiencia[6], // direccion
                    $experiencia[7], // telefono
                    $idPostulante
                ));
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        return true;
    }


    function actualizarEstado($idPostulante, $estadodocs)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sw = substr_replace($estadodocs, "1", 0, 1);
            $sql = "UPDATE postulante SET estadocs=? WHERE idreg=?";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($sw, $idPostulante));
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }

        return $sw;
    }

    function actualizarCodigoAdjuntos($idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $idfile =  $idPostulante . ".pdf";
            $sql = "UPDATE tb_adjuntos SET file1=? WHERE idpostulante = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($idfile, $idPostulante));
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function obtenerLegajo($idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $idfile =  $idPostulante . ".pdf";
            $sql = "SELECT * FROM legajo WHERE idpostulante= ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idPostulante]);

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function obtenerDatosFamiliares($idPostulante, $idficha)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $idfile =  $idPostulante . ".pdf";
            $sql = "SELECT * FROM detallelegajo WHERE postulante= ? AND idficha = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idPostulante, $idficha]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function obtenerEstudios($idPostulante, $idficha)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $idfile =  $idPostulante . ".pdf";
            $sql = "SELECT * FROM detestudioslegajo WHERE postulante= ? AND idficha = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idPostulante, $idficha]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function obtenerExperiencias($idPostulante, $idficha)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $idfile =  $idPostulante . ".pdf";
            $sql = "SELECT * FROM dexperiencialegajo WHERE postulante= ? AND idficha = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idPostulante, $idficha]);

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOexception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function actualizarFicha($ficha, $datosFamiliares, $datosEstudios, $datosExperiencias, $estadodocs, $idpostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $agua     = isset($ficha['servicio1']) ? 1 : 0;
            $luz      = isset($ficha['servicio2']) ? 1 : 0;
            $desague  = isset($ficha['servicio3']) ? 1 : 0;
            $cable    = isset($ficha['servicio4']) ? 1 : 0;
            $internet = isset($ficha['servicio5']) ? 1 : 0;
            $telefono = isset($ficha['servicio6']) ? 1 : 0;
            $otros    = isset($ficha['servicio7']) ? 1 : 0;
            $banco    = isset($ficha['codbanco'])  ? $ficha['codbanco'] : "01";

            $ubigeonac = strlen($ficha['ubignac']) == 6 ? $ficha['ubignac'] : "150130";
            $ubigeodir = strlen($ficha['ubigdir']) == 6 ? $ficha['ubigdir'] : "150130";

            $legajoDelPostulante = $this->obtenerFicha($idpostulante);
            $idficha = $legajoDelPostulante["idficha"];

            $sql = "UPDATE legajo SET 
                    foto=?, paterno=?, materno=?, nomficha=?,
                    edad=?, tdoc=?, ndoc=?, fnac=?, direcc=?,
                    ubigeo=?, celular=?, telefono=?, correo=?, ubigeonac=?,
                    nacional=?, banco=?, cuenta=?, genero=?, estado=?, pension=?,
                    vubic=?, vtenen=?, vtipo=?, vmaterial=?, sagua=?,
                    sluz=?, sdesa=?, scable=?, sinter=?, stel=?, sotro=?,
                    tipoafiliado=?, enfermedad=?, enfermedadtexto=?, tratamiento=?, tratamientotexto=?,
                    familiarcronica=?, familiartext=?, familiartratamiento=?, familiartratamientotexto=?, familiardrogas=?,
                    familiardrogastexto=?, emergenciacontacto=?, emergenciaparentesco=?, emergenciafijo=?, emergenciacelular=?, emergenciadireccion=?,
                    referencia=?, fechaficha=?, lat=?, lon=?, lugarnacextranj=?, adjuntocroquis=?,
                    trabajadoringr=?, conyujeingr=?, otrosingr=?, otrosingrtext=?, totalaportantes=?
                WHERE idficha=? AND idpostulante=?";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                // $ficha['ruta_foto'],
                $idpostulante . ".jpg",
                $ficha['apat'],
                $ficha['amat'],
                $ficha['nombreficha'],
                $ficha['edad'],
                $ficha['coddocumento'],
                rtrim($ficha['dnice']),
                $ficha['fnac'],
                $ficha['direccion'],
                $ubigeodir,
                $ficha['celular'],
                $ficha['teleficha'],
                $ficha['correoficha'],
                $ubigeonac,
                $ficha['codpais'],
                $banco,
                $ficha['cuenta'],
                $ficha['sexo'],
                $ficha['civil'],
                $ficha['pension'],
                $ficha['vivienda'],
                $ficha['tenencia'],
                $ficha['tipo'],
                $ficha['material'],
                $agua,
                $luz,
                $desague,
                $cable,
                $internet,
                $telefono,
                $otros,
                $ficha['afiliado'],
                $ficha['enfermedad'],
                $ficha['emfermedadcual'],
                $ficha['tratamiento'],
                $ficha['tratamientocual'],
                $ficha['familiar'],
                $ficha['familiarcual'],
                $ficha['tratfamiliar'],
                $ficha['tratfamiliarcual'],
                $ficha['drogas'],
                $ficha['drogascual'],
                $ficha['personaEmergencia'],
                $ficha['parentescoEmergencia'],
                $ficha['fijoEmergencia'],
                $ficha['celularEmergencia'],
                $ficha['direccionEmergencia'],
                $ficha['referencia'],
                $ficha['fechaElabora'],
                $ficha['latitud'],
                $ficha['longitud'],
                $ficha['nacimientoext'],
                $ficha['ruta_croquis'],
                $ficha['ingresotrab'],
                $ficha['ingresoconyuje'],
                $ficha['ingresootros'],
                $ficha['aporteOtros'],
                $ficha['gastoFamiliar'],
                $idficha,
                $idpostulante
            ]);

            // Limpiar anteriores detalles
            $pdo->prepare("DELETE FROM detallelegajo WHERE idficha = ?")->execute([$idficha]);
            $pdo->prepare("DELETE FROM detestudioslegajo WHERE idficha = ?")->execute([$idficha]);
            $pdo->prepare("DELETE FROM dexperiencialegajo WHERE idficha = ?")->execute([$idficha]);

            // Insertar nuevos detalles
            $this->grabarDatosFamilares($datosFamiliares, $idficha, $idpostulante);
            $this->grabarEstudios($datosEstudios, $idficha, $idpostulante);
            $this->grabarExperiencias($datosExperiencias, $idficha, $idpostulante);

            $estado_postulante = $this->actualizarEstado($idpostulante, $estadodocs);
            $this->actualizarCodigoAdjuntos($idpostulante);

            echo json_encode([
                "mensaje" => "Ficha actualizada",
                "confirmado" => true
            ]);
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }

    public function obtenerFicha($idPostulante)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT * FROM `legajo` WHERE `idpostulante`=? ";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idPostulante]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    public function datosPersonal($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT legajo.idpostulante,
						legajo.reg,
						legajo.foto,
						legajo.paterno,
						legajo.materno,
						legajo.nomficha,
						legajo.edad,
						legajo.tdoc,
						legajo.ndoc,
						legajo.fnac,
						legajo.direcc,
						legajo.celular,
						legajo.telefono,
						legajo.correo,
						legajo.cuenta,
						legajo.genero,
						legajo.estado,
						legajo.pension,
						legajo.vivefamilia,
						legajo.vubic,
						legajo.vtenen,
						legajo.vtipo,
						legajo.vmaterial,
						legajo.sagua,
						legajo.sluz,
						legajo.sdesa,
						legajo.scable,
						legajo.sinter,
						legajo.stel,
						legajo.sotro,
						legajo.tipoafiliado,
						legajo.enfermedad,
						legajo.enfermedadtexto,
						legajo.tratamiento,
						legajo.tratamientotexto,
						legajo.familiarcronica,
						legajo.familiartext,
						legajo.familiartratamiento,
						legajo.familiardrogas,
						legajo.familiardrogastexto,
						legajo.familiartratamientotexto,
						legajo.ingresotrabajador,
						legajo.ingresoconyuje,
						legajo.ingresootros,
						legajo.gastopromedio,
						legajo.emergenciacontacto,
						legajo.emergenciaparentesco,
						legajo.emergenciafijo,
						legajo.emergenciacelular,
						legajo.emergenciadireccion,
						legajo.referencia,
						legajo.fechaficha,
						legajo.lugarnacextranj,
						legajo.trabajadoringr,
						legajo.conyujeingr,
						legajo.otrosingr,
						legajo.otrosingrtext,
                        legajo.nacional,
						legajo.totalaportantes,
						ubig_domicilio.Departamento AS depdom,
						ubig_domicilio.Provincia AS provdom,
						ubig_domicilio.Distrito AS distdom,
						ubig_nac.Departamento AS depnac,
                        ubig_nac.Provincia AS provnac,
                        ubig_nac.Distrito AS distnac,
                        tb_pais.cdespais,
						bancos.descripcion AS banco
					FROM
						legajo
					LEFT JOIN ubigeo AS ubig_domicilio ON legajo.ubigeo = ubig_domicilio.codigo
					LEFT JOIN ubigeo AS ubig_nac ON legajo.ubigeonac = ubig_nac.codigo
					LEFT JOIN tb_pais ON legajo.nacional = tb_pais.ccodpais
					LEFT JOIN general AS bancos ON (CASE WHEN legajo.banco = '' THEN '01' ELSE legajo.banco END) = bancos.cod
					WHERE
						legajo.idpostulante = ?
					AND bancos.clase = '05'";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($id));
            $results     = $statement->fetchAll();
            $rowaffect     = $statement->rowCount($sql);
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }

        return $results;
    }

    public function detallesParentesco($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        $parentesco = ["CONYUJE", "CONVIVIENTE", "HIJOS"];
        try {
            $sql = "SELECT
					detallelegajo.idreg,
					detallelegajo.idficha,
					detallelegajo.postulante,
					detallelegajo.nombre,
					detallelegajo.fechanac,
					detallelegajo.edad,
					detallelegajo.instruccion,
					detallelegajo.ocupacion,
					detallelegajo.reg,
					detallelegajo.parentesco,
					detallelegajo.vivefamilia,
					detallelegajo.dni
				FROM
					detallelegajo
				WHERE
					detallelegajo.postulante = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($id));
            $results     = $statement->fetchAll();
            $rowaffect     = $statement->rowCount($sql);

            $item = [];
            foreach ($results as $rs) {
                $data = array(
                    "nombre" => strtoupper(utf8_decode($rs['nombre'])),
                    "parentesco" => strtoupper(utf8_decode($rs['parentesco'])),
                    "fechanac" => date("d/m/Y", strtotime($rs['fechanac'])),
                    "dni" => $rs['dni'],
                    "instruccion" => strtoupper($rs['instruccion']),
                    "ocupacion" => strtoupper($rs['ocupacion']),
                    "vivefamilia" => $rs['vivefamilia']
                );
                array_push($item, $data);
            }

            return $item;
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }

    public function detallesEstudios($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        $estudios = ["PRIMARIA", "SECUNDARIA", "TECNICA", "UNIVERSITARIA", "OTROS"];

        try {
            $sql = "SELECT
            detestudioslegajo.idreg,
            detestudioslegajo.idficha,
            detestudioslegajo.postulante,
            detestudioslegajo.tipoinstruccion,
            detestudioslegajo.institucion,
            detestudioslegajo.inicio,
            detestudioslegajo.fin,
            detestudioslegajo.añoInicio,
            detestudioslegajo.añoFin,
            detestudioslegajo.carrera,
            detestudioslegajo.grado,
            detestudioslegajo.reg
            FROM detestudioslegajo 
            WHERE detestudioslegajo.postulante = ?";

            $statement = $pdo->prepare($sql);
            $statement->execute([$id]);
            $result = $statement->fetchAll();

            $item = [];

            foreach ($result as $rs) {
                $tipoIndex = isset($rs['tipoinstruccion']) ? $rs['tipoinstruccion'] : null;

                $tipo = ($tipoIndex !== null && isset($estudios[$tipoIndex]))
                    ? strtoupper($estudios[$tipoIndex])
                    : 'NO DEFINIDO';

                // $inicio = !empty($rs['inicio']) ? date("d/m/Y", strtotime($rs['inicio'])) : '';
                // $fin = !empty($rs['fin']) ? date("d/m/Y", strtotime($rs['fin'])) : '';

                $data = array(
                    "tipoinstruccion" => $tipo,
                    "institucion"     => strtoupper($rs['institucion'] ?? ''),
                    "inicio"          => $rs['inicio'],
                    "fin"             => $rs['fin'],
                    "añoInicio"          => $rs['añoInicio'],
                    "añoFin"             => $rs['añoFin'],
                    "carrera"         => strtoupper($rs['carrera'] ?? ''),
                    "grado"           => strtoupper($rs['grado'] ?? '')
                );
                $item[] = $data;
            }

            return $item;
        } catch (PDOException $th) {
            error_log("Error en detallesEstudios: " . $th->getMessage());
            return false;
        }
    }


    public function detallesExperiencia($id)
    {
        $pdo = ConexionDocumentos::getInstancia()->getConexion();
        try {
            $sql = "SELECT 
			dexperiencialegajo.idreg,
			dexperiencialegajo.idficha,
			dexperiencialegajo.postulante,
			dexperiencialegajo.empresa,
			dexperiencialegajo.cargo,
			dexperiencialegajo.tiempo,
			dexperiencialegajo.motivoretiro,
			dexperiencialegajo.ultremuneracion,
			dexperiencialegajo.nombjefe,
			dexperiencialegajo.direccion,
			dexperiencialegajo.telefono
				FROM 
					dexperiencialegajo 
				WHERE 
					dexperiencialegajo.postulante = ? ";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($id));
            $result    = $statement->fetchAll();
            $rowaffect = $statement->rowCount($sql);

            $item = [];
            foreach ($result as $rs) {
                $data = array(
                    "empresa"  => strtoupper($rs['empresa']),
                    "cargo"  => strtoupper($rs['cargo']),
                    "tiempo"  => strtoupper($rs['tiempo']),
                    "motivoretiro"  => strtoupper($rs['motivoretiro']),
                    "ultremuneracion"  => $rs['ultremuneracion'],
                    "nombjefe"  => strtoupper($rs['nombjefe']),
                    "direccion"  => strtoupper($rs['direccion']),
                    "telefono"  => $rs['telefono']
                );
                array_push($item, $data);
            }
            return $item;
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }
}
