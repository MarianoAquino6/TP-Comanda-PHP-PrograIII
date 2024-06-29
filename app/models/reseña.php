<?php 

class Reseña
{
    private $_idMesa;
    private $_puntuacionMesa;
    private $_idMozo;
    private $_puntuacionMozo;
    private $_idCocinero;
    private $_puntuacionCocinero;
    private $_puntuacionRestaurante;
    private $_experiencia;
    private $_codigoPedido;

    public function __construct($idMesa, $puntuacionMesa, $idMozo, $puntuacionMozo, $idCocinero, $puntuacionCocinero, 
    $puntuacionRestaurante, $experiencia, $codigoPedido)
    {
        $this->_idMesa = $idMesa;
        $this->_puntuacionMesa = $puntuacionMesa;
        $this->_idMozo = $idMozo;
        $this->_puntuacionMozo = $puntuacionMozo;
        $this->_idCocinero = $idCocinero;
        $this->_puntuacionCocinero = $puntuacionCocinero;
        $this->_puntuacionRestaurante = $puntuacionRestaurante;
        $this->_experiencia = $experiencia;
        $this->_codigoPedido = $codigoPedido;
    }

    ///////////////////////////////////////////// CREATE ///////////////////////////////////////////////////////////

    public function Registrar()
    {
        $query = "INSERT INTO 
                reseñas (id_mesa, codigo_pedido, puntuacion_mesa, id_mozo, puntuacion_mozo, id_cocinero, puntuacion_cocinero, puntuacion_restaurante, experiencia)
                VALUES (:id_mesa, :codigo_pedido, :puntuacion_mesa, :id_mozo, :puntuacion_mozo, :id_cocinero, :puntuacion_cocinero, :puntuacion_restaurante, :experiencia)";
        $parametros = [
            ':id_mesa' => $this->_idMesa,
            'codigo_pedido' => $this->_codigoPedido,
            ':puntuacion_mesa' => $this->_puntuacionMesa,
            ':id_mozo' => $this->_idMozo,
            ':puntuacion_mozo' => $this->_puntuacionMozo,
            ':id_cocinero' => $this->_idCocinero,
            ':puntuacion_cocinero' => $this->_puntuacionCocinero,
            ':puntuacion_restaurante' => $this->_puntuacionRestaurante,
            ':experiencia' => $this->_experiencia
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    /////////////////////////////////////////////  READ  ///////////////////////////////////////////////////////////

    public static function ObtenerMejoresComentarios()
    {
        $query = "SELECT experiencia, 
                    codigo_pedido,
                        (puntuacion_mesa + puntuacion_mozo + puntuacion_cocinero + puntuacion_restaurante) / 4 AS promedio_puntuaciones
                FROM reseñas 
                ORDER BY promedio_puntuaciones DESC 
                LIMIT 10";

        $resultados = AccesoDatos::EjecutarConsultaSelect($query, [])->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultados;
    }

    public static function ObtenerMejoresComentariosDeMesa($codigoMesa)
    {
        $query = "SELECT experiencia FROM reseñas r
                INNER JOIN mesas m ON r.id_mesa = m.id 
                WHERE m.codigo = :codigo_mesa
                ORDER BY puntuacion_mesa DESC 
                LIMIT 5";
            
            $parametros = [
                ':codigo_mesa' => $codigoMesa
            ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerPeoresComentariosDeMesa($codigoMesa)
    {
        $query = "SELECT experiencia FROM reseñas r
                INNER JOIN mesas m ON r.id_mesa = m.id 
                WHERE m.codigo = :codigo_mesa
                ORDER BY puntuacion_mesa ASC 
                LIMIT 5";
            
            $parametros = [
                ':codigo_mesa' => $codigoMesa
            ];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }
}