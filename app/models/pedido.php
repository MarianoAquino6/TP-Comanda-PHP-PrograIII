<?php

class Pedido
{
    private $_id;
    private $_idMesa;
    private $_idMozo;
    private $_codigo;
    private $_nombreCliente;
    private $_fotoMesa;

    public function __construct($idMesa, $idMozo, $codigo, $nombreCliente, $id = null)
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

    public function SetFoto($foto)
    {
        $this->_fotoMesa = $foto;
    }

    ///////////////////////////////////////////// CREATE ///////////////////////////////////////////////////////////

    public function RegistrarYDevolverId()
    {
        $query = "INSERT INTO 
                pedidos (is_deleted, id_mesa, id_mozo, codigo, nombre_cliente, fecha_creacion, fecha_modificacion, foto_mesa)
                VALUES (0, :id_mesa, :id_mozo, :codigo, :nombre_cliente, :fecha_creacion, :fecha_modificacion, :foto_mesa)";

        $parametros = [
            ':id_mesa' => $this->_idMesa,
            ':id_mozo' => $this->_idMozo,
            ':codigo' => $this->_codigo,
            ':nombre_cliente' => $this->_nombreCliente,
            ':fecha_creacion' => date('Y-m-d H:i:s'),
            ':fecha_modificacion' => date('Y-m-d H:i:s'),
            ':foto_mesa' => $this->_fotoMesa
        ];

        return AccesoDatos::EjecutarConsultaIUDYDevolverId($query, $parametros);
    }

    ///////////////////////////////////////////// UPDATE ///////////////////////////////////////////////////////////

    public function ActualizarImporteTotal()
    {
        $query = "UPDATE pedidos pe
                SET importe_total = (
                    SELECT SUM(pr.precio)
                    FROM pedidos_productos pp
                    INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                    INNER JOIN productos pr ON pp.id_producto = pr.id
                    WHERE pe.id = :id_pedido_1 AND pp.estado != 'Cancelado'
                )
                WHERE pe.id= :id_pedido_2";

        $parametros = [
            ':id_pedido_1' => $this->_id,
            ':id_pedido_2' => $this->_id
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public function VincularFoto()
    {
        $query = "UPDATE pedidos SET foto_mesa = :foto, fecha_modificacion = :fecha WHERE codigo = :codigo"; 

        $fotoBinaria = file_get_contents($this->_fotoMesa);

        $parametros = [
            ':foto' => $fotoBinaria,
            ':fecha' => date('Y-m-d H:i:s'),
            ':codigo' => $this->_codigo
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public function CancelarPedidoEntero()
    {
        $query = "UPDATE pedidos_productos pp
                JOIN pedidos p ON pp.id_pedido = p.id
                SET pp.estado = 'Cancelado', p.fecha_modificacion = :fecha_modificacion
                WHERE p.id = :id_pedido";

        $parametros = [
            ':id_pedido' => $this->_id,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// DELETE ///////////////////////////////////////////////////////////

    public static function Borrar($idMesa)
    {
        $query = "UPDATE pedidos SET is_deleted = 1, fecha_modificacion = :fecha_modificacion 
                WHERE id_mesa = :id_mesa AND is_deleted = 0";

        $parametros = [
            ':id_mesa' => $idMesa,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// READ ///////////////////////////////////////////////////////////

    public static function ObtenerUno($codigoPedido)
    {
        $query = "SELECT id_mesa, id_mozo, codigo, nombre_cliente, foto_mesa, id FROM pedidos 
                    WHERE codigo = :codigo AND is_deleted = 0";
        $parametros = [':codigo' => $codigoPedido];

        $queryPreparada = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        $fila = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            return new Pedido($fila['id_mesa'], $fila['id_mozo'], $fila['codigo'], $fila['nombre_cliente'], $fila['id']);
        } else {
            return null;
        }
    }

    public static function PedidoExiste($codigo)
    {
        $query = "SELECT codigo FROM pedidos WHERE codigo = :codigo";
        $parametros = [':codigo' => $codigo];

        $queryPreparada = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        $resultado = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        return ($resultado != false);
    }

    public static function ObtenerDatosNecesarioEncuesta($idPedido)
    {
        $query = "SELECT p.id_mesa, p.id_mozo, GROUP_CONCAT(pp.id_usuario ORDER BY pp.id_usuario SEPARATOR ',') AS cocineros
                FROM pedidos p
                INNER JOIN pedidos_productos pp ON p.id = pp.id_pedido
                INNER JOIN usuarios u ON pp.id_usuario = u.id
                WHERE p.id = :id_pedido AND u.sector = 'COCINERO'
                GROUP BY p.id_mesa, p.id_mozo";

        $parametros = [':id_pedido' => $idPedido];

        $queryPreparada = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerTiempoRestante($codigoMesa, $codigoPedido)
    {
        $query = "SELECT TIME_TO_SEC(TIMEDIFF(DATE_ADD(pp.hora_inicio, INTERVAL pp.tiempo_estimado MINUTE), NOW())) AS tiempo_restante
                FROM pedidos_productos pp
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                WHERE me.codigo = :codigoMesa AND pe.codigo = :codigoPedido AND pp.estado = 'En Preparación'
                ORDER BY pp.tiempo_estimado DESC
                LIMIT 1";

        $parametros = [':codigoMesa' => $codigoMesa, ':codigoPedido' => $codigoPedido];

        $queryPreparada = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        $resultado = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($resultado !== false) 
        {
            $tiempoRestanteSegundos = $resultado['tiempo_restante'];
            $tiempoRestanteMinutos = $tiempoRestanteSegundos / 60;
            return $tiempoRestanteMinutos;
        } 
        else 
        {
            return false; 
        }
    }

    public static function TodosPedidosPendientes($codigoPedido)
    {
        $query = "SELECT COUNT(*) AS total, COUNT(IF(pp.estado = 'Pendiente', 1, NULL)) AS pendientes
            FROM pedidos_productos pp
            INNER JOIN pedidos pe ON pp.id_pedido = pe.id
            WHERE pe.codigo = :codigoPedido
            HAVING total = pendientes";

        $parametros = [':codigoPedido' => $codigoPedido];

        $queryPreparada = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        $resultado = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        return ($resultado != false) ? ($resultado['total'] == $resultado['pendientes']) : false;
    }
}
