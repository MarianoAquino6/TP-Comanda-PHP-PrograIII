<?php 

require_once './models/usuario.php';

class UsuarioController
{
    public function RegistrarUsuario($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
    
            $nuevoUsuario = new Usuario($parametros['username'], $parametros['pass'], $parametros['sector']);
            $resultado = $nuevoUsuario->Registrar();
    
            if ($resultado) 
            {
                $payload = json_encode(array("mensaje" => "Usuario creado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear el usuario");
            }
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ObtenerTodosLosUsuarios($request, $response, $args)
    {
        $lista = Usuario::ObtenerTodos();
        $payload = json_encode(array("listaUsuarios" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ObtenerUsuario($request, $response, $args)
    {
        $usuario = Usuario::ObtenerUno($args['username']);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
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
                $payload = json_encode(array("mensaje" => "Usuario modificado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } 
            else 
            {
                throw new Exception("Error en la modificacion");
            }
        }
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
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
                $payload = json_encode(array("mensaje" => "Usuario borrado con éxito"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
            else
            {
                throw new Exception("Error al borrar el usuario");
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