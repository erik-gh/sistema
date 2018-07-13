<?php
if (strlen(session_id()) < 1) {
    session_start();
}
require_once '../modelos/Ingreso.modelo.php';

$ingreso = new Ingreso();
$idIng = isset($_POST["idIngreso"]) ? limpiarCadena($_POST["idIngreso"]) : null;
$idPro = isset($_POST["idProveedor"]) ? limpiarCadena($_POST["idProveedor"]) : null;
$idUsu = $_SESSION["id_usuario"] ?? null;
$tpcIng = isset($_POST["tcIngreso"]) ? limpiarCadena($_POST["tcIngreso"]) : null;
$secIng = isset($_POST["scIngreso"]) ? limpiarCadena($_POST["scIngreso"]) : null;
$nucIng = isset($_POST["ncIngreso"]) ? limpiarCadena($_POST["ncIngreso"]) : null;
$fehIng = isset($_POST["fhIngreso"]) ? limpiarCadena($_POST["fhIngreso"]) : null;
$impIng = isset($_POST["impIngreso"]) ? limpiarCadena($_POST["impIngreso"]) : null;
$tcDet = isset($_POST["tcDetalle"]) ? limpiarCadena($_POST["tcDetalle"]) : null;
$idArt = $_POST["idArticulo"] ?? null;
$cntIng = $_POST["cantArticulo"] ?? null;
$prcIng = $_POST["precioCompra"] ?? null;
$prvIng = $_POST["precioVenta"] ?? null;
$tocIng = $_POST["total_compra"] ?? null;

$rspt = null;
switch ($_GET["op"]) {
    case 'guardar_editar':
        if (empty($idIng)) {// (Insert)Guarda Registros
            $rspt = $ingreso->insertar($idPro, $idUsu, $tpcIng, $secIng, $nucIng, $fehIng, $impIng, $tocIng, $idArt, $cntIng, $prcIng, $prvIng);
            echo $rspt ? "Ingreso registrado" : "No pudieron registrar todos los datos del ingreso";
        } else {
        }
        break;
    case 'anular':
        $rspt = $ingreso->anular($idIng);
        echo $rspt ? "Ingreso anulado" : "Ingreso no se pudo anular";
        break;
    case 'mostrar':
        $rspt = $ingreso->mostrar($idIng);
        // Codificar el resultado utilizando json
        echo json_encode($rspt);
        break;
    case 'listar_detalle':
        //Recibimos el ingreso
        $id = $_GET['id'];
        $rspt = $ingreso->listarDetalles($id);
        $total=0;
        echo '<thead style="background-color: #A9D0F5">
                    <th>#</th>
                    <th>Articulo</th>
                    <th>Cantidad</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>SubTotal</th>
                </thead>';
        while ($row = $rspt->fetch_object()) {
            echo
                '<tr class="filas">
                    <td>' . $row->idarticulo . '</td>
                    <td>' . $row->nombre . '</td>
                    <td>' . $row->cantidad . '</td>
                    <td>' . $row->precio_compra . '</td>
                    <td>' . $row->precio_venta . '</td>
                    <td>'.$row->precio_compra*$row->cantidad.'</td>
                </tr>';
            $total+=($row->precio_compra*$row->cantidad);
        }
        echo '<tfoot>
                  <th>TOTAL</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th><h4 id="total">S/. '.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
              </tfoot>';
        break;
    case 'listar':
        $rspt = $ingreso->listar();
        // Declaramos un array
        $data = Array();
//        $reg = null
        while ($reg = $rspt->fetch_object()) {
            $bEdit = '<button style="margin-left:2em" class="btn btn-warning" onclick="mostrarDatos(' . $reg->idingreso . ')"><i class="fa fa-eye"></i></button>';
            $acp = $bEdit . '<button style="margin-left:1em" class="btn btn-danger" onclick="anular(' . $reg->idingreso . ')"><i class="fa fa-close"></i></button>';
            $data[] = array(
                "0" => $reg->idingreso,
                "1" => $reg->fecha,
                "2" => $reg->proveedor,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->serie_comprobante . '-' . $reg->num_comprobante,
                "6" => $reg->total_compra,
                "7" => ($reg->estado == 'ACEPTADO') ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>',
                "8" => ($reg->estado == 'ACEPTADO') ? $acp : $bEdit);
        }
        $results = array(
            "sEcho" => 1, //Informacion para el dataTables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'select_proveedor':
        require_once "../modelos/Persona.modelo.php";
        $persona = new Persona;
        $rspt = $persona->listarProveedor();
        while ($reg = $rspt->fetch_object()) {
            echo '<option value="' . $reg->idpersona . '">' . $reg->nombre . '</option>';
        }
        break;
    case 'listar_articulos':
        require_once '../modelos/Articulo.modelo.php';
        $articulo = new Articulo();
        $rspt = $articulo->listarActivos();
        // Declaramos un array
        $data = Array();
//        $reg = null
        while ($reg = $rspt->fetch_object()) {
            $btnAgregar = '<button class="btn btn-warning" onclick="agregarDetalle(' . $reg->idarticulo . ', \'' . $reg->nombre . '\')"><span class="fa fa-plus"></span></button>';
            $data[] = array(
                "0" => $reg->idarticulo,
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => $reg->codigo,
                "4" => $reg->stock,
                "5" => "<img src='../files/articulos/" . $reg->imagen . "' height='50' width='50'>",
                "6" => $btnAgregar);
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

