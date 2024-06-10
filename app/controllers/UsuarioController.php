<?php 

require_once './models/usuario.php';

class UsuarioController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public function RegistrarUsuario($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
    
            $nuevoUsuario = new Usuario($parametros['username'], $parametros['pass'], $parametros['sector']);
            $resultado = $nuevoUsuario->Registrar();
    
            if ($resultado) 
            {
                return $this->CrearRespuesta($response, ["mensaje" => "Usuario creado con éxito"]);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear el usuario");
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, ["mensaje" => $e->getMessage()], 500);
        }
    }

    public function ObtenerTodosLosUsuarios($request, $response, $args)
    {
        try
        {
            $lista = Usuario::ObtenerTodos();
            return $this->CrearRespuesta($response, ["listaUsuarios" => $lista]);
        }
        catch (Exception $e)
        {
            return $this->CrearRespuesta($response, ["mensaje" => $e->getMessage()], 500);
        }
    }

    public function ModificarUsuario($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
    
            $modificacionNuevoUsuario = new Usuario($parametros['username'], $parametros['pass'], $parametros['sector']);
            $resultado = $modificacionNuevoUsuario->Modificar($parametros['usernameOriginal']);

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, ["mensaje" => "Usuario modificado con éxito"]);
            } 
            else 
            {
                throw new Exception("Error en la modificación");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, ["mensaje" => $e->getMessage()], 500);
        }
    }

    public function BorrarUsuario($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $resultado = Usuario::Borrar($parametros['username']);

            if ($resultado)
            {
                return $this->CrearRespuesta($response, ["mensaje" => "Usuario borrado con éxito"]);
            }
            else
            {
                throw new Exception("Error al borrar el usuario");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, ["mensaje" => $e->getMessage()], 500);
        }
    }
}