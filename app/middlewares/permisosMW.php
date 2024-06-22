<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './JWT/JWTHandler.php';

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

            if (!in_array($sectorUsuario, $this->sectoresUsuario))
            {
                throw new Exception('El usuario no tiene los permisos necesarios.');
            }

            $response = $handler->handle($request);
        }
        catch (Exception $e)
        {
            $response = new Response();
            $response->getBody()->write($e->getMessage());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        return $response;
    }
}