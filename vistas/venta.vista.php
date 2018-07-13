<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("Location:login.html");
} else {
    require 'header.php';
    if ($_SESSION['ventas'] == 1) {
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
                                <h1 class="box-title"> Ventas
                                    <button id="btnAgregar" class="btn btn-success" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button>
                                </h1>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoRegistros">
                                <table id="tblListado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Usuario</th>
                                    <th>Tip Comprobante</th>
                                    <th>Num Comprobante</th>
                                    <th>Total Compra</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Usuario</th>
                                    <th>Tip Comprobante</th>
                                    <th>Num Comprobante</th>
                                    <th>Total Compra</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- <div class="panel-body" style="height: 770px;" id="formularioRegistros"> -->
                            <div class="panel-body" id="formularioRegistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label>Cliente(*) : </label>
                                        <input type="hidden" name="idventa" id="idventa">
                                        <select name="idCliente" id="idCliente" class="form-control selectpicker" data-live-search="true" required></select>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Fecha(*) : </label>
                                        <input type="date" class="form-control" name="fhventa" id="fhventa" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo Comprobante(*) : </label>
                                        <select class="form-control selectpicker" name="tcventa" id="tcventa">
                                            <option value="BOLETA">Boleta</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="TICKET">Ticket</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Serie(*) : </label>
                                        <input type="text" class="form-control" name="scventa" id="scventa" maxlength="7" placeholder="Serie" required>
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Numero(*) : </label>
                                        <input type="text" class="form-control" name="ncventa" id="ncventa" maxlength="10" placeholder="Numero" required>
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Impuesto(*) : </label>
                                        <input type="text" class="form-control" name="impventa" id="impventa" required>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                                        <a data-toggle="modal" href="#myModal">
                                            <button class="btn btn-primary" type="button" id="btnAgregarArt"><span class="fa fa-plus"></span>Agregar Articulos</button>
                                        </a>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table id="tblDetalles" class="table table-striped table-bordered table-condensed table-hover">
                                            <thead style="background-color: #A9D0F5">
                                            <th>#</th>
                                            <th>Articulo</th>
                                            <th>Cantidad</th>
                                            <th>Precio Compra</th>
                                            <th>Descuento</th>
                                            <th>SubTotal</th>
                                            <th>Opciones</th>
                                            </thead>
                                            <tfoot>
                                            <th>TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><h4 id="total">S/.0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>
                                            <th></th>
                                            </tfoot>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="divGuardarCancelar">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                                        <button id="btnCancelar" class="btn btn-danger" onclick="cancelarForm()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
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
        <!--Modal-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width: 40% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Seleccione un Articulo</h4>
                    </div>
                    <div class="modal-body">
                        <table id="tblArticulos" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Codigo</th>
                            <th>Stock</th>
                            <th>Precio Venta</th>
                            <th>Imagen</th>
                            <th>Opcion</th>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Codigo</th>
                            <th>Stock</th>
                            <th>Precio Venta</th>
                            <th>Imagen</th>
                            <th>Opcion</th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal-->
        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/venta.js"></script>
    <?php
}
//deshabilitar el almacenamiento en el buffer
ob_end_flush();
?>