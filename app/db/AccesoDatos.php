<?php

class AccesoDatos
{
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
        try 
        {
            $this->objetoPDO = new PDO('mysql:host='.$_ENV['MYSQL_HOST'].';port='.$_ENV['MYSQL_PORT'].';dbname='.$_ENV['MYSQL_DB'].';charset=utf8', $_ENV['MYSQL_USER'], '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } 
        catch (PDOException $e) 
        {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public static function ObtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) 
        {
            self::$objAccesoDatos = new AccesoDatos();
        }
        return self::$objAccesoDatos;
    }

    public function PrepararConsulta($sql)
    {
        return $this->objetoPDO->prepare($sql);
    }

    public function ObtenerUltimoId()
    {
        return $this->objetoPDO->lastInsertId();
    }

    public static function EjecutarConsultaIUDYDevolverId($query, $parametros)
    {
        $acceso = self::ObtenerInstancia();
        $queryPreparada = $acceso->PrepararConsulta($query);
        
        foreach ($parametros as $nombre => $valor) 
        {
            // Si es INT se establece con PARAM_INT, de lo contrario PARAM_STR
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $queryPreparada->bindValue($nombre, $valor, $tipo);
        }

        // Devuelvo un bool de acuerdo a la ejecucion
        $resultado = $queryPreparada->execute();

        if ($resultado) {
            return $acceso->ObtenerUltimoId();
        } else {
            return $resultado;
        }
    }

    public static function EjecutarConsultaIUD($query, $parametros)
    {
        $acceso = self::ObtenerInstancia();
        $queryPreparada = $acceso->PrepararConsulta($query);
        
        foreach ($parametros as $nombre => $valor) 
        {
            // Si es INT se establece con PARAM_INT, de lo contrario PARAM_STR
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $queryPreparada->bindValue($nombre, $valor, $tipo);
        }

        // Devuelvo un bool de acuerdo a la ejecucion
        return $queryPreparada->execute();
    }

    public static function EjecutarConsultaSelect($query, $parametros)
    {
        $acceso = self::ObtenerInstancia();
        $queryPreparada = $acceso->PrepararConsulta($query);
        
        foreach ($parametros as $nombre => $valor) 
        {
            // Si es INT se establece con PARAM_INT, de lo contrario PARAM_STR
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $queryPreparada->bindValue($nombre, $valor, $tipo);
        }

        $queryPreparada->execute();

        // Devuelvo la query preparada y ejecutada
        return $queryPreparada;
    }

    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}
