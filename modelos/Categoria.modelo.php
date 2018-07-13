<?php

// Incluimos inicialmente la conexion a la base de datos
require '../config/Conexion.php';

class Categoria {

// Implementamos nuestro Constructor
    public function __construct() {
        
    }

    //implementamos un metodo para insertar registros
    public function insertar($nombre, $descripcion) {
        $sql = "INSERT INTO categoria(nombre, descripcion, condicion) VALUES('$nombre', '$descripcion','1');";
        return ejecutarConsulta($sql);
    }

    public function editar($idCategoria, $nombre, $descripcion) {
        $sql = "UPDATE categoria SET nombre='$nombre',descripcion='$descripcion' WHERE idcategoria='$idCategoria';";
        return ejecutarConsulta($sql);
    }

    // Implementamos un metodo para desactivar categorias
    public function desactivar($idCategoria) {
        $sql = "UPDATE categoria SET condicion='0' WHERE idcategoria='$idCategoria';";
        return ejecutarConsulta($sql);
    }

    // Implementamos un metodo para activar categorias
    public function activar($idCategoria) {
        $sql = "UPDATE categoria SET condicion='1' WHERE idcategoria='$idCategoria';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idCategoria) {
        $sql = "SELECT * FROM categoria WHERE idcategoria='$idCategoria';";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Implementar un metodo para listar los registro
    public function listar() {
        $sql = "SELECT * FROM categoria;";
        return ejecutarConsulta($sql);
    }

//Implementamos un metodo para listar los registros y mostrar en el select
    public function select() {
        $sql = "SELECT * FROM categoria where condicion=1;";
        return ejecutarConsulta($sql);
    }

}
?>


