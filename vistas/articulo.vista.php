<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("Location:login.html");
} else {
    require 'header.php';
    if ($_SESSION['almacen'] == 1) {
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
                                <h1 class="box-title"> Artículo 
                                    <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button>
                                    <a target="_blank" href="../reportes/rpt_articulos.php"><button class="btn btn-info"> Reporte </button></a>
                                </h1>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoRegistros">
                                <table id="tblListado"  class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Codigo</th>
                                    <th>Stock</th>
                                    <th>Imagen</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Codigo</th>
                                    <th>Stock</th>
                                    <th>Imagen</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioRegistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Nombre(*) : </label>
                                        <input type="hidden" name="idArticulo" id="idArticulo" >
                                        <input type="text" class="form-control" name="nomArticulo" id="nomArticulo" maxlength="100" placeholder="Ingrese nombre" required >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Categoria(*) : </label>
                                        <select name="idCategoria" id="idCategoria" class="form-control selectpicker" data-live-search="true" required></select>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Stock(*) : </label>
                                        <input type="number" class="form-control" name="stkArticulo" id="stkArticulo" placeholder="Ingrese stock" required >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Descripcion : </label>
                                        <input type="text" class="form-control" name="desArticulo" id="desArticulo" maxlength="256" placeholder="Ingrese descripcion">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Imagen: </label>
                                        <input type="file" class="form-control" name="imgArticulo" id="imgArticulo">
                                        <input type="hidden" name="imgActual" id="imgActual">
                                        <img src="" width="150px" height="120px" id="imgMuestra">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Codigo: </label>
                                        <input type="text" class="form-control" name="codArticulo" id="codArticulo" placeholder="Codigo Barras">
                                        <br/>
                                        <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
                                        <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
                                        <div id="print">
                                            <svg id="barcode"></svg>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                                        <button class="btn btn-danger" onclick="cancelarForm()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Fin centro -->
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
    <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
    <script type="text/javascript" src="../public/js/jquery.PrintArea.js "></script>
    <script type="text/javascript" src="scripts/articulo.js"></script>
    <?php
}
ob_end_flush();
?>