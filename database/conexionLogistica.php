<?php
class ConexionLogistica{ 
    private static $instancia;
    private $conexion;
    
    public function __construct(){
        try {
            $pdo = new PDO(DATABASE_LOGISTICA_URL,DATABASE_USER,DATABASE_PASSWORD);
            $pdo->exec("SET CHARACTER SET utf8");
            $this->conexion = $pdo;
        } catch (PDOException $e) {
            throw new Exception("Error al conectarse a la base de datos de logistica.");
        }    
    }

    public static function getInstancia(){
        if(self::$instancia == null){
            self::$instancia = new ConexionLogistica();
        }

        return self::$instancia;
    }

    public function getConexion(){
        return $this->conexion;
    }
}