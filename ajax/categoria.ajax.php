<?php

require_once '../modelos/Categoria.modelo.php';

$categoria = new Categoria();
$idCat = isset($_POST["idCategoria"]) ? limpiarCadena($_POST["idCategoria"]) : null;
$nomCat = isset($_POST["nomCategoria"]) ? limpiarCadena($_POST["nomCategoria"]) : null;
$desCat = isset($_POST["desCategoria"]) ? limpiarCadena($_POST["desCategoria"]) : null;
$rspt = null;
switch ($_GET["op"]) {
    case 'guardar_editar':
        if (empty($idCat)) {// (Insert)Guarda Registros 
            $rspt = $categoria->insertar($nomCat, $desCat);
            echo $rspt ? "Categoría registrada" : "Categoría no se pudo registrar";
        } else {// (Update)Actualiza Registro $idCat
            $rspt = $categoria->editar($idCat, $nomCat, $desCat);
            echo $rspt ? "Categoría actualizada" : "Categoría no se pudo actualizar";
        }
        break;
    case 'desactivar':
        $rspt = $categoria->desactivar($idCat);
        echo $rspt ? "Categoría desactivada" : "Categoría no se pudo desactivar";
        break;
    case 'activar':
        $rspt = $categoria->activar($idCat);
        echo $rspt ? "Categoría activada" : "Categoría no se pudo activar";
        break;
    case 'mostrar':
        $rspt = $categoria->mostrar($idCat);
        // Codificar el resultado utilizando json
        echo json_encode($rspt);
        break;
    case 'listar':
        $rspt = $categoria->listar();
        // Declaramos un array
        $data = Array();
//        $reg = null
        while ($reg = $rspt->fetch_object()) {
            $bEdit = '<button class="btn btn-warning" onclick="mostrarDatos(' . $reg->idcategoria . ')"><i class="fa fa-pencil"></i></button>';
            $act = $bEdit . '<button class="btn btn-danger" onclick="desactivar(' . $reg->idcategoria . ')"><i class="fa fa-close"></i></button>';
            $des = $bEdit . '<button class="btn btn-primary" onclick="activar(' . $reg->idcategoria . ')"><i class="fa fa-check"></i></button>';
            $data[] = array(
                "0" => $reg->idcategoria,
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => ($reg->condicion)? '<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
                "4" => ($reg->condicion) ? $act : $des);
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