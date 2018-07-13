<?php

session_start();
require_once '../modelos/Usuario.modelo.php';

$usuario = new Usuario();
$idUsu = isset($_POST["idUsuario"]) ? limpiarCadena($_POST["idUsuario"]) : null;
$nomUsu = isset($_POST["nomUsuario"]) ? limpiarCadena($_POST["nomUsuario"]) : null;
$tpdUsu = isset($_POST["tpdUsuario"]) ? limpiarCadena($_POST["tpdUsuario"]) : null;
$nmdUsu = isset($_POST["nmdUsuario"]) ? limpiarCadena($_POST["nmdUsuario"]) : null;
$dirUsu = isset($_POST["dirUsuario"]) ? limpiarCadena($_POST["dirUsuario"]) : null;
$tlfUsu = isset($_POST["tlfUsuario"]) ? limpiarCadena($_POST["tlfUsuario"]) : null;
$emlUsu = isset($_POST["emlUsuario"]) ? limpiarCadena($_POST["emlUsuario"]) : null;
$crgUsu = isset($_POST["crgUsuario"]) ? limpiarCadena($_POST["crgUsuario"]) : null;
$lgnUsu = isset($_POST["lgnUsuario"]) ? limpiarCadena($_POST["lgnUsuario"]) : null;
$clvUsu = isset($_POST["clvUsuario"]) ? limpiarCadena($_POST["clvUsuario"]) : null;
$prmUsu = $_POST["permiso"] ?? null;
//$imgUsu = isset($_POST["imgUsuario"]) ? limpiarCadena($_POST["imgUsuario"]) : null;
$rspt = null;
switch ($_GET["op"]) {
    case 'guardar_editar':
        $imgN = $_FILES["imgUsuario"]["name"];
        $imgTN = $_FILES["imgUsuario"]["tmp_name"];
        $imgT = $_FILES["imgUsuario"]["type"];
        //  carga y guarda la imagen
        if (!file_exists($imgTN) || !is_uploaded_file($imgTN)) {//si no se cargo ninguna imagen
        //$imgN = $_POST["imgActual"];
            $imgN = (!empty($_POST["imgActual"])) ? limpiarCadena($_POST["imgActual"]) : null;
        } else {// si esta cargado la imagen
            if ($imgT == "image/jpg" || $imgT == "image/jpeg" || $imgT == "image/png") {
                $aNomImg = explode(".", $imgN); // crea un array de string, con el separador '.'
                //'round'redondea, los microsegundos del tiempo actual y concadena con la extension tomada del explode
                $imgN = round(microtime(true)) . '.' . end($aNomImg); //obtener un formato de tiempo para el nuevo nombre de la imagen, para que no se repita
                move_uploaded_file($imgTN, "../files/usuarios/" . $imgN);
            }
        }
        /* Encriptar clave Hash SHA256 en la contraseña */
        $claveHash = hash("SHA256", $clvUsu);

        /* El registro del articulo en la base de datos */
        if (empty($idUsu)) {// (Insert)Guarda Registros 
            $rspt = $usuario->insertar($nomUsu, $tpdUsu, $nmdUsu, $dirUsu, $tlfUsu, $emlUsu, $crgUsu, $lgnUsu, $claveHash, $imgN, $prmUsu);
            echo $rspt ? "Usuario registrado" : "Nose Pudieron registrar todos los datos del Usuario";
        } else {// (Update)Actualiza Registro $idCat
            $rspt = $usuario->editar($idUsu, $nomUsu, $tpdUsu, $nmdUsu, $dirUsu, $tlfUsu, $emlUsu, $crgUsu, $lgnUsu, $claveHash, $imgN, $prmUsu);
            echo $rspt ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }
        /* fin del guardado de imagen */
        /* Fin del registro del articulo */
        break;
    case 'desactivar':
        $rspt = $usuario->desactivar($idUsu);
        echo $rspt ? "Usuario desactivado" : "Usuario no se pudo desactivar";
        break;
    case 'activar':
        $rspt = $usuario->activar($idUsu);
        echo $rspt ? "Usuario activado" : "Usuario no se pudo activar";
        break;
    case 'mostrar':
        $rspt = $usuario->mostrar($idUsu);
        // Codificar el resultado utilizando json
        echo json_encode($rspt);
        break;
    case 'listar':
        $rspt = $usuario->listar();
        // Declaramos un array
        $data = Array();
        //$reg = null
        while ($reg = $rspt->fetch_object()) {
            $bEdit = '<button class="btn btn-warning" onclick="mostrarDatos(' . $reg->idusuario . ')"><i class="fa fa-pencil"></i></button>';
            $act = $bEdit . '<button class="btn btn-danger" onclick="desactivar(' . $reg->idusuario . ')"><i class="fa fa-close"></i></button>';
            $des = $bEdit . '<button class="btn btn-primary" onclick="activar(' . $reg->idusuario . ')"><i class="fa fa-check"></i></button>';
            $data[] = array(
                "0" => $reg->idusuario,
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento,
                "3" => $reg->num_documento,
                "4" => $reg->telefono,
                "5" => $reg->email,
                "6" => $reg->login,
                "7" => "<img src='../files/usuarios/" . $reg->imagen . "' height='50' width='50'>",
                "8" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>',
                "9" => ($reg->condicion) ? $act : $des);
        }
        $results = array(
            "sEcho" => 1, //Informacion para el dataTables
            "iTotalRecords" => count($data), // Enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // Enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'permisos':
        $id = $_GET["id"] ?? null;
        //Obtenemos todos los permisos de la tabla permisos
        require_once '../modelos/Permiso.modelo.php';
        $permiso = new Permiso();
        $rspta = $permiso->listar();
        //Obtener los permisos asignados al usuario
        $marcados = $usuario->listarMarcados($id);
        // Declaramos el array para almacenar todos los permisos marcados
        $valores = array();
        //Almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object()) {
            array_push($valores, $per->idpermiso);
        }
        // Mostramos la lista de permisos en la vista y si estan o no marcados
        while ($row = $rspta->fetch_object()) {
            $sw = in_array($row->idpermiso, $valores) ? 'checked' : '';
            echo '<li><input type="checkbox"' . $sw . ' name="permiso[]" value="' . $row->idpermiso . '" >' . $row->nombre . '</li>';
        }
        break;
    case 'verificar':
        $logina = $_POST['logina'] ?? null;
        $clavea = $_POST['clavea'] ?? null;
        //Hash SHA256 en la contraseña
        // $rspt = $usuario->verificar($logina, $clavea);
        $claveHash = hash("SHA256", $clavea);
        $rspt = $usuario->verificar($logina, $claveHash);
        $fetch = $rspt->fetch_object();
        if (isset($fetch)) {// si existe y tiene acceso
            //Declaramos y asignamos a las variables de session
            $_SESSION['id_usuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;
            //obtenemos los permisos del usuario
            $marcados = $usuario->listarMarcados($fetch->idusuario);
            //Declaramos el array para almacenar todos los permisos marcados
            $valores = array();
            //Almacenamos los permisos marcados en el array
            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }
            //Determinamos los accesos del usuario
            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0;
            in_array(3, $valores) ? $_SESSION['compras'] = 1 : $_SESSION['compras'] = 0;
            in_array(4, $valores) ? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0;
            in_array(5, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;
            in_array(6, $valores) ? $_SESSION['consulta_compras'] = 1 : $_SESSION['consulta_compras'] = 0;
            in_array(7, $valores) ? $_SESSION['consulta_ventas'] = 1 : $_SESSION['consulta_ventas'] = 0;
        }
        echo json_encode($fetch);
        break;
    case 'salir':
        // Limpiamos las variables de session
        session_unset();
        //Destruimos la session
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");
        break;
}
?>

