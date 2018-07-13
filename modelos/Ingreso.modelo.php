<?php

// Incluimos inicialmente la conexion a la base de datos
require '../config/Conexion.php';

class Ingreso
{

// Implementamos nuestro Constructor
    public function __construct()
    {}

    //implementamos un metodo para insertar registros
    public function insertar($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta)
    {
        $sql = "INSERT INTO ingreso(idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado)" .
            " VALUES('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_compra','ACEPTADO');";
//        return ejecutarConsulta($sql);
        $idIngreso = ejecutarConsulta_retornarID($sql);
        $num_elementos = 0;
        $sw = true;
        while ($num_elementos < count($idarticulo)) {
            $sql_detalle = "INSERT INTO detalle_ingreso(idIngreso, idarticulo,cantidad,precio_compra,precio_venta)"
                . " VALUES('$idIngreso', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]');";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos++;
        }
        return $sw;
    }

    // Implementamos un metodo para desactivar categorias
    public function anular($idingreso)
    {
        $sql = "UPDATE ingreso SET estado='ANULADO' WHERE idingreso='$idingreso';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idingreso)
    {
        $sql = "SELECT I.idingreso, DATE(I.fecha_hora) AS fecha, I.idproveedor, I.tipo_comprobante, I.serie_comprobante, I.num_comprobante, I.total_compra, I.estado, I.impuesto,"
            . " U.idusuario, U.nombre AS usuario, P.nombre as proveedor"
            . " FROM ingreso I INNER JOIN persona P ON I.idproveedor=P.idpersona INNER JOIN usuario U ON I.idusuario=U.idusuario"
            . " WHERE idingreso='$idingreso';";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalles($idingreso)
    {
        $sql = "SELECT DI.idingreso,DI.idarticulo,A.nombre,DI.cantidad,DI.precio_compra, DI.precio_venta" .
            " FROM detalle_ingreso DI INNER JOIN articulo A ON DI.idarticulo=A.idarticulo WHERE DI.idingreso='$idingreso';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para listar los registro
    public function listar()
    {
        $sql = "SELECT I.idingreso, DATE(I.fecha_hora) AS fecha, I.idproveedor, I.tipo_comprobante, I.serie_comprobante, I.num_comprobante, I.total_compra, I.estado,"
            . " U.idusuario, U.nombre AS usuario, P.nombre as proveedor"
            . " FROM ingreso I INNER JOIN persona P ON I.idproveedor=P.idpersona INNER JOIN usuario U ON I.idusuario=U.idusuario"
            . " ORDER BY I.idingreso desc;";
        return ejecutarConsulta($sql);
    }

}

?>






