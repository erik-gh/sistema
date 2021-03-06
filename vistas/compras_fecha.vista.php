<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("Location:login.html");
} else {
    require 'header.php';
    if ($_SESSION['consulta_compras'] == 1) {
        ?>
        <!-- Contenido -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- box -->
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="box-title"> Consulta de compras por fecha </h1>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoRegistros">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label> Fecha Inicio </label>
                                    <input type="date" class="form_control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label> Fecha Fin </label>
                                    <input type="date" class="form_control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <table id="tblListado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th>Comprobante</th>
                                    <th>Numero</th>
                                    <th>Total compra</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th>Comprobante</th>
                                    <th>Numero</th>
                                    <th>Total compra</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div><!-- Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->
        </div><!--content-wrapper-->
        <!--Fin-Contenido-->
        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/compras_fecha.js"></script>
    <?php
}
//deshabilitar el almacenamiento en el buffer
ob_end_flush();
?>