<?php 

class PedidoProducto
{
    private $_idPedido;
    private $_idProducto;
    private $_estado;

    public function __construct($idPedido, $idProducto, $estado)
    {
        $this->_idPedido = $idPedido;
        $this->_idProducto = $idProducto;
        $this->_estado = $estado;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    pedidos_productos (id_pedido, id_producto, estado)
                    VALUES (:id_pedido, :id_producto, :estado)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_pedido', $this->_idPedido, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_producto', $this->_idProducto, PDO::PARAM_INT);
        $queryPreparada->bindParam(':estado', $this->_estado, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ObtenerImporteTotal($idPedido)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query= "SELECT SUM(p.precio) AS importe_total
                    FROM pedidos_productos pp
                    JOIN productos p ON pp.id_producto = p.id
                    WHERE pp.id_pedido = :idPedido
                    GROUP BY pp.id_pedido";

        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);
        $queryPreparada->execute();
        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }

    public static function TomarPedido($idPedidoProducto, $idEmpleado, $tiempoEstimado)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE pedidos_productos 
                    SET id_empleado = :id_empleado, estado = 'En Preparación', tiempo_estimado = :tiempo_estimado, 
                    hora_inicio = :hora_inicio 
                    WHERE id = :id";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $horaInicio = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':id_empleado', $idEmpleado, PDO::PARAM_INT);
        $queryPreparada->bindParam(':tiempo_estimado', $tiempoEstimado, PDO::PARAM_STR);
        $queryPreparada->bindParam(':hora_inicio', $horaInicio, PDO::PARAM_STR);
        $queryPreparada->bindParam(':id', $idPedidoProducto, PDO::PARAM_INT);

        return $queryPreparada->execute();
    }

    public static function ObtenerProductoDisponible($idPedido, $idProducto)
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT id FROM pedidos_productos WHERE id_producto = :id_producto AND id_pedido = :id_pedido AND 
                    estado != 'Pendiente' LIMIT 1";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);

        $queryPreparada->execute();
    
        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }
}