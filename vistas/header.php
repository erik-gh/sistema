<?php
//si la session no esta iniciada
if (strlen(session_id()) < 1) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SIS-VENTAS</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="../public/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../public/css/font-awesome.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="../public/css/_all-skins.min.css">
        <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
        <link rel="shortcut icon" href="../public/img/favicon.ico">

        <!-- DATATABLES -->
        <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
        <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
        <!-- Bootstrap select -->
        <link rel="stylesheet" href="../public/css/bootstrap-select.min.css">

    </head>
    <body class="hold-transition skin-blue-light sidebar-mini">
        <!--<div class="wrapper">-->
        <header class="main-header">
            <!-- Logo -->
            <a href="index2.html" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>SIS</b>Ventas</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>SISVentas</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Navegacion</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo $_SESSION['nombre'] ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="../files/usuarios/<?php echo $_SESSION['imagen'] ?>" class="img-circle" alt="User Image">
                                    <p>
                                        Erik Tecse Huanca - Desarrollando Software
                                        
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="../ajax/usuario.ajax.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header"></li>
                    <?php
                    if ($_SESSION['escritorio'] == 1) {
                        echo '
                        <li>
                            <a href="escritorio.vista.php">
                                <i class="fa fa-taks"></i> <span>Escritorio</span>
                            </a>
                        </li>';
                    }
                    ?>
                    <?php
                    if ($_SESSION['almacen'] == 1) {
                        echo '
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-laptop"></i>
                                <span>Almacen</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu"> 
                                <li><a href="articulo.vista.php"><i class="fa fa-circle-o"></i> Articulos </a></li>
                                <li><a href="categoria.vista.php"><i class="fa fa-circle-o"></i> Categorías </a></li>
                            </ul>
                        </li>';
                    }
                    ?>
                    <?php
                    if ($_SESSION['compras'] == 1) {
                        echo '
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-th"></i>
                                <span>Compras</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu"> 
                                <li><a href="ingreso.vista.php"><i class="fa fa-circle-o"></i> Ingresos </a></li>
                                <li><a href="proveedor.vista.php"><i class="fa fa-circle-o"></i> Proveedores </a></li>
                            </ul>
                        </li>';
                    }
                    ?>
                    <?php
                    if ($_SESSION['ventas'] == 1) {
                        echo '
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-th"></i>
                                <span>Ventas</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu"> 
                                <li><a href="venta.vista.php"><i class="fa fa-circle-o"></i> Ventas </a></li>
                                <li><a href="cliente.vista.php"><i class="fa fa-circle-o"></i> Clientes </a></li>
                            </ul>
                        </li>';
                    }
                    ?>
                    <?php
                    if ($_SESSION['acceso'] == 1) {
                        echo '
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-folder"></i>
                                <span>Acceso</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu"> 
                                <li><a href="usuario.vista.php"><i class="fa fa-circle-o"></i> Usuarios </a></li>
                                <li><a href="permiso.vista.php"><i class="fa fa-circle-o"></i> Permisos </a></li>
                            </ul>
                        </li>';
                    }
                    ?>
                    <?php
                    if ($_SESSION['consulta_compras'] == 1) {
                        echo '
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-bar-chart"></i>
                                <span>Consulta Compras</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="compras_fecha.vista.php"><i class="fa fa-circle-o"></i> Consulta Compras</a></li>
                            </ul>
                        </li>';
                    }
                    ?>
                    <?php
                    if ($_SESSION['consulta_ventas'] == 1) {
                        echo '
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-bar-chart"></i>
                                <span>Consulta Ventas</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="ventas_fecha_cliente.vista.php"><i class="fa fa-circle-o"></i> Consulta Compras</a></li>
                            </ul>
                        </li>';
                    }
                    ?>
                    <li>
                        <a href="#">
                            <i class="fa fa-plus-square"></i><span> Ayuda </span>
                            <small class="label pull-right bg-red"> PDF </small>                                
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-info-circle"></i> <span>Acerca De ...</span>
                            <small class="label pull-right bg-yellow"> IT </small>     
                        </a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

