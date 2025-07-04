<?php

use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

require_once MODELS_PATH . "/ProyectoModel.php";
require_once MODELS_PATH . "/GeneralModel.php";
require_once MODELS_PATH . "/RequerimientoModel.php";
require_once MODELS_PATH . "/PreseleccionadoModel.php";
require_once MODELS_PATH . "/CursoCertificacionModel.php";
require_once CONTROLLERS_PATH . "/helpers/ApiRespuesta.php";
require_once __DIR__ . "/../models/PostulanteModel.php";
require_once __DIR__ . "/helpers/sendEmailStatusPre.php";
require_once __DIR__ . "/../models/PostulanteModel.php";
require_once __DIR__ . "/../models/LegajoModel.php";
require_once __DIR__ . "/../models/AdjuntosModel.php";

class ProyectosController
{

    private $proyectoModel;
    private $generalModel;
    private $requerimientoModel;
    private $preseleccionadoModel;
    private $curso_certificacionModel;
    private $postulanteModel;
    private $legajoModel;
    private $adjuntosModel;

    public function __construct()
    {
        $this->proyectoModel = new ProyectoModel();
        $this->generalModel = new GeneralModel();
        $this->requerimientoModel = new RequerimientoModel();
        $this->preseleccionadoModel = new PreseleccionadoModel();
        $this->curso_certificacionModel = new CursoCertificacionModel();
        $this->postulanteModel = new PostulanteModel();
        $this->legajoModel = new LegajoModel();
        $this->adjuntosModel = new AdjuntosModel();
    }

    public function vistaProyectos()
    {
        try {
            $proyectos = $this->proyectoModel->obtenerProyectos();

            include VIEWS_PATH . '/proyectos.php';
        } catch (Exception $e) {
        }
    }

    public function vistaProyectoDetalle()
    {
        try {
            $proyectos = $this->proyectoModel->obtenerProyectos();
            $fases = $this->generalModel->obtenerFases();
            $cargos = $this->generalModel->obtenerCargos();
            $requerimientos = $this->requerimientoModel->obtenerRequerimientos();
            $certificaciones = $this->curso_certificacionModel->obtenerCursosCertificaciones();

            $requerimientos = array_map(function ($requerimiento) {
                $requerimiento["preseleccionados"] = $this->preseleccionadoModel
                    ->obtenerPreseleccionadosPorRequerimiento($requerimiento["id_requerimiento"]);
                $requerimiento["cubiertos"] = $this->preseleccionadoModel
                    ->contarPreseleccionadosCubiertosPorRequerimiento($requerimiento["id_requerimiento"]);
                return $requerimiento;
            }, $requerimientos);

            include VIEWS_PATH . '/admin/status/status.php';
        } catch (Exception $e) {
            error_log("Error en status controller: " . $e->getMessage());
            echo "Ocurrió un error al cargar los datos.";
        }
    }

    public function apiObtenerFases()
    {
        try {
            $fases = $this->generalModel->obtenerFases();
        } catch (Exception $e) {
        }
    }

    public function apiObtenerCargos()
    {
        try {
            $fases = $this->generalModel->obtenerCargos();
        } catch (Exception $e) {
        }
    }

    public function apiGuardarRequerimiento()
    {
        try {
            if (!isset($_POST["requerimiento"])) {
                echo ApiRespuesta::error("Debe enviar información del requerimiento");
                exit;
            }

            $requerimiento = json_decode($_POST["requerimiento"], true);

            if (!$requerimiento || !is_array($requerimiento)) {
                echo ApiRespuesta::error("Datos de requerimiento inválidos");
                exit;
            }

            $requerimiento["id_requerimiento"] = uniqid("RE");

            $respuesta = $this->requerimientoModel->insertarRequerimiento($requerimiento);
            if ($respuesta) {
                echo ApiRespuesta::exitoso("", "Requerimiento guardado con éxito.");
            } else {
                echo ApiRespuesta::error("No se pudo guardar el requerimiento");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al guardar requerimiento.");
        }
    }

    public function apiBuscarDocumentoPreseleccionado()
    {
        try {
            if (!isset($_POST["documento"]) || !isset($_POST["id_requerimiento"])) {
                echo ApiRespuesta::error("Debe enviar el documento y el id del requerimiento");
                exit;
            }

            $documento = $_POST["documento"];
            $idRequerimiento = $_POST["id_requerimiento"];

            $preseleccionado = $this->preseleccionadoModel->obtenerPorDocumento($documento);

            if (!$preseleccionado) {
                $url = "http://sicalsepcon.net/api/matrizworkerdataapi.php?documento=" . $documento;
                $api = file_get_contents($url);
                $datos = json_decode($api, true);

                if (empty($datos)) {
                    echo ApiRespuesta::error("Persona no encontrada");
                    return;
                } else {
                    $id_preseleccionado = uniqid("PRE");
                    $preseleccionado["id_preseleccionado"] = $id_preseleccionado;
                    $preseleccionado["apellidos_nombres"] = $datos[0]["paterno"] . " " . $datos[0]["materno"] . " " . $datos[0]["nombres"];
                    $preseleccionado["documento"] = $datos[0]["dni"];
                    $preseleccionado["fecha_nacimiento"] = $datos[0]["nacimiento"];
                    $preseleccionado["email"] = $datos[0]["correo"];
                    $preseleccionado["edad"] = $this->calcularEdad($datos[0]["nacimiento"]);
                    $preseleccionado["fecha_ingreso_ultimo_proyecto"] = $datos[0]["ingreso"];
                    $preseleccionado["fecha_cese_ultimo_proyecto"] = $datos[0]["cesado"];
                    $preseleccionado["nombre_ultimo_proyecto"] = $datos[0]["proyecto"];

                    $insertado = $this->preseleccionadoModel->insertarPreseleccionado($preseleccionado);
                    if (!$insertado) {
                        echo ApiRespuesta::error("No se pudo insertar el candidato (ya existe o hubo un error).");
                        return;
                    }
                }
            }

            $yaAsociado = $this->preseleccionadoModel->yaEstaAsociadoAlRequerimiento($preseleccionado["id_preseleccionado"], $idRequerimiento);

            if ($yaAsociado) {
                echo ApiRespuesta::error("La persona ya está asociada al requerimiento.");
            } else {
                $this->preseleccionadoModel->insertarPreseleccionadoRequerimiento($idRequerimiento, $preseleccionado["id_preseleccionado"]);
                echo ApiRespuesta::exitoso($preseleccionado, "Persona encontrada y asociada correctamente.");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("No se pudo realizar la búsqueda del documento por una falla en el servidor.");
        }
    }

    public function calcularEdad($fechaNacimiento)
    {
        $fechaNacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $fechaNacimiento->diff($hoy);
        return $edad->y;
    }

    public function apiBuscarDetallePreseleccionado()
    {
        try {
            if (!isset($_POST["id_preseleccionado"])) {
                echo ApiRespuesta::error("Debe enviar el id del preseleccionado");
                exit;
            }

            if (!isset($_POST["id_requerimiento"])) {
                echo ApiRespuesta::error("Debe enviar el id del requerimiento");
                exit;
            }


            $idPreseleccionado = $_POST["id_preseleccionado"];
            $idRequerimiento = $_POST["id_requerimiento"];

            $preseleccionado = $this->preseleccionadoModel->obtenerPorIdPreseleccionado($idPreseleccionado);
            $cursos = $this->preseleccionadoModel->obtenerCurCertPreseleccionado($idPreseleccionado, "curso");
            $certificados = $this->preseleccionadoModel->obtenerCurCertPreseleccionado($idPreseleccionado, "certificado");
            $preseleccionado_requerimiento = $this->preseleccionadoModel->obtenerInformacionPreselecRequerimiento($idPreseleccionado, $idRequerimiento);
            $alertas_cur_cert = $this->preseleccionadoModel->obtenerAlertasCertiCurso($idPreseleccionado);

            $preseleccionado["cursos"] = $cursos;
            $preseleccionado["certificados"] = $certificados;
            $preseleccionado["preseleccionado_requerimiento"] = $preseleccionado_requerimiento;
            $preseleccionado["alertas_cur_cert"] = $alertas_cur_cert;
            if ($preseleccionado) {
                echo ApiRespuesta::exitoso($preseleccionado, "Persona encontrada");
            } else {
                echo ApiRespuesta::error("Persona no encontrada");
            }

        } catch (Exception $e) {
            echo ApiRespuesta::error("No se pudo obtener el detalle del preseleccionado");
        }
    }

    public function apiGuardarInformacionPrseleccionado()
    {
        try {
            if (!isset($_POST["preseleccionado"])) {
                echo ApiRespuesta::error("Debe enviar información del preseleccionado");
                exit;
            }

            if (!isset($_POST["id_requerimiento"])) {
                echo ApiRespuesta::error("Debe enviar el id del requerimiento");
                exit;
            }

            $preseleccionado = json_decode($_POST["preseleccionado"], true);
            $idRequerimiento = $_POST["id_requerimiento"];
            $idPreseleccionado = $_POST["id_preseleccionado"];

            if (!$preseleccionado || !is_array($preseleccionado)) {
                echo ApiRespuesta::error("Datos de candidato inválidos");
                exit;
            }

            $id_preseleccionado = null;
            if ($idPreseleccionado == "") {
                $id_preseleccionado = uniqid("PRE");
                $preseleccionado["id_preseleccionado"] = $id_preseleccionado;
                $insertado = $this->preseleccionadoModel->insertarPreseleccionado($preseleccionado);
                if (!$insertado) {
                    echo ApiRespuesta::error("No se pudo insertar el candidato (ya existe o hubo un error).");
                    return;
                }
            } elseif ($idPreseleccionado != "") {
                $id_preseleccionado = $idPreseleccionado;
            }

            $respuesta = $this->preseleccionadoModel->insertarPreseleccionadoRequerimiento($idRequerimiento, $id_preseleccionado);

            if ($respuesta) {
                echo ApiRespuesta::exitoso("", "Candidato guardado con éxito.");
            } else {
                echo ApiRespuesta::error("No se pudo guardar el candidato");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al guardar candidato.");
        }
    }

    public function apiActualizarInformacionPreReque()
    {
        if (!isset($_POST["preseleccionado"])) {
            echo ApiRespuesta::error("Debe enviar información del preseleccionado");
            exit;
        }

        if (!isset($_POST["preseleccionado_requerimiento"])) {
            echo ApiRespuesta::error("Debe enviar información del preseleccionado relacionado a su requerimiento");
            exit;
        }

        $preseleccionado = json_decode($_POST["preseleccionado"], true);
        $preseleccionado_requerimiento = json_decode($_POST["preseleccionado_requerimiento"], true);

        $respuesta = $this->preseleccionadoModel->actualizarPreseleccionado($preseleccionado);
        $respuesta = $this->preseleccionadoModel->actualizarPreseleccionadoRequerimiento($preseleccionado_requerimiento);

        if ($respuesta) {
            echo ApiRespuesta::exitoso("", "Actualizado con éxito.");
        } else {
            echo ApiRespuesta::error("No se pudo actualizar.");
        }

    }

    public function apiActualizarInformacionMedica()
    {
        if (!isset($_POST["informacion_medica"])) {
            echo ApiRespuesta::error("Debe enviar la información médica");
            exit;
        }

        $informacion_medica = json_decode($_POST["informacion_medica"], true);

        $respuesta = $this->preseleccionadoModel->actualizarPreseleccionadoRequerimientoInfMedica($informacion_medica);

        if ($respuesta) {
            echo ApiRespuesta::exitoso("", "Actualizado con éxito.");
        } else {
            echo ApiRespuesta::error("No se pudo actualizar.");
        }

    }

    public function apiGuardarCurCertPreseleccionado()
    {
        try {
            if (!isset($_POST["pre_cur_cert"])) {
                echo ApiRespuesta::error("Debe enviar información del preseleccionado y el curso/certificado");
                exit;
            }

            $pre_cur_cert = json_decode($_POST["pre_cur_cert"], true);

            if (!$pre_cur_cert || !is_array($pre_cur_cert)) {
                echo ApiRespuesta::error("Datos del preseleccionado y curso/preseleccionado inválidos");
                exit;
            }

            $pre_cur_cert["id_prese_curs_certi"] = uniqid("PRECC");


            $curso_certificado = $this->curso_certificacionModel->obtenerCursoCertPorId($pre_cur_cert["id_curs_certi"]);

            $fecha_inicio = $pre_cur_cert["fecha_inicio"];

            $fecha_fin = date("Y-m-d", strtotime("+" . $curso_certificado["duracion"] . " month", strtotime($fecha_inicio)));

            $pre_cur_cert["fecha_fin"] = $fecha_fin;
            $pre_cur_cert["nombre_curso"] = $curso_certificado["nombre"];
            $pre_cur_cert["id_curso"] = $curso_certificado["id_curso_certificacion"];

            $respuesta = $this->preseleccionadoModel->guardarCurCertPreseleccionado($pre_cur_cert);

            $curso = $this->preseleccionadoModel->obtenerUnicoCurCertPreseleccionado($pre_cur_cert["id_prese_curs_certi"]);
            if ($respuesta) {
                echo ApiRespuesta::exitoso($curso, "Preseleccionado guarda con curso/certificado");
            } else {
                echo ApiRespuesta::error("No se pudo guardar el curso/certificado del preseleccionado");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al guardar preseleccionado con el curso/certificado.");
        }
    }

    public function apiActualizarCurCertPreseleccionado()
    {
        try {
            if (!isset($_POST["pre_cur_cert"])) {
                echo ApiRespuesta::error("Debe enviar información del preseleccionado y el curso/certificado");
                exit;
            }

            $pre_cur_cert = json_decode($_POST["pre_cur_cert"], true);

            if (!$pre_cur_cert || !is_array($pre_cur_cert)) {
                echo ApiRespuesta::error("Datos del preseleccionado y curso/preseleccionado inválidos");
                exit;
            }

            $cursoInfo = $this->curso_certificacionModel->obtenerCursoCertPorId($pre_cur_cert["id_curs_certi"]);

            $fecha_inicio = $pre_cur_cert["fecha_inicio"];

            $fecha_fin = date("Y-m-d", strtotime("+" . $cursoInfo["duracion"] . " month", strtotime($fecha_inicio)));

            $pre_cur_cert["fecha_fin"] = $fecha_fin;

            $respuesta = $this->preseleccionadoModel->actualizarCurCertPreseleccionado($pre_cur_cert);

            $curso = $this->preseleccionadoModel->obtenerUnicoCurCertPreseleccionado($pre_cur_cert["id_prese_curs_certi"]);

            if ($respuesta) {
                echo ApiRespuesta::exitoso($curso, "Preseleccionado guarda con curso/certificado");
            } else {
                echo ApiRespuesta::error("No se pudo actualizar el curso/certificado del preseleccionado");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al guardar preseleccionado con el curso/certificado.");
        }
    }

    public function apiEliminarPreseleccionadoRequerimiento()
    {
        if (!isset($_POST["id_preseleccionado"])) {
            echo ApiRespuesta::error("Debe enviar el id del preseleccionado");
            exit;
        }

        if (!isset($_POST["id_requerimiento"])) {
            echo ApiRespuesta::error("Debe enviar el id del requerimiento");
            exit;
        }

        $idPreseleccionado = $_POST["id_preseleccionado"];
        $idRequerimiento = $_POST["id_requerimiento"];

        try {
            $respuesta = $this->preseleccionadoModel->eliminarPreseleccionadoRequerimiento($idRequerimiento, $idPreseleccionado);

            if ($respuesta) {
                echo ApiRespuesta::exitoso("", "Preseleccionado eliminado con éxito del requerimiento.");
            } else {
                echo ApiRespuesta::error("No se pudo eliminar el preseleccionado del requerimiento.");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al eliminar el preseleccionado del requerimiento.");
        }
    }

    public function apiEnviarInvitacionPreseReque()
    {
        if (!isset($_POST["id_preseleccionado"]) || !isset($_POST["id_requerimiento"])) {
            echo ApiRespuesta::error("Debe enviar el id del preseleccionado y del requerimiento.");
            exit;
        }

        try {
            $idPreseleccionado = $_POST["id_preseleccionado"];
            $idRequerimiento = $_POST["id_requerimiento"];
            $preseleccionado = $this->preseleccionadoModel->obtenerPorIdPreseleccionado($idPreseleccionado);
            $requerimiento = $this->requerimientoModel->obtenerRequerimientoPorId($idRequerimiento);

            if (!$preseleccionado || !$requerimiento) {
                echo ApiRespuesta::error("Preseleccionado o requerimiento no encontrado.");
                return;
            }

            // $preseleccionado_requerimiento = $this->preseleccionadoModel->obtenerInformacionPreselecRequerimiento($idPreseleccionado, $idRequerimiento);

            $id = uniqid("PO");
            $doc = "000000000000000000000000000000";
            $reg = 1;
            $pass = rand(100000, 999999);
            $enviado = 0;

            $idPrevio = $this->postulanteModel->buscarReciente($preseleccionado["documento"]);
            if (!empty($idPrevio)) {
                $estado = $idPrevio[0]['estado'];
                $doc = substr_replace($estado, '1', 0, 1);
                $doc = substr($doc, 0, 22);
                $doc = $doc . str_repeat('0', 31 - strlen($doc));
            }

            $insertado = $this->postulanteModel->insertarPorstulanteDesdeStatus(
                $id,
                $preseleccionado,
                $requerimiento,
                $reg,
                $pass,
                $doc,
                0,
                "1"
            );

            if (!$insertado) {
                echo ApiRespuesta::error("No se pudo guardar el postulante.");
                exit;
            }

            $this->preseleccionadoModel->actualizarPreseRequeIdPostulante($idPreseleccionado, $idRequerimiento, $id);

            $correoExito = sendMailStatus(
                $preseleccionado["apellidos_nombres"],
                $preseleccionado["email"],
                $pass,
                $requerimiento["nombreCargo"],
                $requerimiento["nombreFase"],
                $requerimiento["nombreProyecto"],
                "desarrollador",
                "mchunga@sepcon.net"
            );

            if ($correoExito) {
                $this->postulanteModel->marcarCorreoEnviado($id);
            }

            if (!empty($idPrevio)) {
                $this->adjuntosModel->updateAdjuntos($idPrevio[0]['codigo'], $id);
                $this->legajoModel->nuevaFicha($idPrevio[0]['codigo'], $id);
            } else {
                $this->adjuntosModel->insertarCodigoAdjuntos($id, $doc);
            }

            if ($correoExito) {
                echo ApiRespuesta::exitoso([
                    "guardado" => true,
                    "correo_enviado" => true
                ], "Postulante guardado y correo enviado correctamente.");
                exit;
            } else {
                echo ApiRespuesta::exitoso([
                    "guardado" => true,
                    "correo_enviado" => false
                ], "Postulante guardado, pero no se pudo enviar el correo.");
                exit;
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            echo ApiRespuesta::error("Error inesperado al procesar la invitación.");
        }
    }

    public function apiReenviarInvitacionPreReque()
    {
        if (!isset($_POST["id_preseleccionado"]) || !isset($_POST["id_requerimiento"])) {
            echo ApiRespuesta::error("Debe enviar el id del preseleccionado y del requerimiento.");
            exit;
        }

        try {
            $idPreseleccionado = $_POST["id_preseleccionado"];
            $idRequerimiento = $_POST["id_requerimiento"];
            $preseleccionado_requerimiento = $this->preseleccionadoModel->obtenerInformacionPreselecRequerimiento($idPreseleccionado, $idRequerimiento);

            if (isset($preseleccionado_requerimiento["id_postulante"])) {
                $postulante = $this->postulanteModel->obtenerPostulantePorId($preseleccionado_requerimiento["id_postulante"]);
                $correoExito = sendMailStatus(
                    $postulante["nombres"],
                    $postulante["correo"],
                    $postulante["clave"],
                    $postulante["cargoDescripcion"],
                    $postulante["faseDescripcion"],
                    $postulante["nombreProyecto"],
                    "desarrollador",
                    "mchunga@sepcon.net"
                );

                if ($correoExito) {
                    $this->postulanteModel->marcarCorreoEnviado($postulante["idreg"]);
                }

                if ($correoExito) {
                    echo ApiRespuesta::exitoso([
                        "correo_enviado" => true
                    ], "Se volvio a enviar la clave a su correo.");
                    exit;
                } else {
                    echo ApiRespuesta::exitoso([
                        "correo_enviado" => false
                    ], "No se pudo volver a enviar el correo.");
                    exit;
                }
            }

            echo ApiRespuesta::error("Todavía no se genera su postulación.");
            exit;
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo ApiRespuesta::error("Error inesperado al procesar la invitación.");
        }
    }

    public function apiEliminarCursCertPreseleccionado()
    {
        if (!isset($_POST["id_prese_curs_certi"])) {
            echo ApiRespuesta::error("Debe enviar el id del preseleccionado-curso-certi");
            exit;
        }

        $idPreCursCerti = $_POST["id_prese_curs_certi"];

        try {
            $respuesta = $this->preseleccionadoModel->eliminarCursCertPreseleccionado($idPreCursCerti);

            if ($respuesta) {
                echo ApiRespuesta::exitoso("", "Eliminado con éxito");
            } else {
                echo ApiRespuesta::error("No se pudo eliminar.");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al eliminar.");
        }
    }


    public function apiObtenerHistorialProyectos()
    {
        $documento = $_POST["documento"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sicalsepcon.net/api/mantenimiento/loginMMTO.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(['usuario' => 'prueba']),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        $token = $data['token'] ?? null;

        if (!$token) {
            echo "Error: No se pudo obtener el token.";
            return;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://sicalsepcon.net/api/historicoapi_new.php?documento=$documento",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token"
            ],
        ));

        $historial = curl_exec($curl);
        curl_close($curl);

        echo $historial;
    }

}
