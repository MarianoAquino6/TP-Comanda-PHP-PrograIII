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
            $idMozo = Usuario::ObtenerUno($parametros['username'])['id'];

            $nuevoPedido = new Pedido($idMesa, $idMozo, $parametros['codigoPedido'], $parametros['nombreCliente'], $parametros['fotoMesa']);
            $resultadoPedido = $nuevoPedido->Registrar();

            $idPedido = Pedido::ObtenerUno($parametros['codigoPedido'])['id'];

            foreach ($parametros['productos'] as $producto) 
            {
                $idProducto = Producto::ObtenerUno($producto['codigo'])['id'];
                $pedidoProducto = new PedidoProducto($idPedido, $idProducto, "Pendiente");
                $pedidoProducto->Registrar();
            }
    
            if ($resultadoPedido) 
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

    public function DefinirImporteTotal($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $idPedido = Pedido::ObtenerUno($parametros['codigoPedido'])['id'];

            $importeTotal = PedidoProducto::ObtenerImporteTotal($idPedido)['importe_total'];
            $resultado = Pedido::ActualizarImporteTotal($parametros['codigoPedido'], $importeTotal);

            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Importe total actualizado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar el importe total del pedido");
            }
        }
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function TomarPedido($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $idEmpleado = Usuario::ObtenerUno($parametros['username'])['id'];
            $idPedido = Pedido::ObtenerUno($parametros['codigoPedido'])['id'];
            $idProducto = Producto::ObtenerUno($parametros['codigo'])['id'];

            $idProductoTomado = PedidoProducto::ObtenerProductoDisponible($idPedido, $idProducto)['id'];

            $resultado = PedidoProducto::TomarPedido($idProductoTomado, $idEmpleado, $parametros['tiempo_estimado']);

            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Pedito tomado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al tomar el pedido");
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