<?php 

require_once 'validadorInputBase.php';
require_once './models/mesa.php';

class ValidadorInputMesas extends ValidadorInputBase
{
    public function validarParametrosActualizarEstado($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigo', 'estado']);
        
        if (!in_array($parametros['estado'], ['Con cliente esperando pedido', 'Con cliente comiendo', 'Con cliente pagando']))
        {
            throw new Exception('Estado no valido. Los estados permitidos son: Con cliente esperando pedido, Con cliente comiendo, Con cliente pagando');
        }

        parent::validarExistenciaEntidad('Mesa', 'MesaExiste', $parametros['codigo'], 'La mesa indicada no existe');
    }

    public function validarParametrosRegistro($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigo']);

        if (Mesa::MesaExiste($parametros['codigo']))
        {
            throw new Exception('La mesa indicada ya existe');
        }
    }

    public function validarParametrosBorradoCerrar($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigo']);
        parent::validarExistenciaEntidad('Mesa', 'MesaExiste', $parametros['codigo'], 'La mesa indicada no existe');
    }

    public function validarParametrosFacturacionPeriodo($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigoMesa', 'fechaDesde', 'fechaHasta']);
        parent::validarExistenciaEntidad('Mesa', 'MesaExiste', $parametros['codigoMesa'], 'La mesa indicada no existe');
        $this->validarFormatoFechas($parametros);
    }

    private function validarFormatoFechas($parametros)
    {
        $fechaDesde = $parametros['fechaDesde'];
        $fechaHasta = $parametros['fechaHasta'];

        $formatoFecha = '/^\d{4}-\d{2}-\d{2}$/';

        if (!preg_match($formatoFecha, $fechaDesde) || !preg_match($formatoFecha, $fechaHasta)) {
            throw new Exception('El formato de fecha debe ser YYYY-MM-DD');
        }
    }
}