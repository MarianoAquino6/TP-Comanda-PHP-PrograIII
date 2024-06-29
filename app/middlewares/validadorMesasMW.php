<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './validadoresInputs/validadorInputMesas.php';

enum ModoValidacionMesas
{
    case ActualizarEstado;
    case Registro;
    case BorradoCerrar;
    case FacturacionPeriodo;
    case Comentarios;
}

class ValidadorMesasMW
{
    private $_modoValidacion;
    private $_validador;

    public function __construct($modoValidacion)
    {
        $this->_modoValidacion = $modoValidacion;
        $this->_validador = new ValidadorInputMesas();
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $parametros = $this->getRequestParameters($request);

        try
        {
            switch ($this->_modoValidacion)
            {
                case ModoValidacionMesas::ActualizarEstado:
                    $this->_validador->validarParametrosActualizarEstado($parametros);
                    break;
                case ModoValidacionMesas::Registro:
                    $this->_validador->validarParametrosRegistro($parametros);
                    break;
                case ModoValidacionMesas::Comentarios:
                case ModoValidacionMesas::BorradoCerrar:
                    $this->_validador->validarParametrosBorradoCerrar($parametros);
                    break;
                case ModoValidacionMesas::FacturacionPeriodo:
                    $this->_validador->validarParametrosFacturacionPeriodo($parametros);
                    break;
            }

            $response = $handler->handle($request);
        }
        catch (Exception $e)
        {
            $response = new Response();
            $errorData = [
                'error' => 'Error',
                'message' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($errorData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

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
