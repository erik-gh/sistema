<?php

// Incluimos inicialmente la conexion a la base de datos
require '../config/Conexion.php';

class Articulo {

// Implementamos nuestro Constructor
    public function __construct() {
        
    }

    //implementamos un metodo para insertar registros
    public function insertar($idCategoria, $codigo, $nombre, $stock, $descripcion, $imagen) {
        $sql = "INSERT INTO articulo(idcategoria, codigo, nombre, stock, descripcion, imagen, condicion)
                 VALUES('$idCategoria', '$codigo', '$nombre', '$stock', '$descripcion', '$imagen', '1');";
        return ejecutarConsulta($sql);
    }

    public function editar($idArticulo, $idCategoria, $codigo, $nombre, $stock, $descripcion, $imagen) {
        $sql = "UPDATE articulo SET idcategoria='$idCategoria',codigo='$codigo',nombre='$nombre',stock='$stock',descripcion='$descripcion',imagen='$imagen'
                 WHERE idarticulo='$idArticulo';";
        return ejecutarConsulta($sql);
    }

    // Implementamos un metodo para desactivar categorias
    public function desactivar($idArticulo) {
        $sql = "UPDATE articulo SET condicion='0' WHERE idarticulo='$idArticulo';";
        return ejecutarConsulta($sql);
    }

    // Implementamos un metodo para activar categorias
    public function activar($idArticulo) {
        $sql = "UPDATE articulo SET condicion='1' WHERE idarticulo='$idArticulo';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idArticulo) {
        $sql = "SELECT * FROM articulo WHERE idarticulo='$idArticulo';";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Implementar un metodo para listar los registro
    public function listar() {
        $sql = "SELECT A.idarticulo,A.idcategoria,C.nombre AS categoria,A.codigo,A.nombre,A.stock,A.descripcion,A.imagen,A.condicion
                 FROM articulo A INNER JOIN categoria C ON A.idcategoria=C.idcategoria;";
        return ejecutarConsulta($sql);
    }
    public function listarActivos() {
        $sql = "SELECT A.idarticulo,A.idcategoria,C.nombre AS categoria,A.codigo,A.nombre,A.stock,A.descripcion,A.imagen,A.condicion
                 FROM articulo A INNER JOIN categoria C ON A.idcategoria=C.idcategoria WHERE A.condicion = 1;";
        return ejecutarConsulta($sql);
    }
    public function listarActivosVenta(){
        $sql="SELECT (SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=A.idarticulo ORDER BY iddetalle_ingreso DESC LIMIT 0,1) AS precio_venta,
             A.idarticulo, A.idcategoria, C.nombre AS categoria, A.codigo, A.nombre, A.stock, A.descripcion, A.imagen, A.condicion
             FROM articulo A INNER JOIN categoria C ON A.idcategoria=C.idcategoria
             WHERE A.condicion = '1';";
             return ejecutarConsulta($sql);
    }
    public function select() {
        $sql = "SELECT * FROM articulo WHERE condicion=1;";
        return ejecutarConsulta($sql);
    }
}
?>





