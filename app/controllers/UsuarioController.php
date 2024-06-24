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

    //////////////////////////////////////////// GET /////////////////////////////////////////////////

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

    public function ObtenerLogsDeUsuario($request, $response, $args)
    {
        try
        {
            $parametros = $request->getQueryParams();

            $loginsUsuario = Usuario::ObtenerLoginsDeUsuario($parametros['username']);

            if (!$loginsUsuario)
            {
                throw new Exception("El usuario nunca ha ingresado al sistema");
            }

            return $this->CrearRespuesta($response, array("logsDelUsuario" => $loginsUsuario));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    public function Login($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $resultado = Usuario::Login($parametros['username'], $parametros['pass']);
    
            if ($resultado) 
            {
                $usuarioLogueado = Usuario::ObtenerUno($parametros['username']);
                $sectorDelUsuario = $usuarioLogueado->GetSector();

                return $this->CrearRespuesta($response, ["sector" => $sectorDelUsuario]);            
            } 
            else 
            {
                throw new Exception("Las credenciales son incorrectas");
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, ["mensaje" => $e->getMessage()], 500);
        }
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
                return $this->CrearRespuesta($response, ["mensaje" => "Usuario creado con exito"]);
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

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

    public function ModificarUsername($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
    
            $resultado = Usuario::ModificarUsername($parametros['usernameOriginal'], $parametros['usernameNuevo']);

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, ["mensaje" => "Username modificado con exito"]);
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

    public function ModificarPass($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
    
            $resultado = Usuario::ModificarPass($parametros['username'], $parametros['pass']);

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, ["mensaje" => "Pass modificada con exito"]);
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

    public function ModificarSector($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
    
            $resultado = Usuario::ModificarSector($parametros['username'], $parametros['sector']);

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, ["mensaje" => "Sector modificado con exito"]);
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

    //////////////////////////////////////////// DELETE /////////////////////////////////////////////////

    public function BorrarUsuario($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();

            $resultado = Usuario::Borrar($parametros['username']);

            if ($resultado)
            {
                return $this->CrearRespuesta($response, ["mensaje" => "Usuario borrado con exito"]);
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