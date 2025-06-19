<?php

require_once MODELS_PATH."/ProyectoModel.php";

class ProyectosController {

    private $proyectoModel;

    public function __construct(){
        $this->proyectoModel = new ProyectoModel();
    }

    public function vistaProyectos(){
        try {
            $proyectos = $this->proyectoModel->obtenerProyectos();
            include VIEWS_PATH.'/proyectos.php';
        } catch(Exception $e){

        }
    }

    public function vistaProyectoDetalle(){
        try {
            $proyectos = $this->proyectoModel->obtenerProyectos();
            include VIEWS_PATH.'/proyectoDetalle.php';
        } catch(Exception $e){

        }
    }
}
