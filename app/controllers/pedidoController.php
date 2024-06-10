<?php 

require_once './models/pedido.php';
require_once './models/asignacionPedido.php';
require_once './models/pedidoProducto.php';
require_once './models/mesa.php';
require_once './models/usuario.php';
require_once './models/producto.php';

class PedidoController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public function RegistrarPedidoYActualizarMesa($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();

            $mesa = Mesa::ObtenerUno($parametros['codigoMesa']);
            $mozo = Usuario::ObtenerUno($parametros['username']);
            $codigoPedido = substr(bin2hex(random_bytes(6)), 0, 6);

            $nuevoPedido = new Pedido($mesa->GetId(), $mozo->GetId(), $codigoPedido, $parametros['nombreCliente'], $parametros['fotoMesa']);
            $idPedido = $nuevoPedido->RegistrarYDevolverId();

            foreach ($parametros['productos'] as $producto) 
            {
                $producto = Producto::ObtenerUno($producto['codigo']);
                $cantidad = $producto['cantidad'];

                for ($i = 0; $i < $cantidad; $i++) 
                {
                    $pedidoProducto = new PedidoProducto($idPedido, $producto->GetId(), "Pendiente");
                    $pedidoProducto->Registrar();
                }
            }
    
            if ($idPedido != false) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Pedido creado con éxito, el codigo es". $nuevoPedido->GetCodigo()));
            }
            else
            {
                throw new Exception("Ha surgido un error al crear el pedido");
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    //VER ESTO PARA QUE LOS SOCIOS PUEDAN VER TODOS LOS PEDIDOS CON SUS RESPECTIVOS ESTADOS
    // public function ObtenerTodosLosPedidos($request, $response, $args)
    // {
    //     $lista = Pedido::ObtenerTodos();
    //     $payload = json_encode(array("listaPedidos" => $lista));

    //     $response->getBody()->write($payload);
    //     return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    // }

    public function TomarPedido($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $empleado = Usuario::ObtenerUno($parametros['username']);
            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $producto = Producto::ObtenerUno($parametros['codigoProducto']);
            $pedidoProductoDisponible = PedidoProducto::ObtenerPedidoProductoDisponible($pedido->GetId(), $producto->GetId());

            $resultado = $pedidoProductoDisponible->TomarPedido($empleado->GetId(), $parametros['tiempo_estimado']);

            if ($resultado)
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Pedido tomado con éxito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al tomar el pedido");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function TerminarPedido($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $empleado = Usuario::ObtenerUno($parametros['username']);
            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $producto = Producto::ObtenerUno($parametros['codigoProducto']);
            $pedidoProductoEnPreparacion = PedidoProducto::ObtenerPedidoProductoEnPreparacion($pedido->GetId(), $producto->GetId(), $empleado->GetId());

            $resultado = $pedidoProductoEnPreparacion->TerminarPedido();

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Pedido terminado con éxito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al terminar el pedido");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerPedidosPendientesSegunSector($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();

            $usuario = Usuario::ObtenerUno($parametros['username']);
            $pedidosDisponibles = null;

            switch ($usuario->GetSector()) 
            {
                case "COCINERO":
                    $pedidosDisponibles = PedidoProducto::ObtenerPedidosDisponiblesSegunTipo("COMIDA");
                    break;
                case "BARTENDER":
                    $pedidosDisponibles = PedidoProducto::ObtenerPedidosDisponiblesSegunTipo("TRAGO");
                    break;
                case "CERVECEROS":
                    $pedidosDisponibles = PedidoProducto::ObtenerPedidosDisponiblesSegunTipo("CERVEZA");
                    break;
                default:
                    throw new Exception("Sector no reconocido");
            }

            return $this->CrearRespuesta($response, array("listaPedidosDisponibles" => $pedidosDisponibles));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerPedidosTomadosMozo($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();

            $usuario = Usuario::ObtenerUno($parametros['username']);
            $pedidosTomados = PedidoProducto::ObtenerPedidosTomadosMozo($usuario->GetId());

            return $this->CrearRespuesta($response, array("listaPedidosTomados" => $pedidosTomados));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerPedidosListosMozo($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();

            $usuario = Usuario::ObtenerUno($parametros['username']);
            $pedidosListos = PedidoProducto::ObtenerPedidosListosMozo($usuario->GetId());

            return $this->CrearRespuesta($response, array("listaPedidosListos" => $pedidosListos));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerTodosPedidosConEstados($request, $response, $args)
    {
        try 
        {
            $pedidosConEstados = PedidoProducto::ObtenerPedidosConEstados();
            return $this->CrearRespuesta($response, array("listaPedidosEstados" => $pedidosConEstados));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function CobrarMesa($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $resultado = $pedido->ActualizarImporteTotal();

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Importe total actualizado con éxito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar el importe total");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerTiempoRestante($request, $response, $args)
    {
        $codigoMesa = $args['codigoMesa'];
        $codigoPedido = $args['codigoPedido'];
        $tiempoRestante = Pedido::ObtenerTiempoRestante($codigoMesa, $codigoPedido);

        return $this->CrearRespuesta($response, array("mensaje" => "El tiempo restante es de " . $tiempoRestante. " minutos"));
    }
}