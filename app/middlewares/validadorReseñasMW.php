<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/pedido.php';

enum ModoValidacionReseñas
{
    case Registro;
}

class ValidadorReseñasMW
{
    public $modoValidacion;

    public function __construct($modoValidacion)
    {
        $this->modoValidacion = $modoValidacion;
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $parametros = $request->getParsedBody();

        try
        {
            switch ($this->modoValidacion)
            {
                case ModoValidacionReseñas::Registro:
                    $this->validarParametrosRegistrarReseña($parametros);
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

    private function validarParametrosRegistrarReseña($parametros)
    {
        if (!isset($parametros['codigoPedido'], $parametros['puntuacionMesa'], $parametros['puntuacionMozo'], 
            $parametros['puntuacionCocinero'], $parametros['puntuacionRestaurante'], $parametros['experiencia']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Pedido::PedidoExiste($parametros['codigoPedido']))
        {
            throw new Exception('El pedido ingresado no existe');
        }
    }
}