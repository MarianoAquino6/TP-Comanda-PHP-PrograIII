<?php

require_once './models/mesa.php';

class MesaController
{
    public function RegistrarMesa($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
    
            $nuevaMesa = new Mesa($parametros['codigo'], "Sin cliente");
            $resultado = $nuevaMesa->Registrar();
    
            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Mesa creada con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear la mesa");
            }
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ObtenerTodasLasMesas($request, $response, $args)
    {
        $lista = Mesa::ObtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ObtenerMesa($request, $response, $args)
    {
        $mesa = Mesa::ObtenerUno($args['codigo']);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ActualizarEstadoMesa($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $resultado = Mesa::ActualizarEstado($parametros['codigo'], $parametros['estado']);

            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Estado actualizado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar el estado de la mesa");
            }
        }
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function BorrarMesa($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $resultado = Mesa::Borrar($parametros['codigo']);

            if ($resultado)
            {
                $payload = json_encode(array("mensaje" => "Mesa borrada con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
            else
            {
                throw new Exception("Ha surgido un error al borrar la mesa");
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