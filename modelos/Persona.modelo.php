<?php

// Incluimos inicialmente la conexion a la base de datos
require '../config/Conexion.php';

class Persona {

// Implementamos nuestro Constructor
    public function __construct() {
        
    }

    //implementamos un metodo para insertar registros
    public function insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email) {
        $sql = "INSERT INTO persona(tipo_persona, nombre, tipo_documento, num_documento, direccion, telefono, email)"
                . " VALUES('$tipo_persona', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email');";
        return ejecutarConsulta($sql);
    }

    public function editar($idPersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email) {
        $sql = "UPDATE persona SET tipo_persona='$tipo_persona',nombre='$nombre',tipo_documento='$tipo_documento',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email'"
                . " WHERE idpersona='$idPersona';";
        return ejecutarConsulta($sql);
    }

    // Implementamos un metodo para eliminar personas
    public function eliminar($idPersona) {
        $sql = "DELETE FROM persona WHERE idpersona='$idPersona';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idPersona) {
        $sql = "SELECT * FROM persona WHERE idpersona='$idPersona';";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Implementar un metodo para listar los registro
    public function listarProveedor() {
        $sql = "SELECT * FROM persona WHERE tipo_persona='PROVEEDOR';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para listar los registro
    public function listarCliente() {
        $sql = "SELECT * FROM persona WHERE tipo_persona='CLIENTE';";
        return ejecutarConsulta($sql);
    }

}
?>


