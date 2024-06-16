<?php 

class PedidoProducto
{
    private $_id;
    private $_idPedido;
    private $_idProducto;
    private $_estado;

    public function __construct($idPedido, $idProducto, $estado, $id=null)
    {
        $this->_idPedido = $idPedido;
        $this->_idProducto = $idProducto;
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
                    pedidos_productos (id_pedido, id_producto, estado)
                    VALUES (:id_pedido, :id_producto, :estado)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_pedido', $this->_idPedido, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_producto', $this->_idProducto, PDO::PARAM_INT);
        $queryPreparada->bindParam(':estado', $this->_estado, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ObtenerPedidoProductoDisponible($idPedido, $idProducto)
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT id_pedido, id_producto, estado, id FROM pedidos_productos 
                    WHERE id_producto = :id_producto AND id_pedido = :id_pedido AND estado = 'Pendiente' 
                    LIMIT 1";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);

        $queryPreparada->execute();

        $fila = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($fila) 
        {
            return new PedidoProducto($fila['id_pedido'], $fila['id_producto'], $fila['estado'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public static function ObtenerPedidoProductoEnPreparacion($idPedido, $idProducto, $idEmpleado)
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT id_pedido, id_producto, estado, id FROM pedidos_productos 
                    WHERE id_producto = :id_producto AND id_pedido = :id_pedido AND id_usuario = :id_empleado 
                    AND estado = 'En Preparación' 
                    LIMIT 1";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_empleado', $idEmpleado, PDO::PARAM_INT);

        $queryPreparada->execute();

        $fila = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($fila) 
        {
            return new PedidoProducto($fila['id_pedido'], $fila['id_producto'], $fila['estado'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public function TomarPedido($idEmpleado, $tiempoEstimado)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE pedidos_productos 
                    SET id_usuario = :id_usuario, estado = 'En Preparación', tiempo_estimado = :tiempo_estimado, hora_inicio = :hora_inicio 
                    WHERE id = :id";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $horaInicio = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':id_usuario', $idEmpleado, PDO::PARAM_INT);
        $queryPreparada->bindParam(':tiempo_estimado', $tiempoEstimado, PDO::PARAM_INT);
        $queryPreparada->bindParam(':hora_inicio', $horaInicio, PDO::PARAM_STR);
        $queryPreparada->bindParam(':id', $this->_id, PDO::PARAM_INT);

        return $queryPreparada->execute();
    }

    public function TerminarPedido()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE pedidos_productos 
                    SET estado = 'Listo para servir', hora_fin = :hora_fin 
                    WHERE id = :id";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $horaFin = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':hora_fin', $horaFin, PDO::PARAM_STR);
        $queryPreparada->bindParam(':id', $this->_id, PDO::PARAM_INT);

        return $queryPreparada->execute();
    }

    public static function ObtenerPedidosDisponiblesSegunTipo($tipoProducto)
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT 
                    me.codigo AS mesa_codigo, 
                    us.username AS mozo_username, 
                    pe.codigo AS pedido_codigo, 
                    pe.nombre_cliente, 
                    pr.nombre AS producto_nombre, 
                    pr.codigo AS producto_codigo 
                FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                INNER JOIN usuarios us ON pe.id_mozo = us.id
                WHERE pr.tipo = :tipo AND pp.estado = 'Pendiente'";
        
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':tipo', $tipoProducto, PDO::PARAM_STR);
        $queryPreparada->execute();
    
        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidosTomadosMozo($idMozo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT me.codigo, me.estado, pe.codigo, pr.nombre, pp.estado FROM pedidos_productos pp
                    INNER JOIN productos pr ON pp.id_producto = pr.id
                    INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                    INNER JOIN mesas me ON pe.id_mesa = me.id
                    WHERE pe.id_mozo = :id_mozo AND me.estado != 'Cerrada' AND pe.is_deleted = 0";
        
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':id_mozo', $idMozo, PDO::PARAM_INT);
        $queryPreparada->execute();
    
        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidosListosMozo($idMozo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT me.codigo, me.estado, pe.codigo, pr.nombre, pp.estado FROM pedidos_productos pp
                    INNER JOIN productos pr ON pp.id_producto = pr.id
                    INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                    INNER JOIN mesas me ON pe.id_mesa = me.id
                    WHERE pe.id_mozo = :id_mozo AND me.estado != 'Cerrada' AND pp.estado = 'Listo para servir'";
        
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':id_mozo', $idMozo, PDO::PARAM_INT);
        $queryPreparada->execute();
    
        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidosConEstados()
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT me.codigo, me.estado, pe.codigo, pr.nombre, pp.estado FROM pedidos_productos pp
                    INNER JOIN productos pr ON pp.id_producto = pr.id
                    INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                    INNER JOIN mesas me ON pe.id_mesa = me.id
                    WHERE me.estado != 'Cerrada' AND pe.is_deleted = 0";
        
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->execute();
    
        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }
}