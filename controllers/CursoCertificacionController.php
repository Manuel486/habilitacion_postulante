<?php


require_once MODELS_PATH . "/CursoCertificacionModel.php";
require_once MODELS_PATH . "/PreseleCursCertModel.php";
require_once CONTROLLERS_PATH . "/helpers/ApiRespuesta.php";

class CursoCertificacionController
{

    private $curso_certificacionModel;
    private $preseleCursCertModel;

    public function __construct()
    {
        $this->curso_certificacionModel = new CursoCertificacionModel();
        $this->preseleCursCertModel = new PreseleCursCertModel();
    }

    public function vistaCursoCertificacion()
    {

    }

    public function apiGuardarPreseleCursoCert()
    {
        try {
            if (!isset($_POST["pre_cur_cert"])) {
                echo ApiRespuesta::error("Debe enviar información del preseleccionado y curso-certificacion");
                exit;
            }

            $pre_cur_cert = json_decode($_POST["pre_cur_cert"], true);

            $respuesta = $this->preseleCursCertModel->insertarPreCurCert($pre_cur_cert);

            if ($respuesta) {
                echo ApiRespuesta::exitoso("", "Candidato guardado con éxito.");
            } else {
                echo ApiRespuesta::error("No se pudo guardar el candidato");
            }
            
        } catch (Exception $e) {
            echo ApiRespuesta::error("Error inesperado al guardar candidato.");
        }
    }

    public function apiObtenerCurCert(){
        try{
            $curso_cert = $this->curso_certificacionModel->obtenerCursosCertificaciones();
            echo ApiRespuesta::exitoso($curso_cert,"Cursos y certificaciones obtenidos con éxito");
        } catch(Exception $e){
            echo ApiRespuesta::error("No se pudo obtener los cursos y certificaciones");
        }
    }
    
}