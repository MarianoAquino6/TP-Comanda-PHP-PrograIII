<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/usuario.php';

enum ModoValidacionUsuarios
{
    case Registro;
    case Login;
    case ModificacionUsername;
    case ModificacionPass;
    case ModificacionSector;
    case Borrado;
    case LogsUsuario;
}

class ValidadorUsuariosMW
{
    public $modoValidacion;

    public function __construct($modoValidacion)
    {
        $this->modoValidacion = $modoValidacion;
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $method = $request->getMethod();

        switch ($method) 
        {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $parametros = $request->getParsedBody();
                break;
            case 'GET':
                $parametros = $request->getQueryParams();
                break;
        }

        try
        {
            switch ($this->modoValidacion)
            {
                case ModoValidacionUsuarios::Registro:
                    $this->validarParametrosRegistro($parametros);
                    break;
                case ModoValidacionUsuarios::Login:
                    $this->validarParametrosLogin($parametros);
                    break;
                case ModoValidacionUsuarios::ModificacionUsername:
                    $this->validarParametrosModificacionUsername($parametros);
                    break;
                case ModoValidacionUsuarios::ModificacionPass:
                    $this->validarParametrosModificacionPass($parametros);
                    break;
                case ModoValidacionUsuarios::ModificacionSector:
                    $this->validarParametrosModificacionSector($parametros);
                    break;
                case ModoValidacionUsuarios::Borrado:
                    $this->validarParametrosBorrado($parametros);
                    break;
                case ModoValidacionUsuarios::LogsUsuario:
                    $this->validarParametrosLogsUsuario($parametros);
                    break;
            }

            $response = $handler->handle($request);
        }
        catch (Exception $e)
        {
            $response = new Response();
            $response->getBody()->write($e->getMessage());
            return $response->withStatus(400);
        }

        return $response;
    }

    private function validarParametrosRegistro($parametros)
    {
        if (!isset($parametros['username'], $parametros['pass'], $parametros['sector']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!$this->PassEsValida($parametros['pass']) || !in_array($parametros['sector'], ['ADMIN', 'MOZO', 'CERVECERO', 'BARTENDER', 'COCINERO']))
        {
            throw new Exception('Formato de datos no valido');
        }

        if (Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El username ya existe');
        }
    }

    private function validarParametrosLogin($parametros)
    {
        if (!isset($parametros['username'], $parametros['pass']))
        {
            throw new Exception('Complete los parametros necesarios');
        }
    }

    private function validarParametrosModificacionUsername($parametros)
    {
        if (!isset($parametros['usernameNuevo'], $parametros['usernameOriginal']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Usuario::UsuarioExiste($parametros['usernameOriginal']))
        {
            throw new Exception('El usernameOriginal no existe');
        }

        if (Usuario::UsuarioExiste($parametros['usernameNuevo']))
        {
            throw new Exception('El username ya existe');
        }
    }

    private function validarParametrosModificacionPass($parametros)
    {
        if (!isset($parametros['username'], $parametros['pass']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!$this->PassEsValida($parametros['pass']))
        {
            throw new Exception('La contraseña no es valida');
        }

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El username ingresado no existe');
        }
    }

    private function validarParametrosModificacionSector($parametros)
    {
        if (!isset($parametros['username'], $parametros['sector']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!in_array($parametros['sector'], ['ADMIN', 'MOZO', 'CERVECERO', 'BARTENDER', 'COCINERO']))
        {
            throw new Exception('La contraseña no es valida o el sector no es válido');
        }

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El username ingresado no existe');
        }
    }

    private function validarParametrosBorrado($parametros)
    {
        if (!isset($parametros['username']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El usuario a borrar no existe');
        }
    }

    private function validarParametrosLogsUsuario($parametros)
    {
        if (!isset($parametros['username']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Usuario::UsuarioExiste($parametros['username']))
        {
            throw new Exception('El usuario ingresado no existe');
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