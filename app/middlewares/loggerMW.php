<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/registroLogIn.php';

class LoggerMW
{
    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $parametros = $request->getParsedBody();
        
        try 
        {
            $nuevoRegistroLogIn = new RegistroLogIn($parametros['username']);
            $nuevoRegistroLogIn->Guardar();
        } 
        catch (Exception $e)
        {
            $response = new Response();
            $response->getBody()->write('Error al guardar el log: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        $response = $handler->handle($request);

        return $response;
    }
}