<?php 

require_once './models/pedido.php';
require_once './models/asignacionPedido.php';
require_once './models/pedidoProducto.php';
require_once './models/mesa.php';
require_once './models/usuario.php';
require_once './models/producto.php';

class PedidoController
{
    public function RegistrarPedido($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
            
            $idMesa = Mesa::ObtenerUno($parametros['codigoMesa'])['id'];
            $idMozo = Usuario::ObtenerUno($parametros['mozo'])['id'];

            $nuevoPedido = new Pedido($idMesa, $parametros['codigoPedido'], $parametros['nombreCliente'], "Pedido tomado por el mozo", $parametros['fotoMesa']);
            $resultadoPedido = $nuevoPedido->Registrar();

            $idPedido = Pedido::ObtenerUno($parametros['codigoPedido'])['id'];
            $nuevaAsignacion = new AsignacionPedido($idMozo, $idPedido);
            $resultadoAsignacion = $nuevaAsignacion->Registrar();

            foreach ($parametros['productos'] as $producto) 
            {
                $idProducto = Producto::ObtenerUno($producto['codigo'])['id'];
                $pedidoProducto = new PedidoProducto($idPedido, $idProducto, $producto['cantidad']);
                $pedidoProducto->Registrar();
            }

            $importeTotal = PedidoProducto::ObtenerImporteTotal($idPedido);
            $resultadoActualizacion = $nuevoPedido->ActualizarImporteTotal($importeTotal);
    
            if ($resultadoPedido && $resultadoAsignacion && $resultadoActualizacion) 
            {
                $payload = json_encode(array("mensaje" => "Pedido creado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear el pedido");
            }
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ObtenerTodosLosPedidos($request, $response, $args)
    {
        $lista = Pedido::ObtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ObtenerPedido($request, $response, $args)
    {
        $pedido = Pedido::ObtenerUno($args['codigo']);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ActualizarEstadoPedido($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $resultado = Pedido::ActualizarEstado($parametros['codigo'], $parametros['estado']);

            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Estado actualizado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar el estado del pedido");
            }
        }
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function BorrarPedido($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $resultado = Pedido::Borrar($parametros['codigo']);

            if ($resultado)
            {
                $payload = json_encode(array("mensaje" => "Pedido borrado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
            else
            {
                throw new Exception("Ha surgido un error al borrar el pedido");
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