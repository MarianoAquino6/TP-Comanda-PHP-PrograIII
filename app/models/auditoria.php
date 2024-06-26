<?php 

class Auditoria 
{
    private $_username;
    private $_url;
    private $_parametros;

    public function __construct($username, $url, $parametros)
    {
        $this->_username = $username;
        $this->_url = $url;
        $this->_parametros = $parametros;
    }

    ///////////////////////////////////////////// CREATE ///////////////////////////////////////////////////////////

    public function Guardar()
    {
        $query = "INSERT INTO 
                auditoria (username, url, parametros, fecha)
                VALUES (:username, :url, :parametros, :fecha)";
    
        $parametros = array(
            ':username' => $this->_username,
            ':url' => $this->_url,
            ':parametros' => $this->_parametros,
            ':fecha' => date('Y-m-d H:i:s')
        );
    
        return AccesoDatos::EjecutarConsultaIUD($query, $parametros);
    }
}