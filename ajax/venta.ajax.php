<?php

if (strlen(session_id()) < 1) {
    session_start();
}
require_once '../modelos/Venta.modelo.php';

$venta = new Venta();

$idVent = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : null;

$idCli = isset($_POST["idCliente"]) ? limpiarCadena($_POST["idCliente"]) : null;
$idUsu = $_SESSION["id_usuario"] ?? null;
$tpcVent = isset($_POST["tcventa"]) ? limpiarCadena($_POST["tcventa"]) : null;
$secVent = isset($_POST["scventa"]) ? limpiarCadena($_POST["scventa"]) : null;
$nucVent = isset($_POST["ncventa"]) ? limpiarCadena($_POST["ncventa"]) : null;
$fehVent = isset($_POST["fhventa"]) ? limpiarCadena($_POST["fhventa"]) : null;
$impVent = isset($_POST["impventa"]) ? limpiarCadena($_POST["impventa"]) : null;

$tcDet = isset($_POST["tcDetalle"]) ? limpiarCadena($_POST["tcDetalle"]) : null;

$idArt = $_POST["idArticulo"] ?? null;
$cntVent = $_POST["cantArticulo"] ?? null;
$prcVent = $_POST["precioVenta"] ?? null;
$desVent = $_POST["desVenta"] ?? null;
$tovVent = $_POST["total_venta"] ?? null;

$rspt = null;
switch ($_GET["op"]) {
    case 'guardar_editar': {
            if (empty($idVent)) {// (Insert)Guarda Registros
                $rspt = $venta->insertar($idCli, $idUsu, $tpcVent, $secVent, $nucVent, $fehVent, $impVent, $tovVent, $idArt, $cntVent, $prcVent, $desVent);
                echo $rspt ? "venta registrado" : "No pudieron registrar todos los datos del venta";
            } else {

            }
            break;
        }
    case 'anular': {
            $rspt = $venta->anular($idVent);
            echo $rspt ? "venta anulado" : "venta no se pudo anular";
            break;
        }
    case 'mostrar': {
            $rspt = $venta->mostrar($idVent);
            // Codificar el resultado utilizando json
            echo json_encode($rspt);
            break;
        }
    case 'listar_detalle': {  //Recibimos el venta
            $id = $_GET['id'];
            $rspt = $venta->listarDetalles($id);
            $total = 0;
            echo '<thead style="background-color: #A9D0F5">
                          <th>#</th>
                          <th>Articulo</th>
                          <th>Cantidad</th>
                          <th>Precio Venta</th>
                          <th>Descuento</th>
                          <th>SubTotal</th>
                      </thead>';
            while ($row = $rspt->fetch_object()) {
                $descuento = $row->descuento * ($row->precio_venta * $row->cantidad) / 100;
                $subtotal = ($row->precio_venta * $row->cantidad) - $descuento;
                echo
                '<tr class="filas">
                          <td>' . $row->iddetalle_venta . '</td>
                          <td>' . $row->nombre . '</td>
                          <td>' . $row->cantidad . '</td>
                          <td>' . $row->precio_venta . '</td>
                          <td>' . $row->descuento . '</td>
                          <td>' . $subtotal . '</td>
                      </tr>';
                $total += $subtotal;
            }
            echo '<tfoot>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><h4 id="total">S/. ' . $total . '</h4><input type="hidden" name="total_compra" id="total_compra"></th>
                    </tfoot>';
            break;
        }
    case 'listar': {
            $rspt = $venta->listar();
            // Declaramos un array
            $data = Array();
            while ($reg = $rspt->fetch_object()) {
                $url = null;
                if ($reg->tipo_comprobante == 'TICKET') {
                    $url = '../reportes/exTicket.php?id=';
                } else {
                    $url = '../reportes/exportFactura.php?id= ';
                }
                $bEdit = '<button style="margin-left:2em" class="btn btn-warning" onclick="mostrarDatos(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button>';
                $acp = $bEdit . '<button style="margin-left:1em" class="btn btn-danger" onclick="anular(' . $reg->idventa . ')"><i class="fa fa-close"></i></button>';

                $data[] = array(
                    "0" => $reg->idventa,
                    "1" => $reg->fecha,
                    "2" => $reg->cliente,
                    "3" => $reg->usuario,
                    "4" => $reg->tipo_comprobante,
                    "5" => $reg->serie_comprobante . '-' . $reg->num_comprobante,
                    "6" => $reg->total_venta,
                    "7" => ($reg->estado == 'ACEPTADO') ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>',
                    "8" => (($reg->estado == 'ACEPTADO') ? $acp : $bEdit) . '<a target="_blank" href="' . $url . $reg->idventa . '"><button style="margin-left:1em" class="btn btn-info"><i class="fa fa-file"></i></button></a>'
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
    case 'select_cliente': {
            require_once "../modelos/Persona.modelo.php";
            $persona = new Persona;
            $rspt = $persona->listarCliente();
            while ($reg = $rspt->fetch_object()) {
                echo '<option value="' . $reg->idpersona . '">' . $reg->nombre . '</option>';
            }
            break;
        }
    case 'listar_articulos': {
            require_once '../modelos/Articulo.modelo.php';
            $articulo = new Articulo();
            $rspt = $articulo->listarActivosVenta();
            // Declaramos un array
            $data = Array();
            while ($reg = $rspt->fetch_object()) {
                $btnAgregar = '<button class="btn btn-warning" onclick="agregarDetalle(' . $reg->idarticulo . ', \'' . $reg->nombre . '\')"><span class="fa fa-plus"></span></button>';
                $data[] = array(
                    "0" => $reg->idarticulo,
                    "1" => $reg->nombre,
                    "2" => $reg->categoria,
                    "3" => $reg->codigo,
                    "4" => $reg->stock,
                    "5" => $reg->precio_venta,
                    "6" => "<img src='../files/articulos/" . $reg->imagen . "' height='50' width='50'>",
                    "7" => $btnAgregar);
            }
            $results = array(
                "sEcho" => 1, //Informacion para el dataTables
                "iTotalRecords" => count($data), // Enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
                "aaData" => $data);
            echo json_encode($results);
            break;
        }
}

?>
