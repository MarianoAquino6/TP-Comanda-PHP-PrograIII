<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/mesa.php';
require_once './models/usuario.php';
require_once './models/producto.php';
require_once './models/pedido.php';

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
}

class ValidadorPedidosMW
{
    public $modoValidacion;
    private $_username;

    public function __construct($modoValidacion)
    {
        $this->modoValidacion = $modoValidacion;
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $method = $request->getMethod();

        switch ($method) 
        {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $parametros = $request->getParsedBody();
                break;
            case 'GET':
                $parametros = $request->getQueryParams();
                break;
        }

        $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
        $this->_username = JWTHandler::ObtenerData($tokenRecibido)->username;

        try
        {
            switch ($this->modoValidacion)
            {
                case ModoValidacionPedidos::Registro:
                    $this->validarRegistro($parametros, $request);
                    break;
                case ModoValidacionPedidos::TomarPedido:
                    $this->validarTomarPedido($parametros);
                    break;
                case ModoValidacionPedidos::TerminarPedido:
                    $this->validarTerminarPedido($parametros);
                    break;
                case ModoValidacionPedidos::Cobrar:
                    $this->validarCobrar($parametros);
                    break;
                case ModoValidacionPedidos::TiempoRestante:
                    $this->validarTiempoRestante($parametros);
                    break;
                case ModoValidacionPedidos::Foto:
                    $this->validarVincularFoto($parametros);
                    break;
                case ModoValidacionPedidos::CancelarUno:
                    $this->validarTerminarPedido($parametros);
                    break;
                case ModoValidacionPedidos::CancelarTodo:
                    $this->validarCobrar($parametros);
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

    private function validarRegistro($parametros, $request)
    {
        if (!isset($parametros['codigoMesa'], $parametros['nombreCliente'], $parametros['productos']) || empty($parametros['productos'])) 
        {
            throw new Exception('Complete los parametros necesarios');
        }

        foreach ($parametros['productos'] as $producto) 
        {
            if (!isset($producto['codigo'], $producto['cantidad']) || empty($producto['codigo']) || empty($producto['cantidad'])) 
            {
                throw new Exception('Los campos codigo y cantidad de los productos no pueden estar vacíos');
            }

            if (!is_numeric($producto['cantidad'])) 
            {
                throw new Exception('La cantidad del producto debe ser un número');
            }
        }

        if (!Mesa::MesaExiste($parametros['codigoMesa']))
        {
            throw new Exception('La mesa ingresada no existe');
        }

        foreach ($parametros['productos'] as $producto) 
        {
            if (!Producto::ProductoExiste($producto['codigo']))
            {
                throw new Exception('El producto ingresado no existe');
            }
        }
    }

    private function validarTomarPedido($parametros)
    {
        if (!isset($parametros['codigoPedido'], $parametros['codigoProducto'], $parametros['tiempoEstimado']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!is_numeric($parametros['tiempoEstimado'])) 
        {
            throw new Exception('El tiempo estimado debe ser un número');
        }

        if (!Pedido::PedidoExiste($parametros['codigoPedido']))
        {
            throw new Exception('El pedido ingresado no existe');
        }
        if (!Producto::ProductoExiste($parametros['codigoProducto']))
        {   
            throw new Exception('El producto ingresado no existe');
        }
    }

    private function validarTerminarPedido($parametros)
    {
        if (!isset($parametros['codigoPedido'], $parametros['codigoProducto']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Pedido::PedidoExiste($parametros['codigoPedido']))
        {
            throw new Exception('El pedido ingresado no existe');
        }
        if (!Producto::ProductoExiste($parametros['codigoProducto']))
        {   
            throw new Exception('El producto ingresado no existe');
        }
    }

    private function validarCobrar($parametros)
    {
        if (!isset($parametros['codigoPedido']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Pedido::PedidoExiste($parametros['codigoPedido']))
        {
            throw new Exception('El pedido ingresado no existe');
        }
    }

    private function validarTiempoRestante($parametros)
    {
        if (!isset($parametros['codigoMesa'], $parametros['codigoPedido']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Mesa::MesaExiste($parametros['codigoMesa']))
        {
            throw new Exception('La mesa ingresada no existe');
        }
        if (!Pedido::PedidoExiste($parametros['codigoPedido']))
        {
            throw new Exception('El pedido ingresado no existe');
        }
    }

    private function validarVincularFoto($parametros)
    {
        if (!isset($parametros['codigoPedido']))
        {
            throw new Exception('Complete los parametros necesarios: codigoPedido, foto');
        }

        if (!Pedido::PedidoExiste($parametros['codigoPedido']))
        {
            throw new Exception('El pedido ingresado no existe');
        }

        // Verifico si hubo errores al subir la foto
        switch ($_FILES['foto']['error'])
        {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No se subió ningún archivo');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception('Excede el tamaño máximo de archivo permitido');
            default:
                throw new Exception('Error desconocido al subir el archivo');
        }

        // Validar si el archivo es una imagen
        $file_extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) 
        {
            throw new Exception('El archivo no es una imagen');
        }
    }
}