<?php 

class RegistroLogIn
{
    private $_username;
    private $_sector;
    private $_fechaIngreso;

    public function __construct($username, $sector)
    {
        $this->_username = $username;
        $this->_sector = $sector;
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->_fechaIngreso = date('Y-m-d H:i:s');
    }

    ///////////////////////////////////////////// CREATE ///////////////////////////////////////////////////////////

    public function Guardar()
    {
        $query = "INSERT INTO logins (username, sector, fecha_ingreso)
                VALUES (:username, :sector, :fecha_ingreso)";
        $parametros = [
            ':username' => $this->_username,
            ':sector' => $this->_sector,
            ':fecha_ingreso' => $this->_fechaIngreso
        ];

        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }
}