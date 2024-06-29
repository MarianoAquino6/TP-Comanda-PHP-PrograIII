<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/registroLogIn.php';

class LoggerMW
{
    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $response = $handler->handle($request);

        if ($response->getStatusCode() == 200)
        {
            $body = (string) $response->getBody();
            $parsedBody = json_decode($body, true);
            $sector = $parsedBody['sector'];

            $parametros = $request->getParsedBody();
        
            try 
            {
                $nuevoRegistroLogIn = new RegistroLogIn($parametros['username'], $sector);
                $nuevoRegistroLogIn->Guardar();
            } 
            catch (Exception $e)
            {
                $response = new Response();
                $errorData = [
                    'error' => 'Internal Server Error',
                    'message' => 'Error al guardar el log: ' . $e->getMessage()
                ];
                $response->getBody()->write(json_encode($errorData));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
        }

        return $response;
    }
}