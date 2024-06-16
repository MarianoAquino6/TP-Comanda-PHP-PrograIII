<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/mesa.php';

enum ModoValidacionMesas
{
    case ActualizarEstado;
    case Registro;
    case BorradoCerrar;
}

class ValidadorMesasMW
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
                case ModoValidacionMesas::ActualizarEstado:
                    $this->validarParametrosActualizarEstado($parametros);
                    break;
                case ModoValidacionMesas::Registro:
                    $this->validarParametrosRegistro($parametros);
                    break;
                case ModoValidacionMesas::BorradoCerrar:
                    $this->validarParametrosBorradoCerrar($parametros);
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

    private function validarParametrosActualizarEstado($parametros)
    {
        if (!isset($parametros['codigo'], $parametros['estado']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!in_array($parametros['estado'], ['Con cliente esperando pedido', 'Con cliente comiendo', 'Con cliente pagando']))
        {
            throw new Exception('Formato de datos no valido');
        }

        if (!Mesa::MesaExiste($parametros['codigo']))
        {
            throw new Exception('La mesa indicada no existe');
        }
    }

    private function validarParametrosRegistro($parametros)
    {
        if (!isset($parametros['codigo']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (Mesa::MesaExiste($parametros['codigo']))
        {
            throw new Exception('La mesa indicada ya existe');
        }
    }

    private function validarParametrosBorradoCerrar($parametros)
    {
        if (!isset($parametros['codigo']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Mesa::MesaExiste($parametros['codigo']))
        {
            throw new Exception('La mesa indicada no existe');
        }
    }
}