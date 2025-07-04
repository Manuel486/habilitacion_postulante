<?php

require_once __DIR__.'/config/AltoRouter.php';

require_once 'config.local.php';

define("VIEWS_PATH",__DIR__ ."/views");
define("BASE_URL", "/habilitacion_postulante/");
define("MODELS_PATH",__DIR__."/models"); 
define("CONTROLLERS_PATH",__DIR__."/controllers");
define("DATABASE_PATH",__DIR__."/database");
define("STATUS_PATH",__DIR__."/status");

$router = new AltoRouter();
$router->setBasePath("/habilitacion_postulante");

$router->map('GET', '/', function() {
    header('Location: /preseleccionado/proyectos');
    exit;
});

$router->map("GET","/proyectos", function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->vistaProyectos();
});

$router->map("GET","/proyectoDetalle", function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->vistaProyectoDetalle();
});

// ========================= RUTAS - API =========================

// ========================= RUTAS API DE PROYECTO DETALLE =========================

$router->map('GET', '/api/obtenerFases', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiObtenerFases();
});

$router->map('GET', '/api/obtenerCargos', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiObtenerCargos();
});

$router->map('POST', '/api/guardarRequerimiento', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiGuardarRequerimiento();
});

$router->map('POST', '/api/buscarDocumentoPreseleccionado', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiBuscarDocumentoPreseleccionado();
});

$router->map('POST', '/api/guardarInformacionPreseleccionado', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiGuardarInformacionPrseleccionado();
});

$router->map('POST', '/api/actualizarInformacionPreReque', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiActualizarInformacionPreReque();
});

$router->map('POST', '/api/actualizarInformacionMedica', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiActualizarInformacionMedica();
});

$router->map('GET', '/api/obtenerCursosCertificaciones', function() {
    require_once CONTROLLERS_PATH.'/CursoCertificacionController.php';
    $controller = new CursoCertificacionController();
    $controller->apiObtenerCurCert();
});

$router->map('POST', '/api/buscarDetallePreseleccionado', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiBuscarDetallePreseleccionado();
});

$router->map('POST', '/api/eliminarPreseRequer', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiEliminarPreseleccionadoRequerimiento();
});

$router->map('POST', '/api/envitarInvitacionPreReque', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiEnviarInvitacionPreseReque();
});

$router->map('POST', '/api/reenviarInvitacionPreReque', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiReenviarInvitacionPreReque();
});



$router->map('POST', '/api/eliminarCursCertPreseleccionado', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiEliminarCursCertPreseleccionado();
});

$router->map('POST', '/api/guardarCurCertPreseleccionado', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiGuardarCurCertPreseleccionado();
});

$router->map('POST', '/api/actualizarCurCertPreseleccionado', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiActualizarCurCertPreseleccionado();
});

$router->map('POST', '/api/obtenerHistorialProyectos', function() {
    require_once CONTROLLERS_PATH.'/ProyectosController.php';
    $controller = new ProyectosController();
    $controller->apiObtenerHistorialProyectos();
});

$router->map('POST', '/api/generarExcelStatus', function() {
    require_once CONTROLLERS_PATH.'/GenerarStatusController.php';
    $controller = new GenerarStatusController();
    $controller->generarExcelStatus();
});

$router->map('GET', '/api/obtenerNombreColumnas', function() {
    require_once CONTROLLERS_PATH.'/GenerarStatusController.php';
    $controller = new GenerarStatusController();
    $controller->apiObtenerNombreColumnas();
});

$match = $router->match();

if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    include_once VIEWS_PATH.'/404.php';
}
