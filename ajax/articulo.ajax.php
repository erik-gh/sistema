<?php

require_once '../modelos/Articulo.modelo.php';

$articulo = new Articulo();
$idArt = isset($_POST["idArticulo"]) ? limpiarCadena($_POST["idArticulo"]) : null;
$idCat = isset($_POST["idCategoria"]) ? limpiarCadena($_POST["idCategoria"]) : null;
$codArt = isset($_POST["codArticulo"]) ? limpiarCadena($_POST["codArticulo"]) : null;
$nomCat = isset($_POST["nomArticulo"]) ? limpiarCadena($_POST["nomArticulo"]) : null;
$stkArt = isset($_POST["stkArticulo"]) ? limpiarCadena($_POST["stkArticulo"]) : null;
$desCat = isset($_POST["desArticulo"]) ? limpiarCadena($_POST["desArticulo"]) : null;
//$imgArt = isset($_POST["imgArticulo"]) ? limpiarCadena($_POST["imgArticulo"]) : "";
$rspt = null;
switch ($_GET["op"]) {
    case 'guardar_editar':

        $imgN = $_FILES["imgArticulo"]["name"];
        $imgTN = $_FILES["imgArticulo"]["tmp_name"];
        $imgT = $_FILES["imgArticulo"]["type"];

        /* carga y guarda la imagen */
        if (!file_exists($imgTN) || !is_uploaded_file($imgTN)) {//si no se cargo ninguna imagen
//            $imgN = $_POST["imgActual"];
            $imgN = (!empty($_POST["imgActual"])) ? limpiarCadena($_POST["imgActual"]) : 'Null';
        } else {// si esta cargado la imagen
            if ($imgT == "image/jpg" || $imgT == "image/jpeg" || $imgT == "image/png") {
                $aNomImg = explode(".", $imgN); // crea un array de string, con el separador '.'
                //'round'redondea, los microsegundos del tiempo actual y concadena con la extension tomada del explode
                $imgN = round(microtime(true)) . '.' . end($aNomImg); //obtener un formato de tiempo para el nuevo nombre de la imagen, para que no se repita
                move_uploaded_file($imgTN, "../files/articulos/" . $imgN);
            }
        }

        /* El registro del articulo en la base de datos */
        if (empty($idArt)) {// (Insert)Guarda Registros 
            $rspt = $articulo->insertar($idCat, $codArt, $nomCat, $stkArt, $desCat, $imgN);
            echo $rspt ? "Artículo registrado" : "Artículo no se pudo registrar";
        } else {// (Update)Actualiza Registro $idCat
            $rspt = $articulo->editar($idArt, $idCat, $codArt, $nomCat, $stkArt, $desCat, $imgN);
            echo $rspt ? "Artículo actualizado" : "Artículo no se pudo actualizar";
        }
        /* fin del guardado de imagen */
        /* Fin del registro del articulo */
        break;
    case 'desactivar':
        $rspt = $articulo->desactivar($idArt);
        echo $rspt ? "Artículo desactivado" : "Artículo no se pudo desactivar";
        break;
    case 'activar':
        $rspt = $articulo->activar($idArt);
        echo $rspt ? "Artículo activado" : "Artículo no se pudo activar";
        break;
    case 'mostrar':
        $rspt = $articulo->mostrar($idArt);
        // Codificar el resultado utilizando json
        echo json_encode($rspt);
        break;
    case 'listar':
        $rspt = $articulo->listar();
        // Declaramos un array
        $data = Array();
//        $reg = null
        while ($reg = $rspt->fetch_object()) {
            $bEdit = '<button class="btn btn-warning" onclick="mostrarDatos(' . $reg->idarticulo . ')"><i class="fa fa-pencil"></i></button>';
            $act = $bEdit . '<button class="btn btn-danger" onclick="desactivar(' . $reg->idarticulo . ')"><i class="fa fa-close"></i></button>';
            $des = $bEdit . '<button class="btn btn-primary" onclick="activar(' . $reg->idarticulo . ')"><i class="fa fa-check"></i></button>';
            $data[] = array(
                "0" => $reg->idarticulo,
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => $reg->codigo,
                "4" => $reg->stock,
                "5" => "<img src='../files/articulos/" . $reg->imagen . "' height='50' width='50'>",
                "6" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>',
                "7" => ($reg->condicion) ? $act : $des);
        }
        $results = array(
            "sEcho" => 1, //Informacion para el dataTables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'select_categoria':
        require_once "../modelos/Categoria.modelo.php";
        $selcat = new Categoria();
        $rspt = $selcat->select();
        while ($row = $rspt->fetch_object()) {
            echo '<option value=' . $row->idcategoria . '>' . $row->nombre . '</option>';
        }
        break;
}
?>

