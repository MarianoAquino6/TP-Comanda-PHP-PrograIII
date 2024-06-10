<?php

require_once './models/producto.php';

class ProductoController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public function RegistrarProducto($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
    
            $nuevoProducto = new Producto($parametros['tipo'], $parametros['codigo'], $parametros['nombre'], $parametros['precio']);
            $resultado = $nuevoProducto->Registrar();
    
            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Producto creado con éxito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear el producto");
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerTodosLosProductos($request, $response, $args)
    {
        try 
        {
            $lista = Producto::ObtenerTodos();
            return $this->CrearRespuesta($response, array("listaProductos" => $lista));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ActualizarPrecioProducto($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $productoAActualizar = Producto::ObtenerUno($parametros['codigo']);
            $resultado = $productoAActualizar->ActualizarPrecio($parametros['precio']);

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Precio actualizado con éxito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar el precio");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
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
                return $this->CrearRespuesta($response, array("mensaje" => "Producto borrado con éxito"));
            }
            else
            {
                throw new Exception("Ha surgido un error al borrar el producto");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }
}