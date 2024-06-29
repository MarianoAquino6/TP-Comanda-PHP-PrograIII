<?php

require_once './models/mesa.php';
require_once './models/pedido.php';
require_once './models/reseña.php';

class MesaController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    //////////////////////////////////////////// GET /////////////////////////////////////////////////

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

            if ($mesaMasUsada != false)
            {
                return $this->CrearRespuesta($response, array("mesaMasUsada" => $mesaMasUsada));
            }
            
            return $this->CrearRespuesta($response, array("eror" => "No se uso ninguna mesa"));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMesaMenosUsada($request, $response, $args)
    {
        try 
        {
            $mesaMasUsada = Mesa::ObtenerMesaMenosUsada();
            return $this->CrearRespuesta($response, array("mesaMenosUsada" => $mesaMasUsada));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMesaMayorFacturacion($request, $response, $args)
    {
        try 
        {
            $mesaMasUsada = Mesa::ObtenerMesaMayorFacturacion();

            if ($mesaMasUsada == false)
            {
                return $this->CrearRespuesta($response, array("mesaMayorFacturacion" => "Aun no hay datos"));
            }

            return $this->CrearRespuesta($response, array("mesaMayorFacturacion" => $mesaMasUsada));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMesaMenorFacturacion($request, $response, $args)
    {
        try 
        {
            $mesaMasUsada = Mesa::ObtenerMesaMenorFacturacion();
            return $this->CrearRespuesta($response, array("mesaMenorFacturacion" => $mesaMasUsada));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMesaMayorImporte($request, $response, $args)
    {
        try 
        {
            $mesaMasUsada = Mesa::ObtenerMesaMayorImporte();

            if ($mesaMasUsada == false)
            {
                return $this->CrearRespuesta($response, array("mesaMayorImporte" => "Aun no hay datos"));
            }

            return $this->CrearRespuesta($response, array("mesaMayorImporte" => $mesaMasUsada));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMesaMenorImporte($request, $response, $args)
    {
        try 
        {
            $mesaMasUsada = Mesa::ObtenerMesaMenorImporte();
            return $this->CrearRespuesta($response, array("mesaMenorImporte" => $mesaMasUsada));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerFacturacionEnPeriodo($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getQueryParams();
            $codigoMesa = $parametros['codigoMesa'];
            $fechaDesde = $parametros['fechaDesde'];
            $fechaHasta = $parametros['fechaHasta'];

            $facturacion = Mesa::ObtenerFacturacionEnPeriodo($codigoMesa, $fechaDesde, $fechaHasta);

            if ($facturacion)
            {
                return $this->CrearRespuesta($response, array("facturacion de " . $codigoMesa  . " en el periodo indicado" => $facturacion));
            }
            else
            {
                return $this->CrearRespuesta($response, array("facturacion de " . $codigoMesa  . "en el periodo indicado" => "No existen pedidos correspondientes a la mesa para las fechas ingresadas"));
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMejoresComentarios($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getQueryParams();
            $codigoMesa = $parametros['codigo'];

            $mejoresComentarios = Reseña::ObtenerMejoresComentariosDeMesa($codigoMesa);

            if ($mejoresComentarios)
            {
                return $this->CrearRespuesta($response, array("mejoresComentarios" => $mejoresComentarios));
            }
            else
            {
                return $this->CrearRespuesta($response, array("mejoresComentarios" => "No existen comentarios para la mesa ingresada"));
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerPeoresComentarios($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getQueryParams();
            $codigoMesa = $parametros['codigo'];

            $peoresComentarios = Reseña::ObtenerPeoresComentariosDeMesa($codigoMesa);

            if ($peoresComentarios)
            {
                return $this->CrearRespuesta($response, array("peoresComentarios" => $peoresComentarios));
            }
            else
            {
                return $this->CrearRespuesta($response, array("peoresComentarios" => "No existen comentarios para la mesa ingresada"));
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMesasOrdenadasPorImporteMenorMayor($request, $response, $args)
    {
        try 
        {
            $mesasOrdenadas = Mesa::ObtenerMesasOrdenadasPorImporteMaximoMenorMayor();

            if ($mesasOrdenadas == false)
            {
                return $this->CrearRespuesta($response, array("mesasOrdenadasSegunImporte" => "Aun no hay importes"));
            }

            return $this->CrearRespuesta($response, array("mesasOrdenadasSegunImporte" => $mesasOrdenadas));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

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

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

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

            Pedido::Desestimar($mesaAActualizar->GetId());

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

    //////////////////////////////////////////// DELETE /////////////////////////////////////////////////

    public function BorrarMesa($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $mesaABorrar = Mesa::ObtenerUno($parametros['codigo']);
            $resultado = $mesaABorrar->Borrar();

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
}