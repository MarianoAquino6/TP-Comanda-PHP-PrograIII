<?php

require_once './models/reseña.php';
require_once './models/pedido.php';

class ReseñaController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public function RegistrarReseña($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
    
            $pedido = Pedido::ObtenerUno($parametros['codigoPedido']);
            $idNecesarios = Pedido::ObtenerDatosNecesarioEncuesta($pedido->GetId());

            $idMesa = $idNecesarios['id_mesa'];
            $idMozo = $idNecesarios['id_mozo'];
            $idCocineros = $idNecesarios['cocineros'];

            $nuevaReseña = new Reseña($idMesa, $parametros['puntuacionMesa'], $idMozo, $parametros['puntuacionMozo'], 
            $idCocineros, $parametros['puntuacionCocinero'], $parametros['puntuacionRestaurante'], $parametros['experiencia'], $parametros['codigoPedido']);
            
            $resultado = $nuevaReseña->Registrar();
    
            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Resenia guardada con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al registrar la reseña");
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerMejoresComentariosPromedio($request, $response, $args)
    {
        try
        {
            $mejoresComentarios = Reseña::ObtenerMejoresComentarios();
            return $this->CrearRespuesta($response, array("listaMejoresComentarios" => $mejoresComentarios));
        }
        catch (Exception $e)
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }
}