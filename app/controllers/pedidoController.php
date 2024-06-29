<?php 

require_once './models/pedido.php';
require_once './models/pedidoProducto.php';
require_once './models/mesa.php';
require_once './models/usuario.php';
require_once './models/producto.php';
require_once './JWT/JWTHandler.php';

class PedidoController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    //////////////////////////////////////////// GET /////////////////////////////////////////////////

    public function ObtenerPedidosPendientesSegunSector($request, $response, $args)
    {
        try 
        {
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;

            $usuario = Usuario::ObtenerUno($username);
            $pedidosDisponibles = null;

            switch ($usuario->GetSector()) 
            {
                case "COCINERO":
                    $pedidosDisponibles = PedidoProducto::ObtenerPedidosDisponiblesSegunTipo("COMIDA");
                    break;
                case "BARTENDER":
                    $pedidosDisponibles = PedidoProducto::ObtenerPedidosDisponiblesSegunTipo("TRAGO");
                    break;
                case "CERVECERO":
                    $pedidosDisponibles = PedidoProducto::ObtenerPedidosDisponiblesSegunTipo("CERVEZA");
                    break;
                default:
                    throw new Exception("Sector no reconocido");
            }

            if ($pedidosDisponibles == false)
            {
                throw new Exception("No hay pedidos pendientes");
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
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;

            $usuario = Usuario::ObtenerUno($username);
            $pedidosTomados = PedidoProducto::ObtenerPedidosTomadosMozo($usuario->GetId());

            if ($pedidosTomados == false)
            {
                return $this->CrearRespuesta($response, array("listaPedidosTomados" => "Aun no ha tomado pedidos. Revisar estado de la mesa"));
            }

            return $this->CrearRespuesta($response, array("listaPedidosTomados" => $pedidosTomados));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerPedidosTomadosEmpleado($request, $response, $args)
    {
        try 
        {
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;

            $usuario = Usuario::ObtenerUno($username);
            $pedidosTomados = PedidoProducto::ObtenerPedidosTomadosEmpleado($usuario->GetId());

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
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;

            $usuario = Usuario::ObtenerUno($username);
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

            if ($pedidosConEstados == false)
            {
                return $this->CrearRespuesta($response, array("listaPedidosEstados" => "No hay pedidos vigentes"));
            }

            return $this->CrearRespuesta($response, array("listaPedidosEstados" => $pedidosConEstados));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerTiempoRestante($request, $response, $args)
    {
        $parametros = $request->getQueryParams();

        $codigoMesa = $parametros['codigoMesa'];
        $codigoPedido = $parametros['codigoPedido'];
        $tiempoRestante = Pedido::ObtenerTiempoRestante($codigoMesa, $codigoPedido);

        //Si no me trajo ningun resultado es porque todos los pedidos estan aun pendientes o porque estan todos listos
        if (!$tiempoRestante)
        {
            // Corroboro si todos los pedidos estan pendientes
            if (Pedido::TodosPedidosPendientes($codigoPedido))
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Aun no se ha comenzado a preparar el pedido, por lo tanto no hay un tiempo estimado"));
            }

            // Entonces todos los pedidos estan listos para servir
            return $this->CrearRespuesta($response, array("mensaje" => "Todos sus pedidos estan listos para servir"));
        }

        if ($tiempoRestante < 0)
        {
            return $this->CrearRespuesta($response, array("mensaje" => "Su pedido lleva demorado " . (intval($tiempoRestante*-1)). " minutos mas de lo esperado"));
        }

        return $this->CrearRespuesta($response, array("mensaje" => "Su pedido esta siendo preparado! El tiempo restante es de " . $tiempoRestante. " minutos"));
    }

    public function ObtenerDemorasProductos($request, $response, $args)
    {
        try 
        {
            $productosDemorados = PedidoProducto::ObtenerPedidosDemorados();

            if (!$productosDemorados)
            {
                throw new Exception("No existen productos con demoras");
            }

            return $this->CrearRespuesta($response, array("listaProductosDemora" => $productosDemorados));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerDemorasPedidos($request, $response, $args)
    {
        try 
        {
            $pedidosDemorados = Pedido::ObtenerPedidosDemorados();

            if (!$pedidosDemorados)
            {
                throw new Exception("No existen pedidos con demoras");
            }

            return $this->CrearRespuesta($response, array("listaPedidosDemora" => $pedidosDemorados));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerOperacionesPorSectorEmpleados($request, $response, $args)
    {
        try 
        {
            $operacionesPorSector = PedidoProducto::ObtenerOperacionesPorSectorEmpleados();

            if (!$operacionesPorSector)
            {
                throw new Exception("Aun no existen operaciones");
            }

            return $this->CrearRespuesta($response, array("operacionesPorSector" => $operacionesPorSector));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerOperacionesPorSector($request, $response, $args)
    {
        try 
        {
            $operacionesPorSector = PedidoProducto::ObtenerOperacionesPorSector();

            if (!$operacionesPorSector)
            {
                throw new Exception("Aun no existen operaciones");
            }

            return $this->CrearRespuesta($response, array("operacionesPorSector" => $operacionesPorSector));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerOperacionesPorEmpleados($request, $response, $args)
    {
        try 
        {
            $operacionesPorSector = PedidoProducto::ObtenerOperacionesPorEmpleados();

            if (!$operacionesPorSector)
            {
                throw new Exception("Aun no existen operaciones");
            }

            return $this->CrearRespuesta($response, array("operacionesPorEmpleados" => $operacionesPorSector));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerProductosOrdenadosPorVentasMayorAMenor($request, $response, $args)
    {
        try 
        {
            $productosOrdenadosPorVentas = PedidoProducto::ObtenerProductosOrdenadosPorVentasMayorAMenor();

            if (!$productosOrdenadosPorVentas)
            {
                throw new Exception("Aun no se vendieron productos");
            }

            return $this->CrearRespuesta($response, array("productosOrdenadosPorVentas" => $productosOrdenadosPorVentas));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerProductosOrdenadosPorVentasMenorAMayor($request, $response, $args)
    {
        try 
        {
            $productosOrdenadosPorVentas = PedidoProducto::ObtenerProductosOrdenadosPorVentasMenorAMayor();

            if (!$productosOrdenadosPorVentas)
            {
                throw new Exception("Aun no se vendieron productos");
            }

            return $this->CrearRespuesta($response, array("productosOrdenadosPorVentas" => $productosOrdenadosPorVentas));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerPedidosCancelados($request, $response, $args)
    {
        try
        {
            $resultado = PedidoProducto::ObtenerPedidosCancelados();

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("Pedidos Cancelados" => $resultado));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al obtener los pedidos cancelados");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerFoto($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getQueryParams();
            $codigoPedido = $parametros['codigoPedido'];
            $pedido = Pedido::ObtenerUno($codigoPedido);
            $rutaFoto = $pedido->ObtenerFoto();

            if (!file_exists($rutaFoto)) 
            {
                throw new Exception("La foto no se encuentra en el servidor");
            }

            $imagen = file_get_contents($rutaFoto);
            $tipoImagen = mime_content_type($rutaFoto);

            $response = $response->withHeader('Content-Type', $tipoImagen)
                             ->withHeader('Content-Disposition', 'inline; filename="' . basename($rutaFoto) . '"');

            $response->getBody()->write($imagen);
            return $response;
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    public function RegistrarPedidoYActualizarMesa($request, $response, $args)
    {
        try 
        {
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;
            $parametros = $request->getParsedBody();

            $mesa = Mesa::ObtenerUno($parametros['codigoMesa']);
            $mozo = Usuario::ObtenerUno($username);
            $codigoPedido = substr(bin2hex(random_bytes(3)), 0, 5);

            $nuevoPedido = new Pedido($mesa->GetId(), $mozo->GetId(), $codigoPedido, $parametros['nombreCliente']);
            $idPedido = $nuevoPedido->RegistrarYDevolverId();

            foreach ($parametros['productos'] as $producto) 
            {
                $objetoProducto = Producto::ObtenerUno($producto['codigo']);
                $cantidad = $producto['cantidad'];

                for ($i = 0; $i < $cantidad; $i++) 
                {
                    $pedidoProducto = new PedidoProducto($idPedido, $objetoProducto->GetId(), "Pendiente");
                    $pedidoProducto->Registrar();
                }
            }
    
            if ($idPedido != false) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Pedido creado con exito, el codigo es ". $nuevoPedido->GetCodigo()));
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

    private function MoverFoto($archivo, $codigoPedido)
    {
        $directorioDestino = './img/';

        if (!is_dir($directorioDestino)) 
        {
            mkdir($directorioDestino, 0755, true); // Crear el directorio si no existe
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION); // Obtener la extensión del archivo
        $nombreArchivo = $codigoPedido . '.' . $extension; // Asignar el código del pedido como nombre del archivo
        $destino = $directorioDestino . $nombreArchivo;
        $imagen = $archivo['tmp_name'];

        $resultadoMoverFoto = move_uploaded_file($imagen, $destino);

        if (!$resultadoMoverFoto)
        {
            throw new Exception("Ha surgido un error al mover la foto");
        }

        return $destino; // Devuelvo la ruta completa donde se guardo el archivo
    }

    public function VincularFoto($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $rutaFoto = $this->MoverFoto($_FILES['foto'], $parametros['codigoPedido']);

            $pedido->SetFoto($rutaFoto);
            $resultado = $pedido->VincularFoto();

            if ($resultado)
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Foto vinculada con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al vincular la foto");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

    public function TomarPedido($request, $response, $args)
    {
        try
        {
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;
            $sector = $data->sector;
            $parametros = $request->getParsedBody();

            $empleado = Usuario::ObtenerUno($username);
            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $producto = Producto::ObtenerUno($parametros['codigoProducto']);

            if ($producto->ProductoNoCorrespondeAUsuario($sector))
            {
                throw new Exception("El producto no corresponde a tu sector");
            }

            $pedidoProductoDisponible = PedidoProducto::ObtenerPedidoProductoDisponible($pedido->GetId(), $producto->GetId());

            $resultado = $pedidoProductoDisponible->TomarPedido($empleado->GetId(), $parametros['tiempoEstimado']);

            if ($resultado)
            {
                //Si todos los pedidos fueron tomados y no hay mas pedidos en pendiente
                if (PedidoProducto::TodosLosPedidosEstanTomados($parametros['codigoPedido']))
                {
                    $pedido->DefinirTiempoTotalEstimado();
                }

                return $this->CrearRespuesta($response, array("mensaje" => "Pedido tomado con exito"));
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
            $tokenRecibido = JWTHandler::ObtenerTokenEnviado($request);
            $data = JWTHandler::ObtenerData($tokenRecibido);
            $username = $data->username;
            $sector = $data->sector;
            $parametros = $request->getParsedBody();

            $empleado = Usuario::ObtenerUno($username);
            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $producto = Producto::ObtenerUno($parametros['codigoProducto']);

            if (PedidoProducto::PedidoTomadoNoCorrespondeAlUsuario($empleado->GetId(), $producto->GetId()))
            {
                throw new Exception("El producto no corresponde a tu sector");
            }

            $pedidoProductoEnPreparacion = PedidoProducto::ObtenerPedidoProductoEnPreparacion($pedido->GetId(), $producto->GetId(), $empleado->GetId());

            $resultado = $pedidoProductoEnPreparacion->TerminarPedido();

            if ($resultado) 
            {
                //Si todos los pedidos estan listos para servir (Pudiendo algunos estar cancelados)
                if (PedidoProducto::TodosLosPedidosEstanListos($parametros['codigoPedido']))
                {
                    $pedido->DefinirFechaFinalizacion();
                }

                return $this->CrearRespuesta($response, array("mensaje" => "Pedido terminado con exito"));
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

    public function CobrarMesa($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $resultado = $pedido->ActualizarImporteTotal();

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Importe total actualizado con exito"));
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

    public function CancelarTodo($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $resultado = $pedido->CancelarPedidoEntero();

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Pedido cancelado totalmente con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al cancelar el pedido");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function CancelarUno($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $producto = Producto::ObtenerUno($parametros['codigoProducto']);

            $resultado = PedidoProducto::Cancelar($pedido->GetId(), $producto->GetId());

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Pedido cancelado con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al cancelar el pedido");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }
}