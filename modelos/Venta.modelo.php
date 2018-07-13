<?php

// Incluimos inicialmente la conexion a la base de datos
require '../config/Conexion.php';

class Venta {

// Implementamos nuestro Constructor
    public function __construct() {
        
    }

    //implementamos un metodo para insertar registros
    public function insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento) {
        $sql = "INSERT INTO venta(idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta)
                 VALUES('$idcliente','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_venta');";

        $idventa = ejecutarConsulta_retornarID($sql);
        $num_elementos = 0;
        $sw = true;
        while ($num_elementos < count($idarticulo)) {
            $sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento)
                             VALUES('$idventa', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]');";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos++;
        }
        return $sw;
    }

    // Implementamos un metodo para anular Venta
    public function anular($idventa) {
        $sql = "UPDATE venta SET estado='ANULADO' WHERE idventa='$idventa';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idventa) {
        $sql = "SELECT V.idventa,V.idcliente, V.tipo_comprobante,V.serie_comprobante,V.num_comprobante,DATE(V.fecha_hora) AS fecha,V.impuesto,V.total_venta,V.estado,
                U.idusuario, U.nombre AS usuario, P.nombre as cliente
                FROM venta V INNER JOIN persona P ON V.idcliente=P.idpersona INNER JOIN usuario U ON V.idusuario=U.idusuario
                WHERE idventa='$idventa';";
        return ejecutarConsultaSimpleFila($sql);
    }

// Implementrar un metodo listar los detalles de cada venta 
    public function listarDetalles($idventa) {//Revisar si se va poner el descuento
        $sql = "SELECT DV.idventa,DV.idarticulo,A.nombre,DV.cantidad,DV.precio_venta,DV.descuento,DV.iddetalle_venta
                FROM detalle_venta DV INNER JOIN articulo A ON DV.idarticulo=A.idarticulo 
                WHERE DV.idventa='$idventa';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para listar en la tabla principal las ventas
    public function listar() {
        $sql = "SELECT V.idventa, DATE(V.fecha_hora) AS fecha, V.idcliente, V.tipo_comprobante, V.serie_comprobante,
                 V.num_comprobante, V.total_venta, V.estado, U.idusuario, U.nombre AS usuario, P.nombre as cliente
                 FROM venta V INNER JOIN persona P ON V.idcliente=P.idpersona INNER JOIN usuario U ON V.idusuario=U.idusuario
                 ORDER BY V.idventa DESC;";
        return ejecutarConsulta($sql);
    }

    public function ventaCabecera($idventa) {
        $sql = "SELECT V.idventa, V.idcliente, V.idusuario, U.nombre AS usuario, V.tipo_comprobante,
			V.serie_comprobante, V.num_comprobante, DATE(V.fecha_hora) AS fecha, V.impuesto, V.total_venta,
			P.nombre AS cliente, P.direccion, P.tipo_documento, P.num_documento, P.email, P.telefono
            FROM venta V INNER JOIN persona P ON V.idcliente = P.idpersona INNER JOIN usuario U ON V.idusuario = U.idusuario
            WHERE V.idventa='$idventa';";
        return ejecutarConsulta($sql);
    }

    public function ventaDetalle($idventa) {
        $sql = "SELECT A.nombre AS articulo, A.codigo, DV.cantidad, DV.precio_venta, DV.descuento,
			    FORMAT(((DV.precio_venta*DV.cantidad)-(DV.precio_venta*DV.cantidad*DV.descuento/100)),2) AS subtotal
                FROM detalle_venta DV 
                INNER JOIN articulo A ON DV.idarticulo = A.idarticulo
                WHERE DV.idventa='$idventa';";
        return ejecutarConsulta($sql);
    }

}
?>






