<?php 

class Usuario
{
    private $_id;
    private $_username;
    private $_pass;
    private $_sector;

    public function __construct($username, $pass, $sector, $id=null)
    {
        $this->_username = $username;
        $this->_pass = $pass;
        $this->_sector = $sector;
        $this->_id = $id;
    }

    public function GetId()
    {
        return $this->_id;
    }

    public function GetSector()
    {
        return $this->_sector;
    }

    public function GetPass()
    {
        return $this->_pass;
    }

    public static function Login($username, $pass)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT pass FROM usuarios 
                WHERE username = :username AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $queryPreparada->execute();

        $fila = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($fila != false && password_verify($pass, $fila['pass']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    usuarios (is_deleted, username, pass, sector, fecha_creacion, fecha_modificacion)
                    VALUES (0, :username, :pass, :sector, :fecha_creacion, :fecha_modificacion)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $passHasheada = password_hash($this->_pass, PASSWORD_DEFAULT);
        $fechaCreacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':username', $this->_username, PDO::PARAM_STR);
        $queryPreparada->bindParam(':pass', $passHasheada, PDO::PARAM_STR);
        $queryPreparada->bindParam(':sector', $this->_sector, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_creacion', $fechaCreacion, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaCreacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function UsuarioExiste($username)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT username FROM usuarios WHERE username = :username AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $queryPreparada->execute();

        $resultado = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($resultado != false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function ObtenerTodos()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT username, sector, fecha_creacion, fecha_modificacion 
                    FROM usuarios
                    WHERE is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->execute();

        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerUno($username)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT username, pass, sector, id FROM usuarios WHERE username = :username";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);

        $queryPreparada->execute();

        $fila = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if ($fila) 
        {
            return new Usuario($fila['username'], $fila['pass'], $fila['sector'], $fila['id']);
        } 
        else 
        {
            return null;
        }
    }

    public static function ObtenerSector($username)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT sector FROM usuarios WHERE username = :username";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);

        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_ASSOC)['sector'];
    }

    public static function ModificarUsername($usernameOriginal, $usernameNuevo)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE usuarios SET username = :username_nuevo, fecha_modificacion = :fecha_modificacion 
                    WHERE username = :username_original AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username_original', $usernameOriginal, PDO::PARAM_STR);
        $queryPreparada->bindParam(':username_nuevo', $usernameNuevo, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ModificarPass($username, $nuevaPass)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE usuarios SET pass = :pass, fecha_modificacion = :fecha_modificacion 
                    WHERE username = :username AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $passHasheada = password_hash($nuevaPass, PASSWORD_DEFAULT);
        $fechaModificacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $queryPreparada->bindParam(':pass', $passHasheada, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ModificarSector($username, $nuevoSector)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE usuarios SET sector = :sector, fecha_modificacion = :fecha_modificacion 
                    WHERE username = :username AND is_deleted = 0";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $fechaModificacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $queryPreparada->bindParam(':sector', $nuevoSector, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function Borrar($username)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE usuarios 
                    SET is_deleted = 1, fecha_modificacion = :fecha_modificacion 
                    WHERE username = :username";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function ObtenerLoginsDeUsuario($username)
    {
        $acceso = AccesoDatos::ObtenerInstancia();
    
        $query = "SELECT username, sector, fecha_ingreso FROM logins WHERE username = :username";
        
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $queryPreparada->execute();

        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }
}