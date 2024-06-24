<?php

require_once 'validadorInputBase.php';

class ValidadorInputUsuarios extends ValidadorInputBase
{
    public function validarParametrosRegistro($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['username', 'pass', 'sector']);

        $this->validarFormatoDatos($parametros);

        if (Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El usuario indicado ya existe');
        }
    }

    public function validarParametrosLogin($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['username', 'pass']);
    }

    public function validarParametrosModificacionUsername($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['usernameNuevo', 'usernameOriginal']);

        if (!Usuario::UsuarioExiste($parametros['usernameOriginal']))
        {
            throw new Exception('El usernameOriginal no existe');
        }

        if (Usuario::UsuarioExiste($parametros['usernameNuevo']))
        {
            throw new Exception('El username ya existe');
        }
    }

    public function validarParametrosModificacionPass($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['username', 'pass']);

        if (!$this->PassEsValida($parametros['pass']))
        {
            throw new Exception('La contraseña no es valida');
        }

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El username ingresado no existe');
        }
    }

    public function validarParametrosModificacionSector($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['username', 'sector']);

        if (!in_array($parametros['sector'], ['ADMIN', 'MOZO', 'CERVECERO', 'BARTENDER', 'COCINERO']))
        {
            throw new Exception('La contraseña no es valida o el sector no es válido');
        }

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El username ingresado no existe');
        }
    }

    public function validarParametrosBorrado($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['username']);

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El usuario a borrar no existe');
        }
    }

    public function validarParametrosLogsUsuario($parametros)
    {
        parent::validarCamposObligatorios($parametros, ['username']);

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El usuario ingresado no existe');
        }
    }

    private function validarFormatoDatos($parametros)
    {
        if (!$this->PassEsValida($parametros['pass']) || !in_array($parametros['sector'], ['ADMIN', 'MOZO', 'CERVECERO', 'BARTENDER', 'COCINERO']))
        {
            throw new Exception('Formato de datos no valido');
        }
    }

    private function PassEsValida($pass)
    {
        // Longitud entre 4 y 10 caracteres
        if (strlen($pass) < 4 || strlen($pass) > 10) {
            return false;
        }
    
        // Al menos una minúscula, una mayúscula y un número
        if (!preg_match('/[a-z]/', $pass) || !preg_match('/[A-Z]/', $pass) || !preg_match('/[0-9]/', $pass)) {
            return false;
        }
    
        return true;
    }
}