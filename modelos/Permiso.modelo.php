<?php

// Incluimos inicialmente la conexion a la base de datos
require '../config/Conexion.php';

class Permiso {

// Implementamos nuestro Constructor
    public function __construct() {
        
    }

    // Implementar un metodo para listar los registro
    public function listar() {
        $sql = "SELECT * FROM permiso;";
        return ejecutarConsulta($sql);
    }

}

?>
