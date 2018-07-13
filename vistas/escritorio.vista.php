<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("Location:login.html");
} else {
    require 'header.php';
    if ($_SESSION['escritorio'] == 1) {

        require_once "../modelos/Consultas.modelo.php";
        $consulta = new Consultas();
        $rspt = $consulta->totalCompraHoy();
        $regCompras = $rspt->fetch_object();
        $totalCompra = $regCompras->total_compra;

        $rspt = $consulta->totalVentaHoy();
        $regVentas = $rspt->fetch_object();
        $totalVenta = $regVentas->total_venta;

        /* Grafico de Compras*/
        //Datos para mostrar el grafico de barras de las compras
        $compras10 = $consulta->comprasUltimos10Dias();
        $fec_compras = '';
        $tot_compras = '';
        while ($reg_fec_comp = $compras10->fetch_object()) {
            $fec_compras = $fec_compras . '"' . $reg_fec_comp->fecha . '",';
            $tot_compras = $tot_compras . '"' . $reg_fec_comp->total . '",';
        }
        //Quitamos la ultima coma
        $fec_comp = substr($fec_compras, 0, -1);
        $tot_comp = substr($tot_compras, 0, -1);

        /* Grafico de Ventas*/
        //Datos para mostrar el grafico de barras de las compras
        $ventas12 = $consulta->ventasUltimos12Meses();
        $fec_ventas = '';
        $tot_ventas = '';
        while ($reg_fec_ven = $ventas12->fetch_object()) {
            $fec_ventas = $fec_ventas . '"' . $reg_fec_ven->fecha . '",';
            $tot_ventas = $tot_ventas . '"' . $reg_fec_ven->total . '",';
        }
        //Quitamos la ultima coma
        $fec_vent = substr($fec_ventas, 0, -1);
        $tot_vent = substr($tot_ventas, 0, -1);
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
                                <h1 class="box-title"> Escritorio </h1>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="small-box bg-aqua">
                                        <div class="inner"><h4 style="font-size: 17px;"><strong>S/. <?php echo $totalCompra ?></strong></h4>
                                            <p> Compras </p>
                                        </div>
                                        <div class="icon"><i class="ion ion-bag"></i></div>
                                        <a href="ingreso.vista.php" class="small-box-footer"> Compras <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h4 style="font-size: 17px;"><strong>S/. <?php echo $totalVenta ?></strong></h4>
                                            <p> Ventas </p>
                                        </div>
                                        <div class="icon"><i class="ion ion-bag"></i></div>
                                        <a href="venta.vista.php" class="small-box-footer"> Ventas <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border"> Compras de los ultimos 10 dias</div>
                                    </div>
                                    <div class="box-body">
                                        <canvas id="compras" width="400" height="300"></canvas>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border"> Ventas de los ultimos 12 Meses</div>
                                    </div>
                                    <div class="box-body">
                                        <canvas id="ventas" width="400" height="300"></canvas>
                                    </div>
                                </div>
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
    <script type="text/javascript" src="../public/js/Chart.min.js"></script>
    <script type="text/javascript" src="../public/js/Chart.bundle.min.js"></script>
    <script type="text/javascript">
        var ctx = document.getElementById("compras").getContext('2d');
        var compras = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $fec_comp; ?>],
                datasets: [{
                    label: '# Compras en S/. de los ultimos 10 dias',
                    data: [<?php echo $tot_comp; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var ctx = document.getElementById("ventas").getContext('2d');
        var ventas = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $fec_vent; ?>],
                datasets: [{
                    label: '# Compras en S/. de los ultimos 12 meses',
                    data: [<?php echo $tot_vent; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
    <?php
}
//deshabilitar el almacenamiento en el buffer
ob_end_flush();
?>