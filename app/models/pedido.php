<?php 

class Pedido
{
    private $_idMesa;
    private $_idMozo;
    private $_codigo;
    private $_nombreCliente;
    private $_tiempoEstimado;
    private $_tiempoTardado;
    private $_fotoMesa;
    private $_importeTotal;

    public function __construct($idMesa, $idMozo, $codigo, $nombreCliente, $fotoMesa)
    {
        $this->_idMesa = $idMesa;
        $this->_idMozo = $idMozo;
        $this->_codigo = $codigo;
        $this->_nombreCliente = $nombreCliente;
        $this->_fotoMesa = $fotoMesa;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    pedidos (is_deleted, id_mesa, codigo, nombre_cliente, fecha_creacion, fecha_modificacion, foto_mesa)
                    VALUES (false, :id_mesa, :codigo, :nombre_cliente, :fecha_creacion, :fecha_modificacion, :foto_mesa)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaCreacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':id_mesa', $this->_idMesa, PDO::PARAM_INT);
        $queryPreparada->bindParam(':codigo', $this->_codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':nombre_cliente', $this->_nombreCliente, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_creacion', $fechaCreacion, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaCreacion, PDO::PARAM_STR);
        $queryPreparada->bindParam(':foto_mesa', $this->_fotoMesa, PDO::PARAM_LOB);

        return $queryPreparada->execute();
    }

    public static function ObtenerTodos()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT * FROM pedidos";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->execute();

        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerUno($codigoPedido)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT * FROM pedidos WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigoPedido, PDO::PARAM_STR);

        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }

    public static function Borrar($codigo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE pedidos SET is_deleted = true, fecha_modificacion = :fecha_modificacion WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ActualizarImporteTotal($codigo, $importeTotal)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE pedidos SET importe_total = :importe_total, fecha_modificacion = :fecha_modificacion WHERE codigo = :codigo";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':importe_total', $importeTotal, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public function AsignarMozo()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO pedidos (id_mozo) VALUES (:id_mozo)";
        $queryPreparada = $acceso->PrepararConsulta($query);


        $queryPreparada->bindParam(':id_mozo', $this->_idMozo, PDO::PARAM_INT);

        return $queryPreparada->execute();
    }
}