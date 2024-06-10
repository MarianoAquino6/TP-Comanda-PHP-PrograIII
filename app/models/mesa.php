<?php 

class Mesa 
{
    private $_id;
    private $_codigo;
    private $_estado;

    public function __construct($codigo, $estado, $id=null)
    {
        $this->_codigo = $codigo;
        $this->_estado = $estado;
        $this->_id = $id;
    }

    public function GetId()
    {
        return $this->_id;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    mesas (is_deleted, codigo, estado, fecha_creacion, fecha_modificacion)
                    VALUES (false, :codigo, :estado, :fecha_creacion, :fecha_modificacion)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaCreacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':codigo', $this->_codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':estado', $this->_estado, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_creacion', $fechaCreacion, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaCreacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ObtenerUno($codigoMesa)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT codigo, estado, id 
                    FROM mesas 
                    WHERE codigo = :codigo AND NOT is_deleted";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigoMesa, PDO::PARAM_STR);

        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_CLASS, 'Mesa');
    }

    public function ActualizarEstado($estado)
    {
        $this->_estado = $estado;
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE mesas 
                    SET estado = :estado, fecha_modificacion = :fecha_modificacion 
                    WHERE codigo = :codigo AND NOT is_deleted";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaModificacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':codigo', $this->_codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':estado', $this->_estado, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function Borrar($codigo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE mesas 
                    SET is_deleted = true, fecha_modificacion = :fecha_modificacion 
                    WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ObtenerListadoMesasConEstados()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT id, codigo, estado, fecha_creacion FROM mesas WHERE NOT is_deleted";
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->execute();
        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesaMasUsada()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT * FROM mesas 
                    WHERE id = (
                        SELECT m.id 
                        FROM mesas m
                        INNER JOIN pedidos p ON m.id = p.id_mesa
                        GROUP BY m.id
                        ORDER BY COUNT(p.id) DESC
                        LIMIT 1)";

        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->execute();
        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }

    public static function MesaExiste($codigo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT codigo FROM mesas WHERE codigo = :codigo AND NOT is_deleted";
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $queryPreparada->execute();

        $resultado = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if (count($resultado) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}