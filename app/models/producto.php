<?php 

class Producto
{
    private $_id;
    private $_tipo;
    private $_nombre;
    private $_precio;
    private $_codigo;

    public function __construct($tipo, $codigo, $nombre, $precio, $id=null)
    {
        $this->_tipo = $tipo;
        $this->_codigo = $codigo;
        $this->_nombre = $nombre;
        $this->_precio = $precio;
        $this->_id = $id;
    }

    public function GetId()
    {
        return $this->_id;
    }

    public function GetPrecio()
    {
        return $this->_precio;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    productos (is_deleted, tipo, codigo, nombre, precio, fecha_creacion, fecha_modificacion)
                    VALUES (0, :tipo, :codigo, :nombre, :precio, :fecha_creacion, :fecha_modificacion)";
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

    public static function ProductoExiste($codigo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT codigo FROM productos WHERE codigo = :codigo AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $queryPreparada->execute();

        $resultado = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($resultado != false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function ObtenerTodos()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT tipo, codigo, nombre, precio, fecha_creacion, fecha_modificacion 
                    FROM productos WHERE is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->execute();

        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerUno($codigoProducto)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT tipo, codigo, nombre, precio, id FROM productos 
                    WHERE codigo = :codigo AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigoProducto, PDO::PARAM_STR);
        $queryPreparada->execute();

        $fila = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($fila) 
        {
            return new Producto($fila['tipo'], $fila['codigo'], $fila['nombre'], $fila['precio'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public function ActualizarPrecio($precio)
    {
        $this->_precio = $precio;
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE productos SET precio = :precio, fecha_modificacion = :fecha_modificacion 
                    WHERE codigo = :codigo AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaModificacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':codigo', $this->_codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':precio', $this->_precio, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public function Borrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE productos SET is_deleted = 1, fecha_modificacion = :fecha_modificacion WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $this->_codigo, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }
}