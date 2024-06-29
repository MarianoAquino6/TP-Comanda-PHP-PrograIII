<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once('./JWT/JWTHandler.php');

enum ModoJWT
{
    case CrearToken;
    case VerificarToken;
}

class JWTMW
{
    public $modoJWT;

    public function __construct($modoJWT)
    {
        $this->modoJWT = $modoJWT;
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        //EN CASO DE QUE EL USUARIO SE ESTÉ LOGUEANDO
        if ($this->modoJWT == ModoJWT::CrearToken)
        {
            $response = $handler->handle($request);

            if ($response->getStatusCode() == 200)
            {
                $body = (string) $response->getBody();
                $parsedBody = json_decode($body, true);
                $parametros = $request->getParsedBody();
                //Me guardo 
                $data = array(
                    'username' => $parametros['username'],
                    'sector' => $parsedBody['sector']
                );
            
                try 
                {
                    $token = JWTHandler::CrearToken($data);
                    return $this->MeterTokenEnResponse($token);
                } 
                catch (Exception $e)
                {
                    $response = new Response();
                    $errorData = [
                        'error' => 'Internal Server Error',
                        'message' => 'Error al crear el token: ' . $e->getMessage()
                    ];
                    $response->getBody()->write(json_encode($errorData));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
                }
            }

            return $response;
        }

        try
        {
            // EN CASO DE QUE EL USUARIO NO SE ESTÉ LOGUEANDO VERIFICO QUE EL TOKEN SEA VALIDO
            $token = JWTHandler::ObtenerTokenEnviado($request);
            JWTHandler::VerificarToken($token);
            $response = $handler->handle($request);
        }
        catch (Exception $e) 
        {
            $response = new Response();
            $payload = json_encode(['Token Error' => $e->getMessage()]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        return $response;
    }

    private function MeterTokenEnResponse($token)
    {
        // Agregar el token a la respuesta
        $response = new Response();
        $response->getBody()->write(json_encode(['token' => $token]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

}