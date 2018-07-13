<?php

// Incluimos inicialmente la conexion a la base de datos
require '../config/Conexion.php';

class Consultas
{
// Implementamos nuestro Constructor
    public function __construct()
    {
    }
    // Implementar un metodo para listar los registro

    /**
     * @param $fecha_inicio
     * @param $fecha_fin
     * @return bool|mysqli_result
     */
    public function comprasFecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE(I.fecha_hora) AS fecha, U.nombre AS usuario, P.nombre AS proveedor, I.tipo_comprobante,
                 I.serie_comprobante, I.num_comprobante, I.total_compra, I.impuesto, I.estado 
                FROM ingreso I
                INNER JOIN persona P ON I.idproveedor = P.idpersona
                INNER JOIN usuario U ON I.idusuario = U.idusuario
                WHERE DATE(I.fecha_hora)>='$fecha_inicio' AND DATE(I.fecha_hora)<='$fecha_fin';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para listar los registro
    public function ventasFechaCliente($fecha_inicio, $fecha_fin, $idcliente)
    {
        $sql = "SELECT DATE(V.fecha_hora) AS fecha, U.nombre AS usuario, P.nombre AS cliente, V.tipo_comprobante,
                 V.serie_comprobante, V.num_comprobante, V.total_venta, V.impuesto, V.estado 
                FROM venta V
                INNER JOIN persona P ON V.idcliente = P.idpersona
                INNER JOIN usuario U ON V.idusuario = U.idusuario
                WHERE DATE(V.fecha_hora)>='$fecha_inicio' AND DATE(V.fecha_hora)<='$fecha_fin' AND V.idcliente='$idcliente';";
        return ejecutarConsulta($sql);
    }

    // Implementar un metodo para listar los registro
    public function totalCompraHoy()
    {
        $sql = "SELECT IFNULL(SUM(total_compra),0) AS total_compra FROM ingreso WHERE DATE(fecha_hora) = CURDATE();";
        return ejecutarConsulta($sql);
    }

    public function totalVentaHoy()
    {
        $sql = "SELECT IFNULL(SUM(total_venta),0) AS total_venta FROM venta WHERE DATE(fecha_hora) = CURDATE();";
        return ejecutarConsulta($sql);
    }

    public function comprasUltimos10Dias(){
        $sql="SELECT CONCAT(DAY(fecha_hora), ' - ', MONTH(fecha_hora)) AS fecha, SUM(total_compra) AS total 
                FROM  ingreso GROUP BY fecha_hora ORDER BY fecha_hora DESC LIMIT 0,10;";
        return ejecutarConsulta($sql);
    }

    public function ventasUltimos12Meses(){
        $sql="SELECT DATE_FORMAT(fecha_hora,'%M') AS fecha, SUM(total_venta) AS total 
                FROM  venta GROUP BY MONTH(fecha_hora) ORDER BY fecha_hora DESC LIMIT 0,12;";
        return ejecutarConsulta($sql);
    }

}

?>