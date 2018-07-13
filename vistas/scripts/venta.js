var tabla;

// Funcion que se ejecuta al inicio
function init() {
    mostrarForm(false);
    listar();
    $("#formulario").on("submit", function (e) {
        guardarEditar(e);
    });
    //Cargamos los items al select Cliente
    $.post("../ajax/venta.ajax.php?op=select_cliente", function (r) {
        $("#idCliente").html(r);
        $('#idCliente').selectpicker('refresh');
    });
}

//Funcion limpiar
function limpiarForm() {
    //esta codigo limpia todos los campos del formulario (form)
    $("#formulario")[0].reset();
    $("#impventa").val("0");
    $("#total_venta").val("");
    $(".filas").remove();
    $("#total").html("S/. 0.00");
    //Obtenemos la fecha actual
    var now = new Date();
    var dia = ("0" + now.getDate()).slice(-2);
    var mes = ("0" + (now.getMonth() + 1)).slice(-2);
    var hoy = now.getFullYear() + "-" + (mes) + "-" + (dia);
    $("#fhventa").val(hoy);
    /*
     $("#idventa").val("");
     $("#idCliente").val('').selectpicker('refresh');
     $("#fhventa").val("");
     $("#tcventa").val("");
     $("#scventa").val("");
     $("#ncventa").val("");
     $("#impventa").val("");
     $("#impventa").val("");
     // $("#imgMuestra").attr("src", "");
     */
}

// Funcion mostrar formulario
function mostrarForm(flag) {
    limpiarForm();
    if (flag) {// si es true
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        // $("#btnGuardar").prop("disabled", false);
        $("#btnAgregar").hide();
        listarArticulos();
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
    } else { // si es false
        $("#listadoRegistros").show();
        $("#formularioRegistros").hide();
        $("#btnAgregar").show();
    }
}

//Funcion CancelarForm
function cancelarForm() {
    limpiarForm();
    mostrarForm(false);
}

// Funcion listar
function listar() {
    tabla = $("#tblListado").dataTable({
        "aProcessing": true, // Activamos el procesamiento del dataTables
        "aServerSide": true, // Paginacion y filtrado realizados por el servidor
        dom: "Bfrtip", //Definimos los elementos del control de la tabla
        buttons: [//buttons
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: "../ajax/venta.ajax.php?op=listar",
            type: 'get',
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion
        "order": [[0]] // Ordenar (columna, orden) , desc, asc
    }).DataTable();
}

// Funcion listar Articulos
function listarArticulos() {
    tabla = $("#tblArticulos").dataTable({
        "aProcessing": true, // Activamos el procesamiento del dataTables
        "aServerSide": true, // Paginacion y filtrado realizados por el servidor
        dom: "Bfrtip", //Definimos los elementos del control de la tabla
        buttons: [//buttons
        ],
        "ajax": {
            url: "../ajax/venta.ajax.php?op=listar_articulos",
            type: 'get',
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion
        "order": [[0, "asc"]] // Ordenar (columna, orden) , desc, asc
    }).DataTable();
}
function agregarDetalle(id_articulo, nom_articulo) {
    var cantidad = 1;
    var precio_venta = 1;
    var descuento = 1;
    var cal_desc = (precio_venta * cantidad) * descuento / 100;
    if (id_articulo != "") {
        // alert("Entro la funcion agregar detalle");
        var subtotal = (cantidad * precio_venta) - cal_desc;

        var fila =
                '<tr class="filas" id="fila' + cont + '">' +
                '<td><input type="hidden" name="idArticulo[]" value="' + id_articulo + '">' + id_articulo + '</td>' +
                '<td><input type="hidden" name="nomArticulo[]" value="' + nom_articulo + '">' + nom_articulo + '</td>' +
                '<td><input type="number" name="cantArticulo[]" id="cantArticulo[]" value="' + cantidad + '"></td>' +
                '<td><input type="number" name="precioVenta[]" id="precioVenta[]" value="' + precio_venta + '"></td>' +
                '<td><input type="number" name="desVenta[]" id="desVenta[]" value="' + descuento + '"> %</td>' +
                '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                '<td style="text-align:center;">' +
                '<button style="margin-right: 1em" type="button" class="btn btn-info"onclick="modificarSubtotales()" ><i class="fa fa-refresh" ></i></button>' +
                '<button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-remove" ></i></button>' +
                '</td>' +
                '</tr>';
        cont++;
        detalles++;
        $("#tblDetalles").append(fila);
        $("#btnGuardar").show();
        // modificarSubtotales();
    } else {
        alert("Error al ingresar el detalle, revisar los datos del articulo");
    }
}
//Funcion Guardar y Editar
function guardarEditar(e) {
    e.preventDefault(); //No se activará la accion predeterminada del evento
    // $("#btnGuardar").prop("disabled", true);
    // El FormData toma los datos de los name="" del formulario
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/venta.ajax.php?op=guardar_editar",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            swal(resp.trim(),
                    'Revise el cambio',
                    'success'
                    ).then(function (e) {
                if (e.value) {
                    mostrarForm(false);
                    listar();
                    // tabla.ajax.reload();
                }
            });
            /*
             swal(resp.trim(),
             'Revise el cambio',
             'success'
             ).then((e) => {
             if (e.value) {
             mostrarForm(false);
             tabla.ajax.reload();
             }
             });
             */
        }
    });
    limpiarForm();
}

// Funcion mostrar Datos
function mostrarDatos(idVenta) {
    $.post("../ajax/venta.ajax.php?op=mostrar", {idventa: idVenta}, function (data, status) {
        dataJson = JSON.parse(data); //dataJson se convirtio tipo json de data tipo cadena
        // console.log("data", dataJson);
        mostrarForm(true);
        //dataJson tiene los nombres de las columnas tabla 'categoria'
        //se obtiene los valores y se les asigna a las respectivas id del formulario
        $("#idventa").val(dataJson.idventa);
        $("#idCliente").val(dataJson.idCliente);
        $('#idCliente').selectpicker('refresh'); // Se refresca despues de agregar o eliminar elementos
        $("#fhventa").val(dataJson.fecha);
        $("#tcventa").val(dataJson.tipo_comprobante);
        $('#tcventa').selectpicker('refresh'); // Se refresca despues de agregar o eliminar elementos
        $("#scventa").val(dataJson.serie_comprobante);
        $("#ncventa").val(dataJson.num_comprobante);
        $("#impventa").val(dataJson.impuesto);
        //Ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });
    $.post("../ajax/venta.ajax.php?op=listar_detalle&id=" + idVenta, function (r) {
        $("#tblDetalles").html(r);
    })
}

// Funcion para desactivar registros
function anular(idVenta) {
    swal({
        title: "¿Esta seguro de anular el venta?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, anular venta",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        // console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/venta.ajax.php?op=anular",
                    {idventa: idVenta},
                    function (datos) {
                        swal(datos.trim(), 'Revise el cambio', 'success').then((e) => {
                            if (e.value) {
                                tabla.ajax.reload();
                            }
                        });
                    }
            );
        }
    })
            ;
}

function marcarImpuesto() {
    var tipo_comprobante = $("#tcventa option:selected").val();
    if (tipo_comprobante === 'FACTURA') {
        $("#impventa").val(impuesto);
    } else {
        $("#impventa").val("0");
    }
}

function modificarSubtotales() {
    var cant = document.getElementsByName("cantArticulo[]");
    var prec = document.getElementsByName("precioVenta[]");
    var desc = document.getElementsByName("desVenta[]");
    var sub = document.getElementsByName("subtotal");
    for (var i = 0; i < cant.length; i++) {
        var ventC = cant[i];
        var ventP = prec[i];
        var ventD = desc[i];
        var calDes = (ventC.value * ventP.value) * ventD.value / 100;
        var inpS = sub[i];
        inpS.value = (ventC.value * ventP.value) - calDes;
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }
    calcularTotales();
}

function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;
    for (var i = 0; i < sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("S/. " + total);
    $("#total_venta").val(total);
    evaluar();
}

function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    } else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    calcularTotales();
    detalles -= 1
}

//Declaracion de variables necesarios para trabajar con los campos y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
$("#btnGuardar").hide();
$("#tcventa").change(marcarImpuesto);
init();
