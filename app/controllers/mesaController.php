<?php

require_once './models/mesa.php';
require_once './models/pedido.php';

class MesaController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public function RegistrarMesa($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
    
            $nuevaMesa = new Mesa($parametros['codigo'], "Cerrada");
            $resultado = $nuevaMesa->Registrar();
    
            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Mesa creada con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear la mesa");
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ActualizarEstadoMesa($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $mesaAActualizar = Mesa::ObtenerUno($parametros['codigo']);
            $resultado = $mesaAActualizar->ActualizarEstado($parametros['estado']);

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Estado actualizado con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar el estado de la mesa");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function CerrarMesa($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $mesaAActualizar = Mesa::ObtenerUno($parametros['codigo']);
            $resultado = $mesaAActualizar->ActualizarEstado("Cerrada");

            // Pedido::Borrar($mesaAActualizar->GetId());

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Mesa cerrada con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al cerrar la mesa");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
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
                return $this->CrearRespuesta($response, array("mensaje" => "Mesa borrada con exito"));
            }
            else
            {
                throw new Exception("Ha surgido un error al borrar la mesa");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerTodasLasMesasConEstados($request, $response, $args)
    {
        try 
        {
            $lista = Mesa::ObtenerListadoMesasConEstados();
            return $this->CrearRespuesta($response, array("listaMesasConEstados" => $lista));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMesaMasUsada($request, $response, $args)
    {
        try 
        {
            $mesaMasUsada = Mesa::ObtenerMesaMasUsada();
            return $this->CrearRespuesta($response, array("mesaMasUsada" => $mesaMasUsada));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }
}