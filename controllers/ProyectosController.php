<?php

require_once MODELS_PATH . "/ProyectoModel.php";
require_once MODELS_PATH . "/GeneralModel.php";
require_once MODELS_PATH . "/RequerimientoModel.php";
require_once MODELS_PATH . "/PreseleccionadoModel.php";
require_once CONTROLLERS_PATH . "/helpers/ApiRespuesta.php";

class ProyectosController
{

    private $proyectoModel;
    private $generalModel;
    private $requerimientoModel;
    private $preseleccionadoModel;

    public function __construct()
    {
        $this->proyectoModel = new ProyectoModel();
        $this->generalModel = new GeneralModel();
        $this->requerimientoModel = new RequerimientoModel();
        $this->preseleccionadoModel = new PreseleccionadoModel();
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

            // Mapear candidatos a cada requerimiento
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

            $respuesta = $this->preseleccionadoModel->obtenerPorDocumento($documento);
            if ($respuesta["exitoso"]) {
                echo ApiRespuesta::exitoso($respuesta["preseleccionado"], "Persona encontrada");
            } else {
                echo ApiRespuesta::error("Persona no encontrada");
            }

        } catch (Exception $e) {
            echo ApiRespuesta::error("No se pudo realizar la búsqueda del documento por una falla en el servidor.");
        }
    }

    public function apiGuardarInformacionCandidato()
    {
        try {
            if (!isset($_POST["candidato"])) {
                echo ApiRespuesta::error("Debe enviar información del candidato");
                exit;
            }

            if (!isset($_POST["id_requerimiento"])) {
                echo ApiRespuesta::error("Debe enviar el id del requerimiento");
                exit;
            }

            $candidato = json_decode($_POST["candidato"], true);
            $idRequerimiento = $_POST["id_requerimiento"];
            $idCandidato = $_POST["id_candidato"];

            if (!$candidato || !is_array($candidato)) {
                echo ApiRespuesta::error("Datos de candidato inválidos");
                exit;
            }

            $id_preseleccionado = null;
            if($idCandidato == ""){
                $id_preseleccionado = uniqid("PRE");
                $candidato = $this->preseleccionadoModel->insertarCandidato($candidato);
            } elseif($idCandidato != ""){
                $id_preseleccionado = $idCandidato;
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
}
