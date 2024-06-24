<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './validadoresInputs/validadorInputProductos.php';

enum ModoValidacionProductos
{
    case Registro;
    case ActualizacionPrecio;
    case Borrado;
    case Importar;
}

class ValidadorProductosMW
{
    private $_modoValidacion;
    private $_validador;

    public function __construct($modoValidacion)
    {
        $this->_modoValidacion = $modoValidacion;
        $this->_validador = new ValidadorInputProductos();
    }

    public function __invoke(Request $request, RequestHandler $handler) 
    {
        $parametros = $request->getParsedBody();

        try
        {
            switch ($this->_modoValidacion)
            {
                case ModoValidacionProductos::Registro:
                    $this->_validador->validarParametrosRegistro($parametros);
                    break;
                case ModoValidacionProductos::ActualizacionPrecio:
                    $this->_validador->validarParametrosActualizacionPrecio($parametros);
                    break;
                case ModoValidacionProductos::Borrado:
                    $this->_validador->validarParametrosBorrado($parametros);
                    break;
                case ModoValidacionProductos::Importar:
                    $this->_validador->validarCSV();
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
}