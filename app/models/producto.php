<?php 

class Producto
{
    private $_tipo;
    private $_nombre;
    private $_precio;
    private $_codigo;

    public function __construct($tipo, $nombre, $precio, $codigo)
    {
        $this->_tipo = $tipo;
        $this->_nombre = $nombre;
        $this->_precio = $precio;
        $this->_codigo = $codigo;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    productos (is_deleted, tipo, codigo, nombre, precio, fecha_creacion, fecha_modificacion)
                    VALUES (false, :tipo, :codigo, :nombre, :precio, :fecha_creacion, :fecha_modificacion)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaCreacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':tipo', $this->_tipo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':codigo', $this->_codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':nombre', $this->_nombre, PDO::PARAM_STR);
        $queryPreparada->bindParam(':precio', $this->_precio, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_creacion', $fechaCreacion, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaCreacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ObtenerTodos()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT * FROM productos";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->execute();

        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerUno($codigoProducto)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT * FROM productos WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigoProducto, PDO::PARAM_STR);

        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }

    public static function ActualizarPrecio($codigo, $precio)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE productos SET precio = :precio, fecha_modificacion = :fecha_modificacion WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaModificacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':precio', $precio, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function Borrar($codigo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE productos SET is_deleted = true, fecha_modificacion = :fecha_modificacion WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }
}