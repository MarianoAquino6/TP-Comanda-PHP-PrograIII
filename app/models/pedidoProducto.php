<?php 


class PedidoProducto
{
    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_EN_PREPARACION = 'En Preparación';
    const ESTADO_LISTO = 'Listo para servir';
    const ESTADO_CANCELADO = 'Cancelado';

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

    ///////////////////////////////////////////// CREATE ///////////////////////////////////////////////////////////

    public function Registrar()
    {
        $query = "INSERT INTO 
                pedidos_productos (id_pedido, id_producto, estado)
                VALUES (:id_pedido, :id_producto, :estado)";
        $parametros = [
            ':id_pedido' => $this->_idPedido,
            ':id_producto' => $this->_idProducto,
            ':estado' => self::ESTADO_PENDIENTE
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// UPDATE ///////////////////////////////////////////////////////////

    public function TomarPedido($idEmpleado, $tiempoEstimado)
    {
        $query = "UPDATE pedidos_productos 
                SET id_usuario = :id_usuario, estado = :estado, tiempo_estimado = :tiempo_estimado, 
                hora_inicio = :hora_inicio 
                WHERE id = :id";
    
        $parametros = [
            ':id_usuario' => $idEmpleado,
            ':estado' => self::ESTADO_EN_PREPARACION,
            ':tiempo_estimado' => $tiempoEstimado,
            ':hora_inicio' => date('Y-m-d H:i:s'),
            ':id' => $this->_id
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public function TerminarPedido()
    {
        $query = "UPDATE pedidos_productos 
                SET estado = :estado, hora_fin = :hora_fin 
                WHERE id = :id";
    
        $parametros = [
            ':estado' => self::ESTADO_LISTO,
            ':hora_fin' => date('Y-m-d H:i:s'),
            ':id' => $this->_id
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public static function Cancelar($idPedido, $idProducto)
    {
        $query = "UPDATE pedidos_productos
                SET estado = :estado
                WHERE id_pedido = :id_pedido AND id_producto = :id_producto AND estado != 'Cancelado'
                LIMIT 1";
    
        $parametros = [
            ':estado' => self::ESTADO_CANCELADO,
            ':id_pedido' => $idPedido,
            ':id_producto' => $idProducto
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// READ ///////////////////////////////////////////////////////////

    private static function procesarFila($fila)
    {
        if ($fila) 
        {
            return new PedidoProducto($fila['id_pedido'], $fila['id_producto'], $fila['estado'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public static function ObtenerPedidoProductoDisponible($idPedido, $idProducto)
    {
        $query = "SELECT pp.id_pedido, pp.id_producto, pp.estado, pp.id FROM pedidos_productos pp
                INNER JOIN pedidos p ON pp.id_pedido = p.id
                WHERE pp.id_producto = :id_producto AND pp.id_pedido = :id_pedido AND pp.estado = :estado 
                AND p.vigente = 1
                LIMIT 1";
        
        $parametros = [
            ':estado' => self::ESTADO_PENDIENTE,
            ':id_producto' => $idProducto,
            ':id_pedido' => $idPedido
        ];

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        return self::procesarFila($resultado->fetch(PDO::FETCH_ASSOC));
    }

    public static function ObtenerPedidoProductoEnPreparacion($idPedido, $idProducto, $idEmpleado)
    {
        $query = "SELECT pp.id_pedido, pp.id_producto, pp.estado, pp.id FROM pedidos_productos pp
                INNER JOIN pedidos p ON pp.id_pedido = p.id 
                WHERE pp.id_producto = :id_producto AND pp.id_pedido = :id_pedido AND pp.id_usuario = :id_empleado 
                AND pp.estado = :estado AND p.vigente = 1
                LIMIT 1";
        
        $parametros = [
            ':estado' => self::ESTADO_EN_PREPARACION,
            ':id_producto' => $idProducto,
            ':id_pedido' => $idPedido,
            ':id_empleado' => $idEmpleado
        ];

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        return self::procesarFila($resultado->fetch(PDO::FETCH_ASSOC));
    }

    public static function ObtenerPedidosDisponiblesSegunTipo($tipoProducto)
    {
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
            WHERE pr.tipo = :tipo AND pp.estado = :estado AND pe.vigente = 1";
        
        $parametros = [
            ':estado' => self::ESTADO_PENDIENTE,
            ':tipo' => $tipoProducto
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidosTomadosMozo($idMozo)
    {
        $query = "SELECT me.codigo, me.estado, pe.codigo, pr.nombre, pp.estado FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                WHERE pe.id_mozo = :id_mozo AND me.estado != 'Cerrada' AND pe.vigente = 1";
    
        $parametros = [
            ':id_mozo' => $idMozo
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidosTomadosEmpleado($idEmpleado)
    {
        $query = "SELECT me.codigo AS codigo_mesa, pe.codigo AS codigo_pedido, pr.nombre AS producto, pr.codigo AS codigo_producto FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                WHERE pp.id_usuario = :id_empleado AND pp.estado = :estado AND pe.vigente = 1";
    
        $parametros = [
            ':id_empleado' => $idEmpleado,
            ':estado' => self::ESTADO_EN_PREPARACION
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidosListosMozo($idMozo)
    {
        $query = "SELECT me.codigo, me.estado, pe.codigo, pr.nombre, pp.estado FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                WHERE pe.id_mozo = :id_mozo AND me.estado != 'Cerrada' AND pp.estado = :estado AND pe.vigente = 1";
    
        $parametros = [
            ':id_mozo' => $idMozo,
            ':estado' => self::ESTADO_LISTO
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function ObtenerPedidosConEstados()
    {
        $query = "SELECT me.codigo AS mesa, me.estado AS estado_mesa, pe.codigo AS pedido, pe.tiempo_total_estimado AS tiempo_total_estimado,
                pr.nombre AS producto, pp.estado AS estado_producto, pp.tiempo_estimado AS tiempo_estimado FROM pedidos_productos pp
                    INNER JOIN productos pr ON pp.id_producto = pr.id
                    INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                    INNER JOIN mesas me ON pe.id_mesa = me.id
                    WHERE me.estado != 'Cerrada' AND pe.vigente = 1";
        
        $parametros = [];

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);

        // Armo el array para que sea más lindo
        return self::ConstruirArrayPedidos($resultado->fetchAll(PDO::FETCH_ASSOC));
    }

    private static function ConstruirArrayPedidos($resultados)
    {
        $pedidos = [];

        foreach ($resultados as $fila) 
        {
            $mesaCodigo = $fila['mesa'];
            $pedidoCodigo = $fila['pedido'];
            $tiempoTotalEstimado = $fila['tiempo_total_estimado'];

            require_once './models/pedido.php';

            $tiempoRestante = Pedido::ObtenerTiempoRestante($mesaCodigo, $pedidoCodigo);

            // Inicializa la mesa si no existe
            if (!isset($pedidos[$mesaCodigo])) 
            {
                $pedidos[$mesaCodigo] = [
                    'mesa' => $mesaCodigo,
                    'estado_mesa' => $fila['estado_mesa'],
                    'pedido' => $pedidoCodigo,
                    'tiempo_total_estimado' => ($tiempoTotalEstimado !== null) ? $tiempoTotalEstimado : "Aun no definido",
                    'tiempo_restante' => ($tiempoRestante !== false) ? 
                        ($tiempoRestante >= 0 ? $tiempoRestante : "Lleva demorado " . abs($tiempoRestante) . " minutos") : 
                        "Aun no definido",
                    'productos_ordenados' => []
                ];
            }

            // Añade el producto al pedido
            $pedidos[$mesaCodigo]['productos_ordenados'][] = [
                'producto' => $fila['producto'],
                'estado_producto' => $fila['estado_producto'],
                'tiempo_estimado' => $fila['tiempo_estimado']
            ];
        }

        // Convierte el array asociativo en un array indexado
        return array_values($pedidos);
    }

    public static function ObtenerPedidosDemorados()
    {
        $query = "SELECT me.codigo AS mesa, pe.codigo AS pedido, pr.nombre AS producto, 
                (CEIL(TIME_TO_SEC(TIMEDIFF(pp.hora_fin, pp.hora_inicio)) / 60) - pp.tiempo_estimado) AS minutos_demorados
                FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                WHERE pp.estado != :estado AND (TIME_TO_SEC(TIMEDIFF(pp.hora_fin, pp.hora_inicio)) / 60) > pp.tiempo_estimado";
    
        $parametros = [
            ':estado' => self::ESTADO_CANCELADO
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerOperacionesPorSectorEmpleados()
    {
        $query = "SELECT u.sector, u.username, COUNT(*) AS cantidad_operaciones
                FROM pedidos_productos pp
                INNER JOIN usuarios u ON pp.id_usuario = u.id
                GROUP BY u.sector, u.username
                ORDER BY u.sector, u.username";

        $parametros = [];

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);

        return self::ProcesarResultados($resultado->fetchAll(PDO::FETCH_ASSOC));
    }

    private static function ProcesarResultados($resultados)
    {
        $operacionesPorSector = [];

        foreach ($resultados as $fila) 
        {
            $sector = $fila['sector'];
            $username = $fila['username'];
            $cantidadOperaciones = $fila['cantidad_operaciones'];

            if (!isset($operacionesPorSector[$sector])) 
            {
                $operacionesPorSector[$sector] = [];
            }

            $operacionesPorSector[$sector][] = [
                'username' => $username,
                'cantidad_operaciones' => $cantidadOperaciones
            ];
        }

        return $operacionesPorSector;
    }

    public static function ObtenerOperacionesPorSector()
    {
        $query = "SELECT u.sector, COUNT(*) AS cantidad_operaciones
                FROM pedidos_productos pp
                INNER JOIN usuarios u ON pp.id_usuario = u.id
                GROUP BY u.sector
                ORDER BY u.sector";

        $parametros = [];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerOperacionesPorEmpleados()
    {
        $query = "SELECT u.username, COUNT(*) AS cantidad_operaciones
                FROM pedidos_productos pp
                INNER JOIN usuarios u ON pp.id_usuario = u.id
                GROUP BY u.username
                ORDER BY u.username";

        $parametros = [];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerProductosOrdenadosPorVentasMayorAMenor()
    {
        $query = "SELECT pr.nombre, pr.codigo, COUNT(*) AS cantidad_comprada FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                WHERE pp.estado != :estado
                GROUP BY pr.nombre
                ORDER BY cantidad_comprada DESC";

        $parametros = [
            ':estado' => self::ESTADO_CANCELADO
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerProductosOrdenadosPorVentasMenorAMayor()
    {
        $query = "SELECT pr.nombre, pr.codigo, COUNT(*) AS cantidad_comprada FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                WHERE pp.estado != :estado
                GROUP BY pr.nombre
                ORDER BY cantidad_comprada ASC";

        $parametros = [
            ':estado' => self::ESTADO_CANCELADO
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPedidosCancelados()
    {
        $query = "SELECT me.codigo AS mesa, pe.codigo AS pedido, pr.nombre AS producto, pe.fecha_creacion AS fecha
                FROM pedidos_productos pp
                INNER JOIN productos pr ON pp.id_producto = pr.id
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                INNER JOIN mesas me ON pe.id_mesa = me.id
                WHERE pp.estado = :estado";
    
        $parametros = [
            ':estado' => self::ESTADO_CANCELADO
        ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function TodosLosPedidosEstanTomados($codigoPedido)
    {
        $query = "SELECT * FROM pedidos_productos pp 
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                WHERE pe.codigo = :codigo_pedido AND pp.estado = :pendiente";

        $parametros = [
            ':codigo_pedido' => $codigoPedido,
            ':pendiente' => self::ESTADO_PENDIENTE
        ];

        $resultados = AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);

        // Si hay resultados, significa que al menos un producto esta pendiente
        if (!empty($resultados)) 
        {
            return false; // Hay productos pendientes
        } 
        else 
        {
            return true; // Todos los productos están tomados
        }
    }

    public static function TodosLosPedidosEstanListos($codigoPedido)
    {
        // Consulta para contar los productos que no esten en listos o cancelados
        $query = "SELECT COUNT(*) AS cantidad
                FROM pedidos_productos pp
                INNER JOIN pedidos pe ON pp.id_pedido = pe.id
                WHERE pe.codigo = :codigo_pedido
                AND pp.estado != :listo
                AND pp.estado != :cancelado";

        $parametros = [
            ':codigo_pedido' => $codigoPedido,
            ':listo' => self::ESTADO_LISTO,
            ':cancelado' => self::ESTADO_CANCELADO
        ];

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC);

        // Si cantidad es 0, todos los productos están terminados; de lo contrario, no están todos terminados
        if ($resultado['cantidad'] == 0) 
        {
            return true; // Todos los productos están listos
        } 
        else 
        {
            return false; // No todos los productos están listos
        }
    }

    public static function PedidoTomadoNoCorrespondeAlUsuario($idUsuario, $idProducto)
    {
        $query = "SELECT * FROM pedidos_productos WHERE id_usuario = :id_usuario AND id_producto = :id_producto AND estado = :preparacion";
    
        $parametros = [
            ':id_usuario' => $idUsuario,
            ':id_producto' => $idProducto,
            ':preparacion' => self::ESTADO_EN_PREPARACION
        ];
    
        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC);
    
        // Devuelvo true si NO ENCUENTRA UN RESULTADO y false si lo encuentra
        return $resultado === false;
    }
}