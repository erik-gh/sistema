<?php

require_once '../modelos/Persona.modelo.php';

$persona = new Persona();
$idPer = isset($_POST["idPersona"]) ? limpiarCadena($_POST["idPersona"]) : null;
$tpPer = isset($_POST["tpPersona"]) ? limpiarCadena($_POST["tpPersona"]) : null;
$nomPer = isset($_POST["nomPersona"]) ? limpiarCadena($_POST["nomPersona"]) : null;
$tpdPer = isset($_POST["tpdPersona"]) ? limpiarCadena($_POST["tpdPersona"]) : null;
$nmdPer = isset($_POST["nmdPersona"]) ? limpiarCadena($_POST["nmdPersona"]) : null;
$dirPer = isset($_POST["dirPersona"]) ? limpiarCadena($_POST["dirPersona"]) : null;
$tlfPer = isset($_POST["tlfPersona"]) ? limpiarCadena($_POST["tlfPersona"]) : null;
$emlPer = isset($_POST["emlPersona"]) ? limpiarCadena($_POST["emlPersona"]) : null;
$rspt = null;
switch ($_GET["op"]) {
    case 'guardar_editar':
        if (empty($idPer)) {// (Insert)Guarda Registros 
            $rspt = $persona->insertar($tpPer, $nomPer, $tpdPer, $nmdPer, $dirPer, $tlfPer, $emlPer);
            echo $rspt ? "Persona registrada" : "Persona no se pudo registrar";
        } else {// (Update)Actualiza Registro $idCat
            $rspt = $persona->editar($idPer, $tpPer, $nomPer, $tpdPer, $nmdPer, $dirPer, $tlfPer, $emlPer);
            echo $rspt ? "Persona actualizada" : "Persona no se pudo actualizar";
        }
        break;
    case 'eliminar':
        $rspt = $persona->eliminar($idPer);
        echo $rspt ? "Persona eliminada" : "Persona no se pudo eliminar";
        break;
    case 'mostrar':
        $rspt = $persona->mostrar($idPer);
        // Codificar el resultado utilizando json
        echo json_encode($rspt);
        break;
    case 'listar_proveedores':
        $rspt = $persona->listarProveedor();
        // Declaramos un array
        $data = Array();
        while ($reg = $rspt->fetch_object()) {
            $bMost = '<button style="margin-left: 2em" class="btn btn-warning" onclick="mostrarDatos(' . $reg->idpersona . ')"><i class="fa fa-pencil"></i></button>';
            $bElim = '<button style="margin-left:1em" class="btn btn-danger" onclick="eliminar(' . $reg->idpersona . ')"><i class="fa fa-trash"></i></button>';
            $btnsME = $bMost . ' ' . $bElim;
            $data[] = array(
                "0" => $reg->idpersona,
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento,
                "3" => $reg->num_documento,
                "4" => $reg->telefono,
                "5" => $reg->email,
                "6" => $btnsME
            );
        }
        $results = array(
            "sEcho" => 1, //Informacion para el dataTables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'listar_clientes':
        $rspt = $persona->listarCliente();
        // Declaramos un array
        $data = Array();
        while ($reg = $rspt->fetch_object()) {
            $bMost = '<button style="margin-left:2em" class="btn btn-warning" onclick="mostrarDatos(' . $reg->idpersona . ')"><i class="fa fa-pencil"></i></button>';
            $bElim = '<button style="margin-left:1em" class="btn btn-danger" onclick="eliminar(' . $reg->idpersona . ')"><i class="fa fa-trash"></i></button>';
            $btnsME = $bMost . ' ' . $bElim;
            $data[] = array(
                "0" => $reg->idpersona,
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento,
                "3" => $reg->num_documento,
                "4" => $reg->telefono,
                "5" => $reg->email,
                "6" => $btnsME
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

