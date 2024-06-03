<?php

class AsignacionPedido
{
    private $_idUsuario;
    private $_idPedido;

    public function __construct($idUsuario, $idPedido)
    {
        $this->_idUsuario = $idUsuario;
        $this->_idPedido = $idPedido;
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    asignaciones (id_usuario, id_pedido)
                    VALUES (:id_usuario, :id_pedido)";
        $queryPreparada = $acceso->PrepararConsulta($query);


        $queryPreparada->bindParam(':id_usuario', $this->_idUsuario, PDO::PARAM_INT);
        $queryPreparada->bindParam(':id_pedido', $this->_idPedido, PDO::PARAM_INT);

        return $queryPreparada->execute();
    }
}