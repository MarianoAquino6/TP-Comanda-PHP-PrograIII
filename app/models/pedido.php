<?php 

class Pedido
{
    private $_id;
    private $_idMesa;
    private $_idMozo;
    private $_codigo;
    private $_nombreCliente;
    private $_fotoMesa;

    public function __construct($idMesa, $idMozo, $codigo, $nombreCliente, $id=null)
    {
        $this->_idMesa = $idMesa;
        $this->_idMozo = $idMozo;
        $this->_codigo = $codigo;
        $this->_nombreCliente = $nombreCliente;
        $this->_id = $id;
    }

    public function GetId()
    {
        return $this->_id;
    }

    public function GetCodigo()
    {
        return $this->_codigo;
    }

    public function RegistrarYDevolverId()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    pedidos (is_deleted, id_mesa, id_mozo, codigo, nombre_cliente, fecha_creacion, fecha_modificacion, foto_mesa)
                    VALUES (0, :id_mesa, :id_mozo, :codigo, :nombre_cliente, :fecha_creacion, :fecha_modificacion, :foto_mesa)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaCreacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':id_mesa', $this->_idMesa, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_mozo', $this->_idMozo, PDO::PARAM_INT);
        $queryPreparada->bindParam(':codigo', $this->_codigo, PDO::PARAM_STR);
        $queryPreparada->bindParam(':nombre_cliente', $this->_nombreCliente, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_creacion', $fechaCreacion, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaCreacion, PDO::PARAM_STR);
        $queryPreparada->bindParam(':foto_mesa', $this->_fotoMesa, PDO::PARAM_LOB);

        $resultado = $queryPreparada->execute();

        if ($resultado)
        {
            return $acceso->ObtenerUltimoId();
        }
        else
        {
            return $resultado;
        }
    }

    public static function PedidoExiste($codigo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT codigo FROM pedidos WHERE codigo = :codigo";
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

    public static function ObtenerUno($codigoPedido)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT id_mesa, id_mozo, codigo, nombre_cliente, foto_mesa, id FROM pedidos 
        WHERE codigo = :codigo AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':codigo', $codigoPedido, PDO::PARAM_STR);

        $queryPreparada->execute();

        $fila = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($fila) 
        {
            return new Pedido($fila['id_mesa'], $fila['id_mozo'], $fila['codigo'], $fila['nombre_cliente'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public static function Borrar($idMesa)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE pedidos SET is_deleted = 1, fecha_modificacion = :fecha_modificacion WHERE id_mesa = :id_mesa";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_mesa', $idMesa, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public function ActualizarImporteTotal()
    {
        $acceso = AccesoDatos::ObtenerInstancia();
        
        $query = "UPDATE pedidos pe
                    SET importe_total = (
                        SELECT SUM(pr.precio)
                        FROM pedidos_productos pp
                        INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                        INNER JOIN productos pr ON pp.id_producto = pr.id
                        WHERE pe.id = :id_pedido_1 AND pp.estado != 'Cancelado'
                    )
                    WHERE pe.id= :id_pedido_2";
        
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':id_pedido_1', $this->_id, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_pedido_2', $this->_id, PDO::PARAM_INT);
    
        return $queryPreparada->execute();
    }

    public static function ObtenerDatosNecesarioEncuesta($idPedido)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT p.id_mesa, p.id_mozo, GROUP_CONCAT(pp.id_usuario ORDER BY pp.id_usuario SEPARATOR ',') AS cocineros
                    FROM pedidos p
                    INNER JOIN pedidos_productos pp ON p.id = pp.id_pedido
                    INNER JOIN usuarios u ON pp.id_usuario = u.id
                    WHERE p.id = :id_pedido AND u.sector = 'COCINERO'
                    GROUP BY p.id_mesa, p.id_mozo";
        
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);

        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerTiempoRestante($codigoMesa, $codigoPedido)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT MAX(pp.tiempo_estimado) AS tiempo_maximo_estimado
                FROM pedidos_productos pp
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                WHERE me.codigo = :codigoMesa AND pe.codigo = :codigoPedido AND pp.estado = 'En Preparación'";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $queryPreparada->bindParam(':codigoPedido', $codigoPedido, PDO::PARAM_STR);

        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_ASSOC)['tiempo_maximo_estimado'];
    }
}