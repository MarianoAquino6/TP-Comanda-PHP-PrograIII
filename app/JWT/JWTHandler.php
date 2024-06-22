<?php

use Firebase\JWT\JWT;

class JWTHandler
{
    private static $tipoEncriptacion = ['HS256'];

    public static function ObtenerTokenEnviado($request)
    {
        $header = $request->getHeaderLine('Authorization');

        if (empty($header)) 
        {
            throw new Exception("No ha utilizado un token");
        }

        $token = trim(str_replace("Bearer", "", $header));

        if (empty($token)) 
        {
            throw new Exception("Token no válido o vacío");
        }

        return $token;
    }

    public static function CrearToken($datos)
    {
        $ahora = time();
        $payload = array(
            'iat' => $ahora,
            'exp' => $ahora + (60000),
            'aud' => self::Aud(),
            'data' => $datos,
            'app' => "LA COMANDA"
        );
        return JWT::encode($payload, $_ENV['CLAVE_SECRETA']);
    }

    public static function VerificarToken($token)
    {
        //Decodifico el token
        try 
        {
            $decodificado = JWT::decode(
                $token,
                $_ENV['CLAVE_SECRETA'],
                self::$tipoEncriptacion
            );
        } 
        catch (Exception $e) 
        {
            throw $e;
        }

        if ($decodificado->aud !== self::Aud()) 
        {
            throw new Exception("No es el usuario valido");
        }
    }


    public static function ObtenerPayLoad($token)
    {
        if (empty($token)) 
        {
            throw new Exception("El token esta vacio.");
        }
        
        return JWT::decode(
            $token,
            $_ENV['CLAVE_SECRETA'],
            self::$tipoEncriptacion
        );
    }

    public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            $_ENV['CLAVE_SECRETA'],
            self::$tipoEncriptacion
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        //Me guardo el IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
        {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } 
        //Si no puedo guardarme su IP me guardo el IP Foward
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        //Sino el remote adress
        else 
        {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        //Me guardo la info sobre el navegador del usuario
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}
