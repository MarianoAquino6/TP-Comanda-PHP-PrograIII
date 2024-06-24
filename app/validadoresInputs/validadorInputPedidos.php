<?php

require_once 'validadorInputBase.php';

class ValidadorInputPedidos extends ValidadorInputBase
{
    public function validarRegistro($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigoMesa', 'nombreCliente', 'productos']);
        foreach ($parametros['productos'] as $producto) 
        {
            parent::validarCamposObligatorios($producto, ['codigo', 'cantidad']);
            parent::validarCampoNumerico($producto, 'cantidad');
            $this->validarExistenciaProducto($producto['codigo']);
        }
        $this->validarExistenciaMesa($parametros['codigoMesa']);
    }

    public function validarPedidoYProducto($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigoPedido', 'codigoProducto']);
        $this->validarExistenciaPedido($parametros['codigoPedido']);
        $this->validarExistenciaProducto($parametros['codigoProducto']);
    }

    public function validarPedido($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigoPedido']);
        $this->validarExistenciaPedido($parametros['codigoPedido']);
    }

    public function validarPedidoYMesa($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['codigoMesa', 'codigoPedido']);
        $this->validarExistenciaMesa($parametros['codigoMesa']);
        $this->validarExistenciaPedido($parametros['codigoPedido']);
    }

    public function validarFoto()
    {
        if (!isset($_FILES['foto'])) 
        {
            throw new Exception('Complete los parametros necesarios: foto');
        }

        switch ($_FILES['foto']['error'])
        {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No se subió ningún archivo');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception('Excede el tamaño máximo de archivo permitido');
            default:
                throw new Exception('Error desconocido al subir el archivo');
        }

        $file_extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) 
        {
            throw new Exception('El archivo no es una imagen');
        }
    }

    private function validarExistenciaMesa($codigoMesa)
    {
        parent::validarExistenciaEntidad('Mesa', 'MesaExiste', $codigoMesa, 'La mesa ingresada no existe');
    }

    private function validarExistenciaPedido($codigoPedido)
    {
        parent::validarExistenciaEntidad('Pedido', 'PedidoExiste', $codigoPedido, 'El pedido ingresado no existe');
    }

    private function validarExistenciaProducto($codigoProducto)
    {
        parent::validarExistenciaEntidad('Producto', 'ProductoExiste', $codigoProducto, 'El producto ingresado no existe');
    }
}