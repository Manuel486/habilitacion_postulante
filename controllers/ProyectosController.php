<?php

require_once MODELS_PATH . "/ProyectoModel.php";
require_once MODELS_PATH . "/GeneralModel.php";
require_once MODELS_PATH . "/RequerimientoModel.php";
require_once MODELS_PATH . "/PreseleccionadoModel.php";
require_once MODELS_PATH . "/CursoCertificacionModel.php";
require_once CONTROLLERS_PATH . "/helpers/ApiRespuesta.php";

class ProyectosController
{

    private $proyectoModel;
    private $generalModel;
    private $requerimientoModel;
    private $preseleccionadoModel;
    private $curso_certificacionModel;

    public function __construct()
    {
        $this->proyectoModel = new ProyectoModel();
        $this->generalModel = new GeneralModel();
        $this->requerimientoModel = new RequerimientoModel();
        $this->preseleccionadoModel = new PreseleccionadoModel();
        $this->curso_certificacionModel = new CursoCertificacionModel();
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
                $requerimiento["candidatos"] = $this->preseleccionadoModel
                    ->obtenerCandidatosPorRequerimiento($requerimiento["id_requerimiento"]);
                $requerimiento["cubiertos"] = $this->preseleccionadoModel
                    ->contarCandidatosCubiertosPorRequerimiento($requerimiento["id_requerimiento"]);
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
            if (!isset($_POST["documento"])) {
                echo ApiRespuesta::error("Debe enviar el documento");
                exit;
            }

            $documento = $_POST["documento"];
            $idRequerimiento = $_POST["id_requerimiento"];

            $preseleccionado = $this->preseleccionadoModel->obtenerPorDocumento($documento);

            if ($preseleccionado) {
                $this->preseleccionadoModel->insertarCandidatoProyecto($idRequerimiento, $preseleccionado["id_preseleccionado"]);
            }

            if ($preseleccionado) {
                echo ApiRespuesta::exitoso($preseleccionado, "Persona encontrada");
            } else {
                echo ApiRespuesta::error("Persona no encontrada");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("No se pudo realizar la búsqueda del documento por una falla en el servidor.");
        }
    }

    public function apiBuscarDetallePreseleccionado()
    {
        try {
            if (!isset($_POST["id_preseleccionado"])) {
                echo ApiRespuesta::error("Debe enviar el id del preseleccionado");
                exit;
            }

            $idPreseleccionado = $_POST["id_preseleccionado"];

            $preseleccionado = $this->preseleccionadoModel->obtenerPorIdPreseleccionado($idPreseleccionado);
            $cursos = $this->preseleccionadoModel->obtenerCurCertPreseleccionado($idPreseleccionado, "curso");
            $certificados = $this->preseleccionadoModel->obtenerCurCertPreseleccionado($idPreseleccionado, "certificado");

            $preseleccionado["cursos"] = $cursos;
            $preseleccionado["certificados"] = $certificados;
            if ($preseleccionado) {
                echo ApiRespuesta::exitoso($preseleccionado, "Persona encontrada");
            } else {
                echo ApiRespuesta::error("Persona no encontrada");
            }

        } catch (Exception $e) {
            echo ApiRespuesta::error("No se pudo obtener el detalle del preseleccionado");
        }
    }

    public function apiGuardarInformacionCandidato()
    {
        try {
            if (!isset($_POST["preseleccionado"])) {
                echo ApiRespuesta::error("Debe enviar información del candidato");
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
                $insertado = $this->preseleccionadoModel->insertarCandidato($preseleccionado);
                if (!$insertado) {
                    echo ApiRespuesta::error("No se pudo insertar el candidato (ya existe o hubo un error).");
                    return;
                }
            } elseif ($idPreseleccionado != "") {
                $id_preseleccionado = $idPreseleccionado;
            }

            $respuesta = $this->preseleccionadoModel->insertarCandidatoProyecto($idRequerimiento, $id_preseleccionado);

            if ($respuesta) {
                echo ApiRespuesta::exitoso("", "Candidato guardado con éxito.");
            } else {
                echo ApiRespuesta::error("No se pudo guardar el candidato");
            }
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al guardar candidato.");
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
