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

    public function Registrar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO 
                    usuarios (is_deleted, username, pass, sector, fecha_creacion, fecha_modificacion)
                    VALUES (false, :username, :pass, :sector, :fecha_creacion, :fecha_modificacion)";
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

        $query = "SELECT username FROM usuarios WHERE username = :username AND NOT is_deleted";
        $queryPreparada = $acceso->PrepararConsulta($query);
        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $queryPreparada->execute();

        $resultado = $queryPreparada->fetch(PDO::FETCH_ASSOC);

        if (count($resultado) > 0)
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

        $query = "SELECT username, pass, sector, fecha_creacion, fecha_modificacion 
                    FROM usuarios
                    WHERE NOT is_deleted";
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

        return $queryPreparada->fetch(PDO::FETCH_CLASS, 'Usuario');
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

    public function Modificar($usernameOriginal)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE usuarios 
                    SET username = :username, pass = :pass, sector = :sector, fecha_modificacion = :fecha_modificacion 
                    WHERE username = :username_original AND NOT is_deleted";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $passHasheada = password_hash($this->_pass, PASSWORD_DEFAULT);
        $fechaModificacion = date('Y-m-d H:i:s');

        $queryPreparada->bindParam(':username_original', $usernameOriginal, PDO::PARAM_STR);
        $queryPreparada->bindParam(':username', $this->_username, PDO::PARAM_STR);
        $queryPreparada->bindParam(':pass', $passHasheada, PDO::PARAM_STR);
        $queryPreparada->bindParam(':sector', $this->_sector, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }

    public static function Borrar($username)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE usuarios 
                    SET is_deleted = true, fecha_modificacion = :fecha_modificacion 
                    WHERE username = :username";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }
}