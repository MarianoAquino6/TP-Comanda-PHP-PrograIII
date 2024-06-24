<?php

class ValidadorInputBase
{
    protected function validarCamposObligatorios($parametros, $campos)
    {
        foreach ($campos as $campo) 
        {
            if (!isset($parametros[$campo]) || empty($parametros[$campo])) 
            {
                throw new Exception("El campo " . $campo . " es obligatorio");
            }
        }
    }

    public function validarCampoNumerico($parametros, $campo)
    {
        if (!is_numeric($parametros[$campo])) 
        {
            throw new Exception("El campo " . $campo . " debe ser un número");
        }
    }

    protected function validarExistenciaEntidad($entidad, $metodo, $codigo, $mensajeError)
    {
        if (!call_user_func([$entidad, $metodo], $codigo))
        {
            throw new Exception($mensajeError);
        }
    }
}