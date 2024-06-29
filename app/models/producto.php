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

    ///////////////////////////////////////////// CREATE ///////////////////////////////////////////////////////////

    public function Registrar()
    {
        $query = "INSERT INTO 
                productos (is_deleted, tipo, codigo, nombre, precio, fecha_creacion, fecha_modificacion)
                VALUES (0, :tipo, :codigo, :nombre, :precio, :fecha_creacion, :fecha_modificacion)";
    
        $parametros = [
            ':tipo' => $this->_tipo,
            ':codigo' => $this->_codigo,
            ':nombre' => $this->_nombre,
            ':precio' => $this->_precio,
            ':fecha_creacion' => date('Y-m-d H:i:s'),
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// UPDATE ///////////////////////////////////////////////////////////

    public function ActualizarPrecio($precio)
    {
        $this->_precio = $precio;

        $query = "UPDATE productos SET precio = :precio, fecha_modificacion = :fecha_modificacion 
                    WHERE codigo = :codigo AND is_deleted = 0";
        $parametros = [
            ':codigo' => $this->_codigo,
            ':precio' => $this->_precio,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// DELETE ///////////////////////////////////////////////////////////

    public function Borrar()
    {
        $query = "UPDATE productos SET is_deleted = 1, fecha_modificacion = :fecha_modificacion WHERE codigo = :codigo";
        $parametros = [
            ':codigo' => $this->_codigo,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    /////////////////////////////////////////////  READ  ///////////////////////////////////////////////////////////
    public static function ProductoExiste($codigo)
    {
        $query = "SELECT codigo FROM productos WHERE codigo = :codigo AND is_deleted = 0";
        $parametros = [':codigo' => $codigo];

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        return $resultado->fetch(PDO::FETCH_ASSOC) != false;
    }

    public static function ObtenerTodos()
    {
        $query = "SELECT tipo, codigo, nombre, precio, fecha_creacion, fecha_modificacion 
                FROM productos WHERE is_deleted = 0";

        return AccesoDatos::EjecutarConsultaSelect($query, [])->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function TransformarArrayACSV($arrayAsociativo)
    {
        if (empty($arrayAsociativo)) {
            return '';
        }

        $csv = '';

        // Obtener los encabezados del array asociativo
        $headers = array_keys($arrayAsociativo[0]);
        $csv .= implode(',', $headers) . "\n";

        // Agregar los valores del array asociativo al CSV
        foreach ($arrayAsociativo as $fila) {
            $csv .= implode(',', array_map(function ($valor) {
                // Envolver solo los valores que contienen comas o comillas
                if (strpos($valor, ',') !== false || strpos($valor, '"') !== false) {
                    return '"' . str_replace('"', '""', $valor) . '"'; // Escapar comillas dobles
                } else {
                    return $valor;
                }
            }, $fila)) . "\n";
        }

        // Eliminar el último salto de línea si existe
        $csv = rtrim($csv, "\n");

        return $csv;
    }

    public static function ObtenerTodosCSV()
    {
        $query = "SELECT is_deleted, tipo, codigo, nombre, precio
                FROM productos WHERE is_deleted = 0";

        $arrayAsociativoProductos = AccesoDatos::EjecutarConsultaSelect($query, [])->fetchAll(PDO::FETCH_ASSOC);

        return self::TransformarArrayACSV($arrayAsociativoProductos);
    }

    public static function ObtenerUno($codigoProducto)
    {
        $query = "SELECT tipo, codigo, nombre, precio, id FROM productos 
                WHERE codigo = :codigo AND is_deleted = 0";
        $parametros = [':codigo' => $codigoProducto];

        $fila = AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC);

        if ($fila) 
        {
            return new Producto($fila['tipo'], $fila['codigo'], $fila['nombre'], $fila['precio'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public function ProductoNoCorrespondeAUsuario($sectorUsuario)
    {
        $query = "SELECT tipo FROM productos WHERE codigo  = :codigoProducto";

        $parametros = [':codigoProducto' => $this->_codigo];

        $tipoProducto = AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC)['tipo'];

        switch ($sectorUsuario)
        {
            case "COCINERO":
                if ($tipoProducto != "COMIDA")
                {
                    return true;
                }

                break;
            case "CERVECERO":

                if ($tipoProducto != "CERVEZA")
                {
                    return true;
                }

                break;
            case "BARTENDER":

                if ($tipoProducto != "TRAGO")
                {
                    return true;
                }

                break;
        }

        return false;
    }
}