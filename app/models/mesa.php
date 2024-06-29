<?php 

class Mesa 
{
    private $_id;
    private $_codigo;
    private $_estado;

    public function __construct($codigo, $estado, $id=null)
    {
        $this->_codigo = $codigo;
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
                mesas (is_deleted, codigo, estado, fecha_creacion, fecha_modificacion)
                VALUES (0, :codigo, :estado, :fecha_creacion, :fecha_modificacion)";

        $parametros = array(
            ':codigo' => $this->_codigo,
            ':estado' => $this->_estado,
            ':fecha_creacion' => date('Y-m-d H:i:s'),
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        );

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// UPDATE ///////////////////////////////////////////////////////////

    public function ActualizarEstado($estado)
    {
        $this->_estado = $estado;

        $query = "UPDATE mesas 
                    SET estado = :estado, fecha_modificacion = :fecha_modificacion 
                    WHERE codigo = :codigo AND is_deleted = 0";

        $parametros = array(
            ':codigo' => $this->_codigo,
            ':estado' => $this->_estado,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        );

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// DELETE ///////////////////////////////////////////////////////////

    public function Borrar()
    {
        $query = "UPDATE mesas 
                SET is_deleted = 1, fecha_modificacion = :fecha_modificacion 
                WHERE codigo = :codigo";

        $parametros = array(
            ':codigo' => $this->_codigo,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        );

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// READ ///////////////////////////////////////////////////////////

    public static function ObtenerUno($codigoMesa)
    {
        $query = "SELECT codigo, estado, id 
                FROM mesas 
                WHERE codigo = :codigo AND is_deleted = 0";

        $parametros = array(':codigo' => $codigoMesa);

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if ($fila) 
        {
            return new Mesa($fila['codigo'], $fila['estado'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public static function ObtenerListadoMesasConEstados()
    {
        $query = "SELECT id, codigo, estado, fecha_creacion FROM mesas WHERE is_deleted = 0";
        $resultado = AccesoDatos::EjecutarConsultaSelect($query, array());

        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesaMasUsada()
    {
        $query = "SELECT m.*, COUNT(p.id) AS cantidad_pedidos
                FROM mesas m
                INNER JOIN pedidos p ON m.id = p.id_mesa
                GROUP BY m.id
                ORDER BY COUNT(p.id) DESC
                LIMIT 1";

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, []);
        
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesaMenosUsada()
    {
        $query = "SELECT m.*, COALESCE(COUNT(p.id), 0) AS cantidad_pedidos
                FROM mesas m
                LEFT JOIN pedidos p ON m.id = p.id_mesa
                GROUP BY m.id
                ORDER BY COALESCE(COUNT(p.id), 0) ASC
                LIMIT 1";

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, []);
        
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesaMayorFacturacion()
    {
        $query = "SELECT m.codigo AS mesa, SUM(p.importe_total) AS importe_total_facturado
                FROM mesas m
                INNER JOIN pedidos p ON m.id = p.id_mesa
                GROUP BY m.codigo
                ORDER BY SUM(p.importe_total) DESC
                LIMIT 1";

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, array());
        
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesaMenorFacturacion()
    {
        $query = "SELECT m.codigo AS mesa, COALESCE(SUM(p.importe_total), 0) AS importe_total_facturado
                FROM mesas m
                LEFT JOIN pedidos p ON m.id = p.id_mesa
                GROUP BY m.codigo
                ORDER BY importe_total_facturado ASC
                LIMIT 1";

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, array());
        
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesasOrdenadasPorImporteMaximoMenorMayor()
    {
        $query = "SELECT m.codigo AS mesa, COALESCE(max_importe.importe_maximo, 0) AS importe_maximo
                FROM mesas m
                LEFT JOIN (
                    SELECT id_mesa, MAX(importe_total) AS importe_maximo
                    FROM pedidos
                    GROUP BY id_mesa
                ) AS max_importe ON m.id = max_importe.id_mesa
                ORDER BY importe_maximo ASC";

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, []);

        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesaMayorImporte()
    {
        $query = "SELECT m.codigo AS mesa, p.importe_total AS importe
                FROM mesas m
                INNER JOIN pedidos p ON m.id = p.id_mesa
                ORDER BY p.importe_total DESC
                LIMIT 1";

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, array());
        
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerMesaMenorImporte()
    {
        $query = "SELECT m.codigo AS mesa, COALESCE(p.importe_total, 0) AS importe
                FROM mesas m
                LEFT JOIN (
                    SELECT id_mesa, SUM(importe_total) AS importe_total
                    FROM pedidos
                    GROUP BY id_mesa
                ) p ON m.id = p.id_mesa
                ORDER BY COALESCE(p.importe_total, 0) ASC
                LIMIT 1";

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, array());
        
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public static function ObtenerFacturacionEnPeriodo($codigoMesa, $fechaDesde, $fechaHasta)
    {
        $query = "SELECT SUM(p.importe_total) AS importe
                FROM mesas m
                INNER JOIN pedidos p ON m.id = p.id_mesa
                WHERE (m.codigo = :codigo_mesa) AND (p.fecha_creacion BETWEEN :fecha_desde AND :fecha_hasta)
                GROUP BY m.codigo";

        $parametros = array(
            ':codigo_mesa' => $codigoMesa,
            ':fecha_desde' => $fechaDesde,
            ':fecha_hasta' => $fechaHasta
        );

        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);

        // Verificar si $resultado es false o no tiene filas
        if ($resultado === false || $resultado->rowCount() == 0) 
        {
            return 0; // Otra acción o valor predeterminado según el caso
        }

        // Obtener el importe total
        $importeTotal = $resultado->fetch(PDO::FETCH_ASSOC)['importe'];

        // Si $importeTotal es null (puede ocurrir si no hay registros), retornar 0
        return $importeTotal !== null ? $importeTotal : 0;
    }

    public static function MesaExiste($codigo)
    {
        $query = "SELECT codigo FROM mesas WHERE codigo = :codigo AND is_deleted = 0";

        $parametros = array(':codigo' => $codigo);
        $resultado = AccesoDatos::EjecutarConsultaSelect($query, $parametros);

        $mesa = $resultado->fetch(PDO::FETCH_ASSOC);

        return ($mesa != false);
    }
}