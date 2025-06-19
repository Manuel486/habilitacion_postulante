<?php

class ApiRespuesta {
    public static function exitoso($respuesta, $mensaje = null) {
        $respuesta = [
            'exitoso' => true,
            'mensaje' => $mensaje,
            'respuesta' => $respuesta
        ];

        header('Content-Type: application/json');
        return json_encode($respuesta);
    }

    public static function error($mensaje){
        $respuesta = [
            'exitoso' => false,
            'mensaje' => $mensaje
        ];

        header('Content-Type: application/json');
        return json_encode($respuesta);
    }
}
