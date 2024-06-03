<?php 

class Usuario
{
    private $_username;
    private $_pass;
    private $_sector;

    public function __construct($username, $pass, $sector)
    {
        $this->_username = $username;
        $this->_pass = $pass;
        $this->_sector = $sector;
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

    public static function ObtenerTodos()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT * FROM usuarios";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->execute();

        return $queryPreparada->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ObtenerUno($username)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT * FROM usuarios WHERE username = :username";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);

        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_ASSOC);
    }

    public function Modificar($usernameOriginal)
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "UPDATE usuarios SET username = :username, pass = :pass, sector = :sector, fecha_modificacion = :fecha_modificacion WHERE username = :username_original";
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

        $query = "UPDATE usuarios SET is_deleted = true, fecha_modificacion = :fecha_modificacion WHERE username = :username";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $username, PDO::PARAM_STR);
        $fechaModificacion = date('Y-m-d H:i:s');
        $queryPreparada->bindParam(':fecha_modificacion', $fechaModificacion, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }
}