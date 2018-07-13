<?php

require_once '../modelos/Permiso.modelo.php';

$permiso = new Permiso();
$rspt = null;
switch ($_GET["op"]) {
    case 'listar':
        $rspt = $permiso->listar();
        // Declaramos un array
        $data = Array();
        while ($reg = $rspt->fetch_object()) {
            $data[] = array(
                "0" => $reg->idpermiso,
                "1" => $reg->nombre,
            );
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

