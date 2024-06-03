<?php 

class PedidoProducto
{
    private $_idPedido;
    private $_idProducto;
    private $_cantidad;

    public function __construct($idPedido, $idProducto, $cantidad)
    {
        $this->_idPedido = $idPedido;
        $this->_idProducto = $idProducto;
        $this->_cantidad = $cantidad;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    pedidos_productos (id_pedido, id_producto, cantidad)
                    VALUES (:id_pedido, :id_producto, :cantidad)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_pedido', $this->_idPedido, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_producto', $this->_idProducto, PDO::PARAM_INT);
        $queryPreparada->bindParam(':cantidad', $this->_cantidad, PDO::PARAM_INT);

        return $queryPreparada->execute();
    }

    public static function ObtenerImporteTotal($idPedido)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT SUM(pp.cantidad * pr.precio) AS importe_total 
                    FROM pedidos_productos pp 
                    INNER JOIN productos pr ON pp.id_producto = pr.id
                    INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                    WHERE pp.id_pedido = :idPedido";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);
        $queryPreparada->execute();
        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }
}