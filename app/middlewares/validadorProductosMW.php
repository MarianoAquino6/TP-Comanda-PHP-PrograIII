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
    case Importar;
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
                case ModoValidacionProductos::Importar:
                    $this->validarCSV();
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

    private function validarCSV()
    {
        // Valido que haya insertado un CSV
        if (!isset($_FILES['csv']))
        {
            throw new Exception('Inserte un archivo csv');
        }

        // Valido que sea efectivamente un archivo CSV
        $file_extension = pathinfo($_FILES['csv']['name'], PATHINFO_EXTENSION);
        if (strtolower($file_extension) != 'csv') 
        {
            throw new Exception('El archivo ingresado no es un archivo CSV');
        }

        // Valido que tenga el header correctamente
        $encabezadoEsperado = ['is_deleted', 'tipo', 'codigo', 'nombre', 'precio'];
        $archivo = fopen($_FILES['csv']['tmp_name'], 'r');

        if ($archivo != FALSE) 
        {
            $encabezado = fgetcsv($archivo, 1000, ",");
            
            if (count($encabezado) > count($encabezadoEsperado)) 
            {
                fclose($archivo);
                throw new Exception('Hay un numero mayor de campos que el esperado');
            }

            if ($encabezado != $encabezadoEsperado) 
            {
                fclose($archivo);
                throw new Exception('El header del archivo CSV no es válido');
            }
        }
        else
        {
            fclose($archivo);
            throw new Exception('Surgió un error al leer el archivo CSV');
        }

        while (($row = fgetcsv($archivo, 1000, ",")) != FALSE) 
        {
            // Valido que cada fila tenga el numero correcto de campos
            if (count($row) != count($encabezadoEsperado)) 
            {
                fclose($archivo);
                throw new Exception('El numero de campos de una fila no coincide con el numero de campos del encabezado');
            }

            // Valido que is_deleted sea 1 o 0
            if (!in_array($row[0], ['0', '1'], true))
            {
                fclose($archivo);
                throw new Exception('El campo "is_deleted" debe ser 1 o 0');
            }
            // Valido que el tipo sea uno admitido
            if (!in_array($row[1], ['COMIDA', 'TRAGO', 'CERVEZA'], true))
            {
                fclose($archivo);
                throw new Exception('El campo "tipo" debe ser "COMIDA", "TRAGO" o "CERVEZA"');
            }
            // Valido que el precio sea un numero
            if (!is_numeric($row[4]))
            {
                fclose($archivo);
                throw new Exception('El campo "precio" debe ser numerico');
            }
        }
        fclose($archivo);
    }
}