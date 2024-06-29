<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './validadoresInputs/validadorInputPedidos.php';
require_once './validadoresInputs/validadorInputBase.php';

enum ModoValidacionPedidos
{
    case Registro;
    case TomarPedido;
    case TerminarPedido;
    case Cobrar;
    case TiempoRestante;
    case Foto;
    case CancelarTodo;
    case CancelarUno;
    case ObtenerFoto;
}

class ValidadorPedidosMW
{
    private $_modoValidacion;
    private $_validador;

    public function __construct($modoValidacion)
    {
        $this->_modoValidacion = $modoValidacion;
        $this->_validador = new ValidadorInputPedidos();
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $parametros = $this->getRequestParameters($request);

        try
        {
            $this->validarParametrosSegunModo($parametros);
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

    private function validarParametrosSegunModo($parametros)
    {
        switch ($this->_modoValidacion)
        {
            case ModoValidacionPedidos::Registro:
                $this->_validador->validarRegistro($parametros);
                break;
            case ModoValidacionPedidos::TomarPedido:
                $this->_validador->validarPedidoYProducto($parametros);
                $this->_validador->validarCampoNumerico($parametros, 'tiempoEstimado');
                $this->_validador->validarCampoNumerico($parametros, 'tiempoEstimado');
                break;
            case ModoValidacionPedidos::TerminarPedido:
            case ModoValidacionPedidos::CancelarUno:
                $this->_validador->validarPedidoYProducto($parametros);
                break;
            case ModoValidacionPedidos::Cobrar:
            case ModoValidacionPedidos::ObtenerFoto:
            case ModoValidacionPedidos::CancelarTodo:
                $this->_validador->validarPedido($parametros);
                break;
            case ModoValidacionPedidos::TiempoRestante:
                $this->_validador->validarPedidoYMesa($parametros);
                break;
            case ModoValidacionPedidos::Foto:
                $this->_validador->validarPedido($parametros);
                $this->_validador->validarFoto();
                break;
        }
    }
}