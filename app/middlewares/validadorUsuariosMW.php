<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './validadoresInputs/validadorInputUsuarios.php';

enum ModoValidacionUsuarios
{
    case Registro;
    case Login;
    case ModificacionUsername;
    case ModificacionPass;
    case ModificacionSector;
    case Borrado;
    case LogsUsuario;
    case BajaReactivacion;
}

class ValidadorUsuariosMW
{
    private $_modoValidacion;
    private $_validador;

    public function __construct($modoValidacion)
    {
        $this->_modoValidacion = $modoValidacion;
        $this->_validador = new ValidadorInputUsuarios();
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
            switch ($this->_modoValidacion)
            {
                case ModoValidacionUsuarios::Registro:
                    $this->_validador->validarParametrosRegistro($parametros);
                    break;
                case ModoValidacionUsuarios::Login:
                    $this->_validador->validarParametrosLogin($parametros);
                    break;
                case ModoValidacionUsuarios::ModificacionUsername:
                    $this->_validador->validarParametrosModificacionUsername($parametros);
                    break;
                case ModoValidacionUsuarios::ModificacionPass:
                    $this->_validador->validarParametrosModificacionPass($parametros);
                    break;
                case ModoValidacionUsuarios::ModificacionSector:
                    $this->_validador->validarParametrosModificacionSector($parametros);
                    break;
                case ModoValidacionUsuarios::Borrado:
                case ModoValidacionUsuarios::BajaReactivacion;
                    $this->_validador->validarParametrosBorrado($parametros);
                    break;
                case ModoValidacionUsuarios::LogsUsuario:
                    $this->_validador->validarParametrosLogsUsuario($parametros);
                    break;
            }

            $response = $handler->handle($request);
        }
        catch (Exception $e)
        {
            $response = new Response();
            $errorData = [
                'error' => 'Error de validación',
                'message' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($errorData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $response;
    }
}