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
    case ObtenerRegistros;
    case TomarPedido;
    case TerminarPedido;
    case Cobrar;
    case TiempoRestante;
}

class ValidadorPedidosMW
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
                case ModoValidacionPedidos::Registro:
                    $this->validarRegistro($parametros, $request);
                    break;
                case ModoValidacionPedidos::ObtenerRegistros:
                    $this->validarObtenerRegistros($parametros);
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
        if (!isset($parametros['codigoMesa'], $parametros['username'], $parametros['nombreCliente'], $parametros['fotoMesa'], $parametros['productos']) || empty($parametros['productos'])) 
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

        $mime = $request->getUploadedFiles()['fotoMesa']->getClientMediaType();
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) 
        {
            throw new Exception('La foto de la mesa debe ser una imagen válida (JPEG, PNG, GIF)');
        }

        if (!Mesa::MesaExiste($parametros['codigoMesa']))
        {
            throw new Exception('La mesa ingresada no existe');
        }
        if (!Usuario::UsuarioExiste($parametros['username']))
        {   
            throw new Exception('El username ingresado no existe');
        }

        foreach ($parametros['productos'] as $producto) 
        {
            if (!Producto::ProductoExiste($producto['codigo']))
            {
                throw new Exception('El producto ingresado no existe');
            }
        }
    }

    private function validarObtenerRegistros($parametros)
    {
        if (!isset($parametros['username']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Usuario::UsuarioExiste($parametros['username']))
        {   
            throw new Exception('El username ingresado no existe');
        }
    }

    private function validarTomarPedido($parametros)
    {
        if (!isset($parametros['username'], $parametros['codigoPedido'], $parametros['codigoProducto'], $parametros['tiempoEstimado']))
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
        if (!isset($parametros['username'], $parametros['codigoPedido'], $parametros['codigoProducto']))
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
}