<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

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
        $parametros = $request->getParsedBody();

        try
        {
            if (!isset($parametros['usernameCredencial'])) 
            {
                throw new Exception('El parametro usernameCredencial es requerido.');
            }

            $sectorUsuario = Usuario::ObtenerSector($parametros['usernameCredencial']);

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

            if ($e->getMessage() === 'El parametro usernameCredencial es requerido.') 
            {
                return $response->withStatus(400);
            } 
            else 
            {
                return $response->withStatus(403);
            }
        }

        return $response;
    }
}