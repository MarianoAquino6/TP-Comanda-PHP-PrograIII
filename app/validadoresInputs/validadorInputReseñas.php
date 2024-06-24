<?php

require_once 'validadorInputBase.php';

class ValidadorInputReseñas extends ValidadorInputBase
{
    public function validarParametrosRegistrarReseña($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigoPedido', 'puntuacionMesa', 'puntuacionMozo', 'puntuacionCocinero', 'puntuacionRestaurante', 'experiencia']);

        $this->validarPuntuaciones($parametros);

        parent::validarExistenciaEntidad('Pedido', 'PedidoExiste', $parametros['codigoPedido'], 'El pedido ingresado no existe');
    }

    private function validarPuntuaciones($parametros)
    {
        $puntuaciones = ['puntuacionMesa', 'puntuacionMozo', 'puntuacionCocinero', 'puntuacionRestaurante'];
        foreach ($puntuaciones as $campo) 
        {
            if (!isset($parametros[$campo]) || !is_numeric($parametros[$campo]) || $parametros[$campo] < 1 || $parametros[$campo] > 10)
            {
                throw new Exception("El campo " . $campo . " debe ser un número entre 1 y 10");
            }
        }
    }
}