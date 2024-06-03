<?php

require_once './models/producto.php';

class ProductoController
{
    public function RegistrarProducto($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
    
            $nuevoProducto = new Producto($parametros['tipo'], $parametros['nombre'], $parametros['precio'], $parametros['codigo']);
            $resultado = $nuevoProducto->Registrar();
    
            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Producto creado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear el producto");
            }
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ObtenerTodosLosProductos($request, $response, $args)
    {
        $lista = Producto::ObtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ObtenerProducto($request, $response, $args)
    {
        $producto = Producto::ObtenerUno($args['codigo']);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ActualizarPrecioProducto($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $resultado = Producto::ActualizarPrecio($parametros['codigo'], $parametros['precio']);

            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Precio actualizado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar del precio");
            }
        }
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function BorrarProducto($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $resultado = Producto::Borrar($parametros['codigo']);

            if ($resultado)
            {
                $payload = json_encode(array("mensaje" => "Producto borrado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
            else
            {
                throw new Exception("Ha surgido un error al borrar de el producto");
            }
        }
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}