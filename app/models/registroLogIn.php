<?php 

class RegistroLogIn
{
    private $_username;
    private $_sector;
    private $_fechaIngreso;

    public function __construct($username)
    {
        $this->_username = $username;
        $this->_sector = $this->ObtenerSectorUsuario();
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->_fechaIngreso = date('Y-m-d H:i:s');
    }

    private function ObtenerSectorUsuario()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "SELECT sector FROM usuarios WHERE username = :username AND NOT is_deleted";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $this->_username, PDO::PARAM_STR);
        $queryPreparada->execute();

        return $queryPreparada->fetch(PDO::FETCH_ASSOC)['sector'];
    }

    public function Guardar()
    {
        $acceso = AccesoDatos::ObtenerInstancia();

        $query = "INSERT INTO logins (username, sector, fecha_ingreso)
                    VALUES (:username, :sector, :fecha_ingreso)";
        $queryPreparada = $acceso->PrepararConsulta($query);

        $queryPreparada->bindParam(':username', $this->_username, PDO::PARAM_STR);
        $queryPreparada->bindParam(':sector', $this->_sector, PDO::PARAM_STR);
        $queryPreparada->bindParam(':fecha_ingreso', $this->_fechaIngreso, PDO::PARAM_STR);

        return $queryPreparada->execute();
    }
}