<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/producto.php';

enum ModoValidacionProductos
{
    case Registro;
    case ActualizacionPrecio;
    case Borrado;
}

class ValidadorProductosMW
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
                case ModoValidacionProductos::Registro:
                    $this->validarParametrosRegistro($parametros);
                    break;
                case ModoValidacionProductos::ActualizacionPrecio:
                    $this->validarParametrosActualizacionPrecio($parametros);
                    break;
                case ModoValidacionProductos::Borrado:
                    $this->validarParametrosBorrado($parametros);
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

    private function validarParametrosRegistro($parametros)
    {
        if (!isset($parametros['tipo'], $parametros['codigo'], $parametros['nombre'], $parametros['precio']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!in_array($parametros['tipo'], ['COMIDA', 'TRAGO', 'CERVEZA']) || !is_numeric($parametros['precio']))
        {
            throw new Exception('Formato de datos no valido');
        }

        if (Producto::ProductoExiste($parametros['codigo']))
        {
            throw new Exception('El codigo ingresado ya existe');
        }
    }

    private function validarParametrosActualizacionPrecio($parametros)
    {
        if (!isset($parametros['codigo'], $parametros['precio']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!is_numeric($parametros['precio']))
        {
            throw new Exception('Formato de datos no valido');
        }

        if (!Producto::ProductoExiste($parametros['codigo']))
        {
            throw new Exception('El codigo para el producto ingresado no existe');
        }
    }

    private function validarParametrosBorrado($parametros)
    {
        if (!isset($parametros['codigo']))
        {
            throw new Exception('Complete los parametros necesarios');
        }

        if (!Producto::ProductoExiste($parametros['codigo']))
        {
            throw new Exception('El codigo para el producto ingresado no existe');
        }
    }
}