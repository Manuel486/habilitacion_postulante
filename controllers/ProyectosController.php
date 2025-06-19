<?php

require_once MODELS_PATH . "/ProyectoModel.php";
require_once MODELS_PATH . "/GeneralModel.php";
require_once MODELS_PATH . "/Requerimiento.php";
require_once CONTROLLERS_PATH . "/helpers/ApiRespuesta.php";

class ProyectosController
{

    private $proyectoModel;
    private $generalModel;
    private $requerimiento;

    public function __construct()
    {
        $this->proyectoModel = new ProyectoModel();
        $this->generalModel = new GeneralModel();
        $this->requerimiento = new Requerimiento();
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
            include VIEWS_PATH . '/proyectoDetalle.php';
        } catch (Exception $e) {
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

            $requerimiento = $_POST["requerimiento"];

            try {
                $respuesta = $this->requerimiento->insertarRequerimiento($requerimiento);
                if($respuesta){
                    echo ApiRespuesta::exitoso("", "Requerimiento guardado con éxito.");
                } else {
                    echo ApiRespuesta::error("No se pudo guardar el requerimiento");
                }
            } catch (Exception $e) {
                echo ApiRespuesta::error("Error inesperado al guardar requerimiento.");
            }
        } catch (Exception $e) {

        }
    }
}
