<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("Location:login.html");
} else {
    require 'header.php';
    if ($_SESSION['acceso'] == 1) {
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
                                <h1 class="box-title"> Usuario <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoRegistros">
                                <table id="tblListado"  class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Tipo Docum</th>
                                    <th>Numero Docum</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th>Login</th>
                                    <th>Imagen</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Tipo Docum</th>
                                    <th>Numero Docum</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th>Login</th>
                                    <th>Imagen</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioRegistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Nombre(*) : </label>
                                        <input type="hidden" name="idUsuario" id="idUsuario" >
                                        <input type="text" class="form-control" name="nomUsuario" id="nomUsuario" maxlength="100" placeholder="Ingrese nombre" required >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo Documento(*) : </label>
                                        <select name="tpdUsuario" id="tpdUsuario" class="form-control selectpicker" data-live-search="true" required>
                                            <option value="DNI">DNI</option>
                                            <option value="RUC">RUC</option>
                                            <option value="CEDULA">CEDULA</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Numero Documento(*) : </label>
                                        <input type="number" class="form-control" name="nmdUsuario" id="nmdUsuario" maxlength="20" placeholder="Ingrese Nombre" required >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Direccion : </label>
                                        <input type="text" class="form-control" name="dirUsuario" id="dirUsuario" maxlength="70" placeholder="Ingrese direccion">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Telefono : </label>
                                        <input type="text" class="form-control" name="tlfUsuario" id="tlfUsuario" maxlength="20" placeholder="Ingrese telefono">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Email : </label>
                                        <input type="text" class="form-control" name="emlUsuario" id="emlUsuario" maxlength="50" placeholder="Ingrese Email">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Cargo : </label>
                                        <input type="text" class="form-control" name="crgUsuario" id="crgUsuario" maxlength="20" placeholder="Ingrese Cargo">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Usuario Login(*) : </label>
                                        <input type="text" class="form-control" name="lgnUsuario" id="lgnUsuario" maxlength="20" placeholder="Ingrese Login">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Usuario Password(*) : </label>
                                        <input type="password" class="form-control" name="clvUsuario" id="clvUsuario" maxlength="64" placeholder="Ingrese Password">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="">Permisos : </label>
                                        <ul style="list-style: none;" id="permisos"></ul>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Imagen: </label>
                                        <input type="file" class="form-control" name="imgUsuario" id="imgUsuario">
                                        <input type="hidden" name="imgActUsu" id="imgActUsu">
                                        <img src="" width="120px" height="120px" id="imgMuestra">
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
    <script type="text/javascript" src="scripts/usuario.js"></script>
    <?php
}
ob_end_flush();
?>
