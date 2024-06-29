<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './validadoresInputs/validadorInputReseñas.php';

enum ModoValidacionReseñas
{
    case Registro;
}

class ValidadorReseñasMW
{
    public $modoValidacion;
    private $_validador;

    public function __construct($modoValidacion)
    {
        $this->modoValidacion = $modoValidacion;
        $this->_validador = new ValidadorInputReseñas();
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $parametros = $request->getParsedBody();

        try
        {
            switch ($this->modoValidacion)
            {
                case ModoValidacionReseñas::Registro:
                    $this->_validador->validarParametrosRegistrarReseña($parametros);
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