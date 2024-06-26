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

    ///////////////////////////////////////////// CREATE ///////////////////////////////////////////////////////////

    public function Registrar()
    {
        $query = "INSERT INTO 
                usuarios (is_deleted, username, pass, sector, fecha_creacion, fecha_modificacion, activo)
                VALUES (0, :username, :pass, :sector, :fecha_creacion, :fecha_modificacion, 1)";
        $passHasheada = password_hash($this->_pass, PASSWORD_DEFAULT);

        $parametros = [
            ':username' => $this->_username,
            ':pass' => $passHasheada,
            ':sector' => $this->_sector,
            ':fecha_creacion' => date('Y-m-d H:i:s'),
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// UPDATE ///////////////////////////////////////////////////////////

    public static function ModificarUsername($usernameOriginal, $usernameNuevo)
    {
        $query = "UPDATE usuarios SET username = :username_nuevo, fecha_modificacion = :fecha_modificacion 
                WHERE username = :username_original AND is_deleted = 0";
        $parametros = [
            ':username_original' => $usernameOriginal,
            ':username_nuevo' => $usernameNuevo,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public static function ModificarPass($username, $nuevaPass)
    {
        $query = "UPDATE usuarios SET pass = :pass, fecha_modificacion = :fecha_modificacion 
                WHERE username = :username AND is_deleted = 0";
        $passHasheada = password_hash($nuevaPass, PASSWORD_DEFAULT);
        $parametros = [
            ':username' => $username,
            ':pass' => $passHasheada,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public static function ModificarSector($username, $nuevoSector)
    {
        $query = "UPDATE usuarios SET sector = :sector, fecha_modificacion = :fecha_modificacion 
                WHERE username = :username AND is_deleted = 0";
        $parametros = [
            ':username' => $username,
            ':sector' => $nuevoSector,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public static function DarDeBaja($username)
    {
        $query = "UPDATE usuarios SET activo = 0, fecha_modificacion = :fecha_modificacion 
                WHERE username = :username AND is_deleted = 0";
        $parametros = [
            ':username' => $username,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    public static function Reactivar($username)
    {
        $query = "UPDATE usuarios SET activo = 1, fecha_modificacion = :fecha_modificacion 
                WHERE username = :username AND is_deleted = 0";
        $parametros = [
            ':username' => $username,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// DELETE ///////////////////////////////////////////////////////////

    public static function Borrar($username)
    {
        $query = "UPDATE usuarios 
                SET is_deleted = 1, fecha_modificacion = :fecha_modificacion 
                WHERE username = :username";
        $parametros = [
            ':username' => $username,
            ':fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }

    ///////////////////////////////////////////// READ ///////////////////////////////////////////////////////////

    public static function Login($username, $pass)
    {
        $query = "SELECT pass FROM usuarios 
            WHERE username = :username AND is_deleted = 0";
        $parametros = [':username' => $username];

        $fila = AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC);

        if ($fila != false && password_verify($pass, $fila['pass']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function UsuarioExiste($username)
    {
        $query = "SELECT username FROM usuarios WHERE username = :username AND is_deleted = 0";
        $parametros = [':username' => $username];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC) != false;
    }

    public static function ObtenerTodos()
    {
        $query = "SELECT username, sector, fecha_creacion, fecha_modificacion, activo 
                FROM usuarios
                WHERE is_deleted = 0";

        return AccesoDatos::EjecutarConsultaSelect($query, [])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerUno($username)
    {
        $query = "SELECT username, pass, sector, id FROM usuarios WHERE username = :username AND is_deleted = 0";
        $parametros = [':username' => $username];

        $fila = AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC);

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
        $query = "SELECT sector FROM usuarios WHERE username = :username";
        $parametros = [':username' => $username];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC)['sector'];
    }

    public static function ObtenerLoginsDeUsuario($username)
    {
        $query = "SELECT username, sector, fecha_ingreso FROM logins WHERE username = :username";
        $parametros = [':username' => $username];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function EstaInhabilitado($username)
    {
        $query = "SELECT username FROM usuarios WHERE username = :username AND activo = 0";
        $parametros = [':username' => $username];

        return AccesoDatos::EjecutarConsultaSelect($query, $parametros)->fetch(PDO::FETCH_ASSOC) != false;
    }
}