<?php

require_once '../modelos/Consultas.modelo.php';

$consulta = new Consultas();
// $rspt = null;
switch ($_GET["op"]) {
    case 'compras_fecha':
        $fecha_inicio =$_REQUEST["fecha_inicio"];
        $fecha_fin =$_REQUEST["fecha_fin"];
        $rspt = $consulta->comprasFecha($fecha_inicio, $fecha_fin);
        // Declaramos un array
        $data = Array();
//        $reg = null
        while ($reg = $rspt->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->proveedor,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . ' ' . $reg->num_comprobante,
                "5" => $reg->total_compra,
                "6" => $reg->impuesto,
                "7" => ($reg->estado == 'ACEPTADO')? '<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>');     
        }
        $results = array(
            "sEcho" => 1, //Informacion para el dataTables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'compras_fecha_cliente':
        $fecha_inicio =$_REQUEST["fecha_inicio"];
        $fecha_fin =$_REQUEST["fecha_fin"];
        $id_cliente =$_REQUEST["id_cliente"];
        $rspt = $consulta->ventasFechaCliente($fecha_inicio, $fecha_fin, $id_cliente);
        // Declaramos un array
        $data = Array();
//        $reg = null
        while ($reg = $rspt->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => $reg->cliente,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . ' ' . $reg->num_comprobante,
                "5" => $reg->total_venta,
                "6" => $reg->impuesto,
                "7" => ($reg->estado == 'ACEPTADO')? '<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>');
        }
        $results = array(
            "sEcho" => 1, //Informacion para el dataTables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;
}
?>