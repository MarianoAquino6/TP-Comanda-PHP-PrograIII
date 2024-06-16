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

    public function __construct($idMesa, $puntuacionMesa, $idMozo, $puntuacionMozo, $idCocinero, $puntuacionCocinero, 
    $puntuacionRestaurante, $experiencia)
    {
        $this->_idMesa = $idMesa;
        $this->_puntuacionMesa = $puntuacionMesa;
        $this->_idMozo = $idMozo;
        $this->_puntuacionMozo = $puntuacionMozo;
        $this->_idCocinero = $idCocinero;
        $this->_puntuacionCocinero = $puntuacionCocinero;
        $this->_puntuacionRestaurante = $puntuacionRestaurante;
        $this->_experiencia = $experiencia;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    reseñas (id_mesa, puntuacion_mesa, id_mozo, puntuacion_mozo, id_cocinero, puntuacion_cocinero, puntuacion_restaurante, experiencia)
                    VALUES (:id_mesa, :puntuacion_mesa, :id_mozo, :puntuacion_mozo, :id_cocinero, :puntuacion_cocinero, :puntuacion_restaurante, :experiencia)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':id_mesa', $this->_idMesa, PDO::PARAM_INT);
        $queryPreparada->bindParam(':puntuacion_mesa', $this->_puntuacionMesa, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_mozo', $this->_idMozo, PDO::PARAM_INT);
        $queryPreparada->bindParam(':puntuacion_mozo', $this->_puntuacionMozo, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_cocinero', $this->_idCocinero, PDO::PARAM_STR);
        $queryPreparada->bindParam(':puntuacion_cocinero', $this->_puntuacionCocinero, PDO::PARAM_INT);
        $queryPreparada->bindParam(':puntuacion_restaurante', $this->_puntuacionRestaurante, PDO::PARAM_INT);
        $queryPreparada->bindParam(':experiencia', $this->_experiencia, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ObtenerMejoresComentarios()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT experiencia FROM reseñas 
                    ORDER BY puntuacion_mesa DESC 
                    LIMIT 10";
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->execute();
    
        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }
}