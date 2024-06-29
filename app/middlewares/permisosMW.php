<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './JWT/JWTHandler.php';
require_once './models/usuario.php';

class PermisosMW
{
    public $sectoresUsuario;

    public function __construct($sectoresUsuario)
    {
        $this->sectoresUsuario = $sectoresUsuario;
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        try
        {
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $sectorUsuario = $data->sector;
            $username = $data->username;

            if (!in_array($sectorUsuario, $this->sectoresUsuario))
            {
                throw new Exception('El usuario no tiene los permisos necesarios.');
            }

            if (Usuario::EstaInhabilitado($username))
            {
                throw new Exception('Su usuario ha sido dado de baja');
            }

            $response = $handler->handle($request);
        }
        catch (Exception $e)
        {
            $response = new Response();
            $errorData = [
                'error' => 'Forbidden',
                'message' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($errorData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        return $response;
    }
}