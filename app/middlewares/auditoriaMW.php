<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/auditoria.php';
require_once './JWT/JWTHandler.php';

class AuditoriaMW
{
    public function __invoke(Request $request, RequestHandler $handler) 
    {
        try 
        {
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;

            $url = $_SERVER['REQUEST_URI'];

            $parametros = $this->getRequestParameters($request);
            $parametrosJson = $parametros ? json_encode($parametros) : null; // Convertir los parámetros en un JSON si existen, si no, establecer como null

            $nuevoRegistroAuditoria = new Auditoria($username, $url, $parametrosJson);
            $nuevoRegistroAuditoria->Guardar();
        } 
        catch (Exception $e)
        {
            $response = new Response();
            $errorData = [
                'error' => 'Internal Server Error',
                'message' => 'Error al guardar el log de auditoría: ' . $e->getMessage()
            ];
            $response->getBody()->write(json_encode($errorData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        $response = $handler->handle($request);

        return $response;
    }

    private function getRequestParameters(Request $request)
    {
        switch ($request->getMethod()) 
        {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                return $request->getParsedBody();
            case 'GET':
                return $request->getQueryParams();
        }
    }
}