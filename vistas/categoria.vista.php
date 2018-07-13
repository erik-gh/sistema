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
                                <h1 class="box-title"> Categoria <button class="btn btn-success" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoRegistros">
                                <table id="tblListado"  class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" style="height: 400px;" id="formularioRegistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Nombre : </label>
                                        <input type="hidden" name="idCategoria" id="idCategoria" >
                                        <input type="text" class="form-control" name="nomCategoria" id="nomCategoria" maxlength="50" placeholder="Ingrese nombre" required >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Descripcion : </label>
                                        <input type="text" class="form-control" name="desCategoria" id="desCategoria" maxlength="256" placeholder="Ingrese descripcion" required >
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
    <script type="text/javascript" src="scripts/categoria.js"></script>
    <?php
}
//deshabilitar el almacenamiento en el buffer
ob_end_flush();
?>